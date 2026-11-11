<?php

namespace App\Http\Controllers\Pembimbing;

use App\Enums\RegistrationStatus;
use App\Enums\SubmissionType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'divisi');
        if (! in_array($tab, ['divisi', 'mandiri'], true)) {
            $tab = 'divisi';
        }

        $perPageRaw = trim((string) $request->input('perPage', $request->input('per_page', '10')));
        $allowedPerPage = ['5', '10', '15', '20', '25', '50', '100', 'all'];
        if (! in_array($perPageRaw, $allowedPerPage, true)) {
            $perPageRaw = '10';
        }
        $isAllPerPage = $perPageRaw === 'all';
        $perPage = $isAllPerPage ? 10000 : (int) $perPageRaw;

        $user = Auth::user();
        $today = now()->toDateString();

        // 1. Data Penugasan Divisi (Bersama)
        $divisionMaterialsQuery = Material::where('pembimbing_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('target_type')
                  ->orWhere('target_type', 'all');
            });

        $divisionMaterialsCount = (clone $divisionMaterialsQuery)->count();

        $materials = (clone $divisionMaterialsQuery)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('year'), function ($q) use ($request) {
                $q->whereYear('created_at', $request->input('year'));
            })
            ->when($request->filled('month'), function ($q) use ($request) {
                $q->whereMonth('created_at', $request->input('month'));
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // 2. Data Penugasan Mandiri (Direktori Peserta Aktif yang Memiliki Penugasan Mandiri)
        $activeInternsBaseQuery = Registration::where('status', RegistrationStatus::Accepted->value)
            ->where('is_terminated', false)
            ->where(function ($q) use ($today) {
                $q->whereNull('periode_selesai')
                  ->orWhereDate('periode_selesai', '>=', $today);
            })
            ->whereHas('user', function (Builder $uq) use ($user) {
                $uq->where('division_id', $user->division_id);
            });

        if (! empty($user->opd_id)) {
            $activeInternsBaseQuery->where(function (Builder $q) use ($user): void {
                $q->where('opd_id', $user->opd_id)
                    ->orWhereHas('position', fn (Builder $pq) => $pq->where('opd_id', $user->opd_id))
                    ->orWhereHas('user', fn (Builder $uq) => $uq->where('opd_id', $user->opd_id));
            });
        }

        $activeInternsTotalCount = (clone $activeInternsBaseQuery)->count();

        $internSearch = trim((string) $request->input('intern_search', ''));
        $activeInternsQuery = clone $activeInternsBaseQuery;

        if ($internSearch !== '') {
            $activeInternsQuery->where(function ($q) use ($internSearch): void {
                $q->where('nomor_pendaftaran', 'like', "%{$internSearch}%")
                    ->orWhereHas('user', function ($uq) use ($internSearch): void {
                        $uq->where('name', 'like', "%{$internSearch}%")
                            ->orWhere('email', 'like', "%{$internSearch}%")
                            ->orWhereHas('profile', function ($pq) use ($internSearch): void {
                                $pq->where('institusi', 'like', "%{$internSearch}%")
                                    ->orWhere('jurusan', 'like', "%{$internSearch}%");
                            });
                    })
                    ->orWhereHas('position', function ($pq) use ($internSearch): void {
                        $pq->where('nama_posisi', 'like', "%{$internSearch}%");
                    });
            });
        }

        $activeInternRegistrations = $activeInternsQuery
            ->with(['user.profile', 'position'])
            ->get()
            ->sortBy(function ($reg) {
                return strtolower($reg->user->name ?? '');
            })
            ->values();

        // Hitung metrik tugas mandiri untuk setiap peserta aktif
        $activeInterns = $activeInternRegistrations->map(function ($reg) use ($user, $today) {
            $internUser = $reg->user;
            $personalTasksCount = 0;
            $pendingReviewsCount = 0;
            $completedTasksCount = 0;

            if ($internUser) {
                $personalMaterials = Material::where('pembimbing_id', $user->id)
                    ->where('target_type', 'custom')
                    ->whereHas('assignedUsers', function ($q) use ($internUser) {
                        $q->where('users.id', $internUser->id);
                    })
                    ->with(['submissions' => function ($sq) use ($internUser) {
                        $sq->where('user_id', $internUser->id);
                    }])
                    ->get();

                $personalTasksCount = $personalMaterials->count();
                foreach ($personalMaterials as $pm) {
                    $sub = $pm->submissions->first();
                    if ($sub) {
                        if ($sub->status === 'approved' || $sub->status === 'completed' || $sub->score !== null) {
                            $completedTasksCount++;
                        } else {
                            $pendingReviewsCount++;
                        }
                    }
                }
            }

            $remainingDays = null;
            if ($reg->periode_selesai) {
                $selesai = \Carbon\Carbon::parse($reg->periode_selesai)->startOfDay();
                $now = \Carbon\Carbon::parse($today)->startOfDay();
                $remainingDays = (int) $now->diffInDays($selesai, false);
            }

            return (object) [
                'registration' => $reg,
                'user' => $internUser,
                'personal_tasks_count' => $personalTasksCount,
                'pending_reviews_count' => $pendingReviewsCount,
                'completed_tasks_count' => $completedTasksCount,
                'remaining_days' => $remainingDays,
            ];
        });

        // Ambil tahun unik yang ada pada data materi milik pembimbing yang sedang login
        $dbYears = Material::where('pembimbing_id', $user->id)
            ->whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn($dt) => (int) \Illuminate\Support\Carbon::parse($dt)->format('Y'))
            ->unique()
            ->values();

        $currentYear = (int) date('Y');
        $availableYears = $dbYears->merge([$currentYear, $currentYear + 1])
            ->unique()
            ->sortDesc()
            ->values();

        return view('pembimbing.materials.index', compact(
            'materials',
            'availableYears',
            'tab',
            'divisionMaterialsCount',
            'activeInternsTotalCount',
            'activeInterns',
            'internSearch'
        ));
    }

    public function personal(User $intern)
    {
        $mentor = Auth::user();

        // Pastikan peserta berada dalam divisi bimbingan mentor
        if ($intern->division_id !== $mentor->division_id) {
            abort(403, 'Peserta magang tidak berada dalam divisi bimbingan Anda.');
        }

        if (! empty($mentor->opd_id) && ! empty($intern->opd_id) && $mentor->opd_id !== $intern->opd_id) {
            abort(403, 'Peserta magang tidak berada dalam OPD bimbingan Anda.');
        }

        $registration = Registration::where('user_id', $intern->id)
            ->where('status', RegistrationStatus::Accepted->value)
            ->with(['position', 'user.profile'])
            ->latest()
            ->first();

        // Ambil riwayat penugasan mandiri khusus untuk peserta ini
        $personalMaterials = Material::where('pembimbing_id', $mentor->id)
            ->where('target_type', 'custom')
            ->whereHas('assignedUsers', function ($q) use ($intern) {
                $q->where('users.id', $intern->id);
            })
            ->with(['submissions' => function ($q) use ($intern) {
                $q->where('user_id', $intern->id);
            }])
            ->latest()
            ->get();

        $totalTasks = $personalMaterials->count();
        $submittedCount = 0;
        $pendingReviewCount = 0;
        $completedCount = 0;

        foreach ($personalMaterials as $m) {
            $sub = $m->submissions->first();
            if ($sub) {
                $submittedCount++;
                if ($sub->status === 'approved' || $sub->status === 'completed' || $sub->score !== null) {
                    $completedCount++;
                } else {
                    $pendingReviewCount++;
                }
            }
        }

        $unsubmittedCount = max(0, $totalTasks - $submittedCount);

        $stats = [
            'total' => $totalTasks,
            'submitted' => $submittedCount,
            'pending_review' => $pendingReviewCount,
            'completed' => $completedCount,
            'unsubmitted' => $unsubmittedCount,
        ];

        return view('pembimbing.materials.personal', compact('intern', 'registration', 'personalMaterials', 'stats'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $activeInterns = User::where('role', UserRole::Peserta)
            ->where('division_id', $user->division_id)
            ->when(! empty($user->opd_id), fn($q) => $q->where('opd_id', $user->opd_id))
            ->whereHas('registrations', function ($rq) use ($today) {
                $rq->where('status', RegistrationStatus::Accepted->value)
                    ->where('is_terminated', false)
                    ->where(function ($q) use ($today) {
                        $q->whereNull('periode_selesai')
                            ->orWhereDate('periode_selesai', '>=', $today);
                    });
            })
            ->with(['profile', 'position'])
            ->orderBy('name', 'asc')
            ->get();

        $targetType = $request->query('target', 'all');
        $lockedUserId = $request->query('user_id', old('specific_user_id'));
        $specificUser = null;
        if (! empty($lockedUserId)) {
            $specificUser = $activeInterns->firstWhere('id', (int) $lockedUserId);
            if ($specificUser) {
                $targetType = 'custom';
            }
        }
        $preselectedUserId = $request->query('user_id');
        $submissionTypes = SubmissionType::cases();

        return view('pembimbing.materials.create', compact('submissionTypes', 'activeInterns', 'targetType', 'preselectedUserId', 'specificUser'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_task' => $request->has('is_task') ? 1 : 0,
            'target_type' => $request->input('target_type', 'all'),
        ]);
        $request->merge(['pembimbing_id' => Auth::id()]);

        $isTask = $request->has('is_task');
        $targetType = $request->input('target_type', 'all');

        $safeMimes = 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,txt,rtf,odt,zip,rar,7z,tar,gz,jpg,jpeg,png,webp,gif,svg,mp4,mkv,avi,mov,webm,mp3,wav';

        $validated = $request->validate([
            'pembimbing_id' => 'required|exists:users,id',
            'target_type' => 'required|in:all,custom',
            'assigned_users' => [
                'nullable',
                'array',
                Rule::requiredIf($targetType === 'custom'),
            ],
            'assigned_users.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_materi' => 'nullable|file|mimes:' . $safeMimes . '|max:102400',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:' . $safeMimes . '|max:102400',
            'youtube_url' => 'nullable|url|max:255',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|date',
            'submission_type' => [
                'nullable',
                Rule::when($isTask, [Rule::enum(SubmissionType::class)]),
            ],
        ], [
            'assigned_users.required' => 'Silakan pilih minimal satu peserta untuk penugasan mandiri.',
        ]);

        $materialData = $validated;
        unset($materialData['files'], $materialData['assigned_users']);
        $materialData['is_task'] = $isTask ? 1 : 0;
        $materialData['deadline'] = $request->input('deadline');
        $materialData['submission_type'] = $isTask
            ? $request->input('submission_type') ?: null
            : null;

        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if ($file->isValid()) {
                    $origName = $file->getClientOriginalName();
                    $path = $file->store('materials/documents', 'public');
                    $uploadedFiles[] = [
                        'name' => $origName,
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getClientMimeType(),
                    ];
                }
            }
        }

        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $origName = $file->getClientOriginalName();
            $path = $file->store('materials/documents', 'public');
            $uploadedFiles[] = [
                'name' => $origName,
                'path' => $path,
                'size' => $file->getSize(),
                'mime' => $file->getClientMimeType(),
            ];
            $materialData['file_materi'] = $path;
        }

        if (!empty($uploadedFiles)) {
            $materialData['files'] = $uploadedFiles;
            if (empty($materialData['file_materi'])) {
                $materialData['file_materi'] = $uploadedFiles[0]['path'];
            }
        }

        $material = Material::create($materialData);

        if ($targetType === 'custom' && !empty($request->input('assigned_users'))) {
            $assignedIds = (array) $request->input('assigned_users');
            $material->assignedUsers()->sync($assignedIds);

            if (count($assignedIds) === 1) {
                return redirect()->route('pembimbing.materials.personal', $assignedIds[0])
                    ->with('success', 'Penugasan mandiri berhasil dibuat dan dikirimkan ke peserta.');
            }

            return redirect()->route('pembimbing.materials.index', ['tab' => 'mandiri'])
                ->with('success', 'Penugasan mandiri berhasil dibuat untuk ' . count($assignedIds) . ' peserta.');
        }

        return redirect()->route('pembimbing.materials.index')
            ->with('success', 'Materi penugasan divisi berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $today = now()->toDateString();

        $activeInterns = User::where('role', UserRole::Peserta)
            ->where('division_id', $user->division_id)
            ->when(! empty($user->opd_id), fn($q) => $q->where('opd_id', $user->opd_id))
            ->whereHas('registrations', function ($rq) use ($today) {
                $rq->where('status', RegistrationStatus::Accepted->value)
                    ->where('is_terminated', false)
                    ->where(function ($q) use ($today) {
                        $q->whereNull('periode_selesai')
                            ->orWhereDate('periode_selesai', '>=', $today);
                    });
            })
            ->with(['profile', 'position'])
            ->orderBy('name', 'asc')
            ->get();

        $assignedUserIds = $material->assignedUsers()->pluck('users.id')->toArray();
        $submissionTypes = SubmissionType::cases();

        return view('pembimbing.materials.edit', compact('material', 'submissionTypes', 'activeInterns', 'assignedUserIds'));
    }

    public function update(Request $request, Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $isTask = $request->has('is_task');
        $targetType = $request->input('target_type', 'all');

        $request->merge([
            'is_task' => $isTask ? 1 : 0,
            'target_type' => $targetType,
        ]);

        $safeMimes = 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,txt,rtf,odt,zip,rar,7z,tar,gz,jpg,jpeg,png,webp,gif,svg,mp4,mkv,avi,mov,webm,mp3,wav';

        $validated = $request->validate([
            'target_type' => 'required|in:all,custom',
            'assigned_users' => [
                'nullable',
                'array',
                Rule::requiredIf($targetType === 'custom'),
            ],
            'assigned_users.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_materi' => 'nullable|file|mimes:' . $safeMimes . '|max:102400',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:' . $safeMimes . '|max:102400',
            'youtube_url' => 'nullable|url|max:255',
            'is_task' => 'nullable|boolean',
            'deadline' => 'nullable|date',
            'submission_type' => [
                'nullable',
                Rule::when($isTask, [Rule::enum(SubmissionType::class)]),
            ],
        ], [
            'assigned_users.required' => 'Silakan pilih minimal satu peserta untuk penugasan mandiri.',
        ]);

        $materialData = $validated;
        unset($materialData['files'], $materialData['assigned_users']);
        $materialData['is_task'] = $isTask ? 1 : 0;
        $materialData['deadline'] = $request->input('deadline');
        $materialData['submission_type'] = $isTask
            ? $request->input('submission_type') ?: null
            : null;

        $existingFiles = is_array($material->files) ? $material->files : [];
        if (empty($existingFiles) && !empty($material->file_materi)) {
            $existingFiles[] = [
                'name' => basename($material->file_materi),
                'path' => $material->file_materi,
                'size' => null,
                'mime' => null,
            ];
        }

        // Hapus file yang dicentang hapus
        $removeFilePaths = (array) $request->input('remove_files', []);
        if ($request->boolean('remove_file_materi')) {
            $removeFilePaths[] = $material->file_materi;
        }

        if (!empty($removeFilePaths)) {
            $remainingFiles = [];
            foreach ($existingFiles as $ef) {
                if (in_array($ef['path'], $removeFilePaths, true)) {
                    Storage::disk('public')->delete($ef['path']);
                } else {
                    $remainingFiles[] = $ef;
                }
            }
            $existingFiles = $remainingFiles;
        }

        // Unggah file baru (multiple)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if ($file->isValid()) {
                    $origName = $file->getClientOriginalName();
                    $path = $file->store('materials/documents', 'public');
                    $existingFiles[] = [
                        'name' => $origName,
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getClientMimeType(),
                    ];
                }
            }
        }

        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $origName = $file->getClientOriginalName();
            $path = $file->store('materials/documents', 'public');
            $existingFiles[] = [
                'name' => $origName,
                'path' => $path,
                'size' => $file->getSize(),
                'mime' => $file->getClientMimeType(),
            ];
        }

        $materialData['files'] = !empty($existingFiles) ? array_values($existingFiles) : null;
        $materialData['file_materi'] = !empty($existingFiles) ? $existingFiles[0]['path'] : null;

        $material->update($materialData);

        if ($targetType === 'custom' && !empty($request->input('assigned_users'))) {
            $assignedIds = (array) $request->input('assigned_users');
            $material->assignedUsers()->sync($assignedIds);

            if (count($assignedIds) === 1) {
                return redirect()->route('pembimbing.materials.personal', $assignedIds[0])
                    ->with('success', 'Penugasan mandiri berhasil diperbarui.');
            }

            return redirect()->route('pembimbing.materials.index', ['tab' => 'mandiri'])
                ->with('success', 'Penugasan mandiri berhasil diperbarui.');
        } else {
            $material->assignedUsers()->detach();
        }

        return redirect()->route('pembimbing.materials.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $wasCustom = $material->target_type === 'custom';
        $assignedUserIds = $material->assignedUsers()->pluck('users.id')->toArray();

        if (is_array($material->files)) {
            foreach ($material->files as $f) {
                if (!empty($f['path'])) {
                    Storage::disk('public')->delete($f['path']);
                }
            }
        }

        if (!empty($material->file_materi)) {
            Storage::disk('public')->delete($material->file_materi);
        }

        $material->assignedUsers()->detach();
        $material->delete();

        if ($wasCustom && count($assignedUserIds) === 1) {
            return redirect()->route('pembimbing.materials.personal', $assignedUserIds[0])
                ->with('success', 'Penugasan mandiri berhasil dihapus.');
        }

        if ($wasCustom) {
            return redirect()->route('pembimbing.materials.index', ['tab' => 'mandiri'])
                ->with('success', 'Materi berhasil dihapus.');
        }

        return redirect()->route('pembimbing.materials.index')
            ->with('success', 'Materi berhasil dihapus.');
    }

    public function submissions(Material $material)
    {
        if ($material->pembimbing_id !== Auth::id()) {
            abort(403);
        }

        $material->load(['assignedUsers.profile', 'submissions.user.profile']);

        return view('pembimbing.materials.submissions', compact('material'));
    }
}

