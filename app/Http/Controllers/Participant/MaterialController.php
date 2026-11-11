<?php

namespace App\Http\Controllers\Participant;

use App\Enums\SubmissionType;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\ModuleSubmission;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $user = $this->ensureDivisionIdResolved($user);

        if (empty($user->division_id)) {
            return redirect()->route('participant.dashboard')
                ->with('error', 'Anda belum terdaftar pada divisi manapun. Silakan hubungi admin untuk verifikasi pendaftaran.');
        }

        $acceptedRegistration = $this->getActiveAcceptedRegistration($user);
        $startDate = $acceptedRegistration?->periode_mulai;
        $endDate = $acceptedRegistration?->periode_selesai;

        $dashboardController = app(\App\Http\Controllers\Participant\DashboardController::class);
        $pembimbingIds = $dashboardController->resolvePembimbingIdsForDashboard($user);
        $divisionId = $dashboardController->resolveDivisionIdForDashboard($user);

        $baseQuery = Material::with(['submissions' => function ($q) {
            $q->where('user_id', auth()->id());
        }]);

        if (!empty($pembimbingIds)) {
            $baseQuery->whereIn('pembimbing_id', $pembimbingIds);
        } elseif ($divisionId !== null) {
            $baseQuery->whereHas('pembimbing', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            });
        } else {
            $baseQuery->where(function ($q) use ($user) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('materials', 'division_id')) {
                    $q->where('division_id', $user->division_id);
                }
                $q->orWhereHas('pembimbing', function ($sub) use ($user) {
                    $sub->where('division_id', $user->division_id);
                });
            });
        }

        // Pastikan hanya materi umum divisi atau tugas mandiri milik peserta ini yang bisa dilihat
        $baseQuery->where(function ($q) use ($user) {
            $q->where(function ($allQ) {
                $allQ->whereNull('target_type')
                     ->orWhere('target_type', 'all');
            })->orWhere(function ($sub) use ($user) {
                $sub->where('target_type', 'custom')
                    ->whereHas('assignedUsers', function ($u) use ($user) {
                        $u->where('users.id', $user->id);
                    });
            });
        });

        if ($acceptedRegistration && $startDate) {
            $baseQuery->whereDate('created_at', '>=', $startDate);
            if ($endDate) {
                $baseQuery->whereDate('created_at', '<=', $endDate);
            }
        } else {
            // Jika belum accepted / belum ada start_date, jangan tampilkan materi/tugas masa lalu
            $baseQuery->whereRaw('1 = 0');
        }

        $filterType = $request->query('type', 'all');
        if (! in_array($filterType, ['all', 'division', 'personal'], true)) {
            $filterType = 'all';
        }

        $countAll = (clone $baseQuery)->count();
        $countDivision = (clone $baseQuery)->where(function ($q) {
            $q->whereNull('target_type')->orWhere('target_type', 'all');
        })->count();
        $countPersonal = (clone $baseQuery)->where('target_type', 'custom')->count();

        $query = clone $baseQuery;
        if ($filterType === 'division') {
            $query->where(function ($q) {
                $q->whereNull('target_type')->orWhere('target_type', 'all');
            });
        } elseif ($filterType === 'personal') {
            $query->where('target_type', 'custom');
        }

        $materials = $query->latest()->paginate(10)->withQueryString();

        return view('participant.materials.index', compact('materials', 'filterType', 'countAll', 'countDivision', 'countPersonal'));
    }

    public function show(Material $material)
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);

        if (empty($intern->division_id)) {
            return redirect()->route('participant.materials.index')
                ->with('error', 'Anda belum terdaftar pada divisi manapun. Silakan hubungi admin untuk verifikasi pendaftaran.');
        }

        $pembimbing = User::find($material->pembimbing_id);
        if ($pembimbing === null) {
            abort(403);
        }

        if (!empty($pembimbing->division_id) && $pembimbing->division_id !== $intern->division_id) {
            abort(403);
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('materials', 'division_id') && !empty($material->division_id)) {
            if ($material->division_id !== $intern->division_id) {
                abort(403);
            }
        }

        if ($material->target_type === 'custom' && ! $material->isAssignedTo($intern->id)) {
            abort(403, 'Anda tidak ditugaskan untuk materi / tugas mandiri ini.');
        }

        // Keamanan Penugasan Mandiri: Jika bertipe custom, pastikan peserta terdaftar dalam penugasan
        if ($material->target_type === 'custom' && ! $material->isAssignedTo($intern->id)) {
            abort(403, 'Anda tidak ditugaskan untuk materi / tugas mandiri ini.');
        }

        $acceptedRegistration = $this->getActiveAcceptedRegistration($intern);
        $startDate = $acceptedRegistration?->periode_mulai;
        $endDate   = $acceptedRegistration?->periode_selesai;

        if ($acceptedRegistration && $startDate && $material->created_at) {
            $materialDate = $material->created_at->toDateString();
            if ($materialDate < \Carbon\Carbon::parse($startDate)->toDateString()) {
                abort(403, 'Materi/tugas ini diterbitkan sebelum periode magang Anda dimulai.');
            }
            if ($endDate && $materialDate > \Carbon\Carbon::parse($endDate)->toDateString()) {
                abort(403, 'Materi/tugas ini diterbitkan setelah periode magang Anda berakhir.');
            }
        }

        $submission = ModuleSubmission::where('material_id', $material->id)
            ->where('user_id', auth()->id())
            ->first();

        $material->load(['submissions' => function ($q) {
            $q->where('user_id', auth()->id());
        }]);

        $videoId = null;
        if ($material->youtube_url) {
            preg_match(
                '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
                $material->youtube_url,
                $match
            );
            if (isset($match[1])) {
                $videoId = $match[1];
            }
        }

        return view('participant.materials.show', compact('material', 'submission', 'videoId'));
    }

    public function submitTask(Request $request, Material $material)
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);

        if (empty($intern->division_id)) {
            return redirect()->route('participant.materials.index')
                ->with('error', 'Anda belum terdaftar pada divisi manapun. Silakan hubungi admin untuk verifikasi pendaftaran.');
        }

        $pembimbing = User::find($material->pembimbing_id);
        if ($pembimbing === null) {
            abort(403);
        }

        if (!empty($pembimbing->division_id) && $pembimbing->division_id !== $intern->division_id) {
            abort(403);
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('materials', 'division_id') && !empty($material->division_id)) {
            if ($material->division_id !== $intern->division_id) {
                abort(403);
            }
        }

        if ($material->target_type === 'custom' && ! $material->isAssignedTo($intern->id)) {
            abort(403, 'Anda tidak ditugaskan untuk materi / tugas mandiri ini.');
        }

        $acceptedRegistration = $this->getActiveAcceptedRegistration($intern);
        $startDate = $acceptedRegistration?->periode_mulai;
        $endDate   = $acceptedRegistration?->periode_selesai;

        if ($acceptedRegistration && $startDate && $material->created_at) {
            $materialDate = $material->created_at->toDateString();
            if ($materialDate < \Carbon\Carbon::parse($startDate)->toDateString()) {
                abort(403, 'Tugas ini diterbitkan sebelum periode magang Anda dimulai.');
            }
            if ($endDate && $materialDate > \Carbon\Carbon::parse($endDate)->toDateString()) {
                abort(403, 'Tugas ini diterbitkan setelah periode magang Anda berakhir.');
            }
        }

        if (!$material->is_task) {
            return back()->with('error', 'Materi ini bukan merupakan tugas yang perlu dikumpulkan.');
        }

        $submissionType = $material->submission_type;
        $validationRules = ['submission_text' => 'nullable|string'];
        $submissionData = [
            'submission_text' => $request->input('submission_text'),
            'status' => 'submitted',
        ];

        $existingSubmission = ModuleSubmission::where('material_id', $material->id)
            ->where('user_id', auth()->id())
            ->first();

        $defaultMimes = 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,txt,odt,zip,rar,7z,tar,gz,jpg,jpeg,png,webp,gif,svg,mp4,mkv,avi,mov,webm,mp3,wav';

        $hasFile = $request->hasFile('files') || $request->hasFile('file');
        $hasLink = filled($request->input('submission_link'));

        if ($submissionType === null || $submissionType->isFileUpload()) {
            $mimes = ($submissionType && !$submissionType->isLink() && $submissionType->acceptedMimes()) 
                ? $submissionType->acceptedMimes() 
                : $defaultMimes;

            $rules = [
                'submission_text' => 'nullable|string',
                'submission_link' => 'nullable|url|max:500',
            ];

            if ($submissionType === null) {
                if (!$hasFile && !$hasLink) {
                    return back()->withErrors([
                        'file' => 'Harap upload file tugas atau masukkan link pengumpulan.',
                        'submission_link' => 'Harap upload file tugas atau masukkan link pengumpulan.',
                    ])->withInput();
                }
            } else {
                if (!$hasFile) {
                    return back()->withErrors([
                        'file' => 'Harap unggah minimal satu file tugas.',
                    ])->withInput();
                }
            }

            if ($hasFile) {
                if ($request->hasFile('files')) {
                    $rules['files'] = 'required|array|min:1';
                    $rules['files.*'] = 'file|mimes:' . $mimes . '|max:102400';
                } else {
                    $rules['file'] = 'required|file|mimes:' . $mimes . '|max:102400';
                }
            }

            $validated = $request->validate($rules);

            if ($hasFile) {
                // Hapus file lama jika ada
                if ($existingSubmission) {
                    if (is_array($existingSubmission->files)) {
                        foreach ($existingSubmission->files as $oldF) {
                            if (!empty($oldF['path'])) {
                                Storage::disk('public')->delete($oldF['path']);
                            }
                        }
                    }
                    if (!empty($existingSubmission->file_path)) {
                        Storage::disk('public')->delete($existingSubmission->file_path);
                    }
                }

                $uploadedFiles = [];
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $uploaded) {
                        if ($uploaded->isValid()) {
                            $path = $uploaded->store('submissions', 'public');
                            $uploadedFiles[] = [
                                'name' => $uploaded->getClientOriginalName(),
                                'path' => $path,
                                'size' => $uploaded->getSize(),
                                'mime' => $uploaded->getClientMimeType(),
                            ];
                        }
                    }
                } elseif ($request->hasFile('file')) {
                    $uploaded = $request->file('file');
                    $path = $uploaded->store('submissions', 'public');
                    $uploadedFiles[] = [
                        'name' => $uploaded->getClientOriginalName(),
                        'path' => $path,
                        'size' => $uploaded->getSize(),
                        'mime' => $uploaded->getClientMimeType(),
                    ];
                }

                $submissionData['files'] = !empty($uploadedFiles) ? $uploadedFiles : null;
                $submissionData['file_path'] = !empty($uploadedFiles) ? $uploadedFiles[0]['path'] : null;
            }

            if ($hasLink) {
                $submissionData['submission_link'] = $request->input('submission_link');
            }
        } elseif ($submissionType->isLink()) {
            $validated = $request->validate([
                'submission_link' => 'required|url|max:500',
                'submission_text' => 'nullable|string',
            ]);
            $submissionData['submission_link'] = $validated['submission_link'];
        }

        ModuleSubmission::updateOrCreate(
            ['material_id' => $material->id, 'user_id' => auth()->id()],
            $submissionData
        );

        return redirect()->route('participant.materials.show', $material->id)
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function markVideoWatched(Material $material): \Illuminate\Http\JsonResponse
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);

        if (empty($intern->division_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum terdaftar pada divisi manapun.',
            ], 403);
        }

        if (empty($material->youtube_url)) {
            return response()->json([
                'success' => false,
                'message' => 'Materi ini tidak memiliki video YouTube untuk dilacak.',
            ], 400);
        }

        $pembimbing = User::find($material->pembimbing_id);
        if ($pembimbing === null) {
            return response()->json([
                'success' => false,
                'message' => 'Pembimbing tidak valid.',
            ], 403);
        }

        if (!empty($pembimbing->division_id) && $pembimbing->division_id !== $intern->division_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin mengakses materi ini.',
            ], 403);
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('materials', 'division_id') && !empty($material->division_id)) {
            if ($material->division_id !== $intern->division_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin mengakses materi ini.',
                ], 403);
            }
        }

        if ($material->target_type === 'custom' && ! $material->isAssignedTo($intern->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak ditugaskan untuk materi ini.',
            ], 403);
        }

        $submission = ModuleSubmission::updateOrCreate(
            [
                'material_id' => $material->id,
                'user_id' => $intern->id,
            ],
            [
                'is_video_watched' => true,
                'video_watched_at' => now(),
                'status' => 'submitted',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status penontonan video disimpan.',
            'is_video_watched' => (bool) $submission->is_video_watched,
            'video_watched_at' => $submission->video_watched_at?->toIso8601String(),
        ], 200);
    }

    public function completeVideo(Request $request, Material $material): \Illuminate\Http\JsonResponse
    {
        $intern = Auth::user();
        $intern = $this->ensureDivisionIdResolved($intern);

        if (empty($intern->division_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum terdaftar pada divisi manapun.',
            ], 403);
        }

        if (empty($material->youtube_url)) {
            return response()->json([
                'success' => false,
                'message' => 'Materi ini tidak memiliki video YouTube untuk dilacak.',
            ], 400);
        }

        $pembimbing = User::find($material->pembimbing_id);
        if ($pembimbing === null) {
            return response()->json([
                'success' => false,
                'message' => 'Pembimbing tidak valid.',
            ], 403);
        }

        if (!empty($pembimbing->division_id) && $pembimbing->division_id !== $intern->division_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin mengakses materi ini.',
            ], 403);
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('materials', 'division_id') && !empty($material->division_id)) {
            if ($material->division_id !== $intern->division_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin mengakses materi ini.',
                ], 403);
            }
        }

        if ($material->target_type === 'custom' && ! $material->isAssignedTo($intern->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak ditugaskan untuk materi ini.',
            ], 403);
        }

        $completed = (bool) ($request->json('completed', false) ?: $request->input('completed', false));
        if (!$completed) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter completed tidak valid.',
            ], 422);
        }

        $submission = ModuleSubmission::updateOrCreate(
            [
                'material_id' => $material->id,
                'user_id' => $intern->id,
            ],
            [
                'is_video_watched' => true,
                'video_watched_at' => now(),
                'status' => 'submitted',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status penyelesaian video disimpan.',
            'is_video_watched' => (bool) $submission->is_video_watched,
            'video_watched_at' => $submission->video_watched_at?->toIso8601String(),
        ], 200);
    }

    protected function ensureDivisionIdResolved(User $intern): User
    {
        if (!empty($intern->division_id)) {
            return $intern;
        }

        $acceptedRegistration = $intern->registrations()
            ->where('status', \App\Enums\RegistrationStatus::Accepted)
            ->where('is_terminated', false)
            ->latest()
            ->first();

        if ($acceptedRegistration === null) {
            $acceptedRegistration = $intern->registrations()
                ->where('status', \App\Enums\RegistrationStatus::Accepted)
                ->latest()
                ->first();
        }

        if ($acceptedRegistration === null) {
            return $intern;
        }

        $divisionId = $this->registrationService->resolveDivisionIdFromRegistration($acceptedRegistration);
        if ($divisionId === null) {
            return $intern;
        }

        $intern->update([
            'division_id' => $divisionId,
        ]);

        return $intern->fresh();
    }

    protected function getActiveAcceptedRegistration(User $user): ?\App\Models\Registration
    {
        return $user->registrations()
            ->where(function ($q) {
                $q->where('status', \App\Enums\RegistrationStatus::Accepted)
                  ->orWhere('status', 'accepted');
            })
            ->where('is_terminated', false)
            ->latest('id')
            ->first()
            ?? $user->registrations()
                ->where(function ($q) {
                    $q->where('status', \App\Enums\RegistrationStatus::Accepted)
                      ->orWhere('status', 'accepted');
                })
                ->latest('id')
                ->first();
    }
}
