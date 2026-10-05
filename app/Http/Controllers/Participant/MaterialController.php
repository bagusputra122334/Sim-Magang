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

    public function index()
    {
        $user = auth()->user();
        $user = $this->ensureDivisionIdResolved($user);

        if (empty($user->division_id)) {
            return redirect()->route('participant.dashboard')
                ->with('error', 'Anda belum terdaftar pada divisi manapun. Silakan hubungi admin untuk verifikasi pendaftaran.');
        }

        $acceptedRegistration = $user->registrations()
            ->where('status', \App\Enums\RegistrationStatus::Accepted->value ?? \App\Enums\RegistrationStatus::Accepted)
            ->latest()
            ->first();
        
        $periodeMulai = $acceptedRegistration && $acceptedRegistration->periode_mulai
            ? \Carbon\Carbon::parse($acceptedRegistration->periode_mulai)->startOfDay()
            : \Carbon\Carbon::now()->addYears(100);

        $materials = Material::with(['submissions' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->where(function ($q) use ($user) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('materials', 'division_id')) {
                $q->where('division_id', $user->division_id);
            }

            $q->orWhereHas('pembimbing', function ($sub) use ($user) {
                $sub->where('division_id', $user->division_id);
            });
        })
            ->whereDate('created_at', '>=', $periodeMulai)
            ->latest()
            ->paginate(10);

        return view('participant.materials.index', compact('materials'));
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

        $defaultMimes = 'pdf,doc,docx,zip,rar,jpg,png,jpeg';

        if ($submissionType === null) {
            $hasFile = $request->hasFile('file');
            $hasLink = filled($request->input('submission_link'));

            if (!$hasFile && !$hasLink) {
                return back()->withErrors([
                    'file' => 'Harap upload file atau masukkan link pengumpulan.',
                    'submission_link' => 'Harap upload file atau masukkan link pengumpulan.',
                ])->withInput();
            }

            if ($hasFile) {
                $validated = $request->validate([
                    'file' => 'required|file|mimes:' . $defaultMimes . '|max:10240',
                    'submission_link' => 'nullable|url|max:500',
                    'submission_text' => 'nullable|string',
                ]);
                if ($existingSubmission && !empty($existingSubmission->file_path)) {
                    Storage::disk('public')->delete($existingSubmission->file_path);
                }
                $path = $request->file('file')->store('submissions', 'public');
                $submissionData['file_path'] = $path;
                if (filled($request->input('submission_link'))) {
                    $submissionData['submission_link'] = $request->input('submission_link');
                }
            } else {
                $validated = $request->validate([
                    'submission_link' => 'required|url|max:500',
                    'submission_text' => 'nullable|string',
                ]);
                $submissionData['submission_link'] = $validated['submission_link'];
            }
        } elseif ($submissionType->isLink()) {
            $validated = $request->validate([
                'submission_link' => 'required|url|max:500',
                'submission_text' => 'nullable|string',
            ]);
            $submissionData['submission_link'] = $validated['submission_link'];
        } else {
            $mimes = $submissionType->acceptedMimes();
            $validated = $request->validate([
                'file' => 'required|file|mimes:' . $mimes . '|max:10240',
                'submission_text' => 'nullable|string',
            ]);
            if ($existingSubmission && !empty($existingSubmission->file_path)) {
                Storage::disk('public')->delete($existingSubmission->file_path);
            }
            $path = $request->file('file')->store('submissions', 'public');
            $submissionData['file_path'] = $path;
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
}
