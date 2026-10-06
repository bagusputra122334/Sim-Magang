<?php

namespace App\Http\Controllers\Participant;

use App\Models\User;
use App\Models\Material;
use App\Models\Task;
use App\Enums\UserRole;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends ParticipantController
{
    public function __construct(protected RegistrationService $registrationService) {}

    public function index(): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user = User::query()
            ->with([
                'profile',
                'registrations' => function ($query): void {
                    $query->latest('tanggal_submit')
                        ->latest('id')
                        ->with(['position:id,nama_posisi,kuota']);
                },
            ])
            ->findOrFail($user->id);

        $profile           = $user->profile;
        $hasProfile        = $profile !== null;

        if (! $hasProfile) {
            return redirect()->route('participant.onboarding.welcome')
                ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $latestRegistration = $user->registrations->first();
        $totalRegistrations = $user->registrations->count();

        $documentInfo = $this->buildDocumentInfo($latestRegistration);

        $timeline = $this->buildStatusTimeline($latestRegistration);

        $assignmentDashboardData = $this->buildAssignmentDashboardData($user);

        return view($this->viewPrefix.'.dashboard', array_merge(
            compact(
                'user',
                'profile',
                'hasProfile',
                'latestRegistration',
                'totalRegistrations',
                'documentInfo',
                'timeline',
            ),
            $assignmentDashboardData,
        ));
    }

    public function resolvePembimbingIdsForDashboard(User $user): array
    {
        $user = $this->ensureDivisionIdResolved($user);

        $pembimbingIds = [];

        if (! empty($user->division_id)) {
            $ids = User::where('role', UserRole::Pembimbing)
                ->where('division_id', $user->division_id)
                ->pluck('id')
                ->toArray();
            $pembimbingIds = array_merge($pembimbingIds, $ids);
        }

        $acceptedRegistration = $user->registrations
            ->first(fn ($r) => is_object($r->status) && method_exists($r->status, 'isAccepted') && $r->status->isAccepted());

        if ($acceptedRegistration === null) {
            $acceptedRegistration = $user->registrations
                ->first(fn ($r) => (is_string($r->status) && strtolower($r->status) === 'accepted'));
        }

        if ($acceptedRegistration !== null) {
            $acceptedRegistration->loadMissing(['position']);

            $mentorName = null;
            $mentorNip = null;

            if (Schema::hasColumn('registrations', 'mentor_name')) {
                $rawMentor = $acceptedRegistration->getAttribute('mentor_name');
                if (is_string($rawMentor) && trim($rawMentor) !== '') {
                    $mentorName = trim($rawMentor);
                }
            }
            if (Schema::hasColumn('registrations', 'mentor_nip')) {
                $rawNip = $acceptedRegistration->getAttribute('mentor_nip');
                if (is_string($rawNip) && trim($rawNip) !== '') {
                    $mentorNip = trim($rawNip);
                }
            }

            $position = $acceptedRegistration->position;
            if ($position !== null) {
                $posMentor = $position->getAttribute('mentor_name');
                if ($mentorName === null && is_string($posMentor) && trim($posMentor) !== '') {
                    $mentorName = trim($posMentor);
                }
                $posNip = $position->getAttribute('mentor_nip');
                if ($mentorNip === null && is_string($posNip) && trim($posNip) !== '') {
                    $mentorNip = trim($posNip);
                }

                $posDivisionId = null;
                if (Schema::hasColumn('positions', 'division_id')) {
                    $rawDivId = $position->getAttribute('division_id');
                    if ($rawDivId !== null) {
                        $posDivisionId = (int) $rawDivId;
                    }
                }

                if ($posDivisionId !== null) {
                    $extraDivIds = User::where('role', UserRole::Pembimbing)
                        ->where('division_id', $posDivisionId)
                        ->pluck('id')
                        ->toArray();
                    $pembimbingIds = array_merge($pembimbingIds, $extraDivIds);
                }

                if ($posDivisionId === null) {
                    $posName = trim((string) $position->getAttribute('nama_posisi'));
                    if ($posName !== '' && Schema::hasTable('divisions') && Schema::hasColumn('divisions', 'nama_divisi')) {
                        $matchedDiv = \Illuminate\Support\Facades\DB::table('divisions')
                            ->where('nama_divisi', $posName)
                            ->orWhere('nama_divisi', 'LIKE', '%'.$posName.'%')
                            ->first();
                        if ($matchedDiv !== null && ! empty($matchedDiv->id)) {
                            $derivedDivId = (int) $matchedDiv->id;
                            $extraByNameIds = User::where('role', UserRole::Pembimbing)
                                ->where('division_id', $derivedDivId)
                                ->pluck('id')
                                ->toArray();
                            $pembimbingIds = array_merge($pembimbingIds, $extraByNameIds);
                        }
                    }
                }
            }

            if ($mentorName !== null || $mentorNip !== null) {
                $queryMentor = User::where('role', UserRole::Pembimbing);
                $queryMentor->where(function ($q) use ($mentorName, $mentorNip): void {
                    $applied = false;
                    if ($mentorName !== null) {
                        $q->orWhere('name', 'LIKE', '%'.$mentorName.'%');
                        $applied = true;
                    }
                    if ($mentorNip !== null) {
                        $q->orWhere('nip', 'LIKE', '%'.$mentorNip.'%');
                        $applied = true;
                    }
                    if (! $applied) {
                        $q->whereRaw('1 = 0');
                    }
                });
                $pembimbingIds = array_merge($pembimbingIds, $queryMentor->pluck('id')->toArray());
            }
        }

        return array_values(array_unique(array_filter($pembimbingIds, static fn ($v): bool => is_int($v) || ctype_digit((string) $v))));
    }

    public function resolveDivisionIdForDashboard(User $user): ?int
    {
        if (! empty($user->division_id)) {
            return (int) $user->division_id;
        }

        $acceptedRegistration = $user->registrations
            ->first(fn ($r) => is_object($r->status) && method_exists($r->status, 'isAccepted') && $r->status->isAccepted());

        if ($acceptedRegistration === null) {
            return null;
        }

        try {
            return $this->registrationService->resolveDivisionIdFromRegistration($acceptedRegistration);
        } catch (\Throwable) {
            return null;
        }
    }

    private function ensureDivisionIdResolved(User $intern): User
    {
        if (! empty($intern->division_id)) {
            return $intern;
        }

        $acceptedRegistration = $intern->registrations
            ->first(function ($r): bool {
                $isAccepted = false;
                if (is_object($r->status) && method_exists($r->status, 'isAccepted')) {
                    $isAccepted = $r->status->isAccepted();
                } elseif (is_string($r->status)) {
                    $isAccepted = strtolower($r->status) === 'accepted';
                }

                return $isAccepted && empty($r->is_terminated);
            });

        if ($acceptedRegistration === null) {
            $acceptedRegistration = $intern->registrations
                ->first(function ($r): bool {
                    if (is_object($r->status) && method_exists($r->status, 'isAccepted')) {
                        return $r->status->isAccepted();
                    }
                    if (is_string($r->status)) {
                        return strtolower($r->status) === 'accepted';
                    }

                    return false;
                });
        }

        if ($acceptedRegistration === null) {
            return $intern;
        }

        try {
            $divisionId = $this->registrationService->resolveDivisionIdFromRegistration($acceptedRegistration);
        } catch (\Throwable) {
            $divisionId = null;
        }

        if ($divisionId === null) {
            return $intern;
        }

        try {
            $intern->update(['division_id' => $divisionId]);
        } catch (\Throwable) {
        }

        $intern->division_id = $divisionId;

        return $intern;
    }

    /**
     * @return array{assignments:array<int,object>, totalAssignments:int, pendingCount:int, doneCount:int, overdueCount:int}
     */
    private function buildAssignmentDashboardData(User $user): array
    {
        $pembimbingIds = $this->resolvePembimbingIdsForDashboard($user);
        $divisionId = $this->resolveDivisionIdForDashboard($user);

        $materials = collect();
        $tasks = collect();

        if (! empty($pembimbingIds)) {
            $materials = Material::whereIn('pembimbing_id', $pembimbingIds)
                ->where('created_at', '>=', $user->created_at)
                ->latest()
                ->take(10)
                ->get();

            $tasks = Task::whereIn('pembimbing_id', $pembimbingIds)
                ->where('created_at', '>=', $user->created_at)
                ->latest()
                ->take(10)
                ->get();
        } elseif ($divisionId !== null) {
            $materials = Material::whereHas('pembimbing', function ($q) use ($divisionId): void {
                $q->where('division_id', $divisionId);
            })
                ->where('created_at', '>=', $user->created_at)
                ->latest()
                ->take(10)
                ->get();

            $tasks = Task::whereHas('pembimbing', function ($q) use ($divisionId): void {
                $q->where('division_id', $divisionId);
            })
                ->where('created_at', '>=', $user->created_at)
                ->latest()
                ->take(10)
                ->get();
        }

        $merged = collect();
        foreach ($materials as $m) {
            $submitted = \App\Models\ModuleSubmission::where('material_id', $m->id)
                ->where('user_id', $user->id)
                ->exists();
            $deadline = $m->deadline;
            $overdue = $m->is_task && $deadline !== null && \Illuminate\Support\Carbon::parse($deadline)->isPast() && ! $submitted;
            $merged->push((object) [
                'id' => 'material_'.$m->id,
                'source_type' => 'material',
                'title' => $m->title,
                'description' => $m->description,
                'is_task' => (bool) $m->is_task,
                'deadline' => $deadline,
                'created_at' => $m->created_at,
                'submitted' => $submitted,
                'overdue' => $overdue,
            ]);
        }

        foreach ($tasks as $t) {
            $submitted = \App\Models\Submission::where('task_id', $t->id)
                ->where('intern_id', $user->id)
                ->exists();
            $deadline = $t->deadline;
            $overdue = $deadline !== null && \Illuminate\Support\Carbon::parse($deadline)->isPast() && ! $submitted;
            $merged->push((object) [
                'id' => 'task_'.$t->id,
                'source_type' => 'task',
                'title' => $t->title,
                'description' => $t->description,
                'is_task' => true,
                'deadline' => $deadline,
                'created_at' => $t->created_at,
                'submitted' => $submitted,
                'overdue' => $overdue,
            ]);
        }

        $sorted = $merged
            ->sort(function ($a, $b) {
                $aTime = $a->created_at ? $a->created_at->timestamp : 0;
                $bTime = $b->created_at ? $b->created_at->timestamp : 0;

                return $bTime <=> $aTime;
            })
            ->values();

        $totalAssignments = $sorted->count();
        $pendingCount = $sorted->filter(fn ($a): bool => $a->is_task && ! $a->submitted && ! $a->overdue)->count();
        $doneCount = $sorted->filter(fn ($a): bool => $a->submitted)->count();
        $overdueCount = $sorted->filter(fn ($a): bool => $a->overdue)->count();
        $displayAssignments = $sorted->take(2)->all();

        return [
            'assignments' => $displayAssignments,
            'totalAssignments' => $totalAssignments,
            'pendingCount' => $pendingCount,
            'doneCount' => $doneCount,
            'overdueCount' => $overdueCount,
        ];
    }

    /**
     * Susun informasi dokumen untuk latest registration.
     */
    protected function buildDocumentInfo(mixed $latestRegistration): array
    {
        if ($latestRegistration === null) {
            return [
                'cv_exists'                    => false,
                'cv_url'                       => null,
                'cv_basename'                  => null,
                'surat_pengantar_exists'       => false,
                'surat_pengantar_url'          => null,
                'surat_pengantar_basename'     => null,
                'proposal_magang_exists'       => false,
                'proposal_magang_url'          => null,
                'proposal_magang_basename'     => null,
                'surat_balasan_exists'         => false,
                'surat_balasan_download_route' => null,
                'surat_balasan_info'           => null,
            ];
        }

        $cvPath = $latestRegistration->cv_path;
        $cvDisk = (! empty($cvPath) && \Illuminate\Support\Facades\Storage::disk('local')->exists($cvPath))
            ? \Illuminate\Support\Facades\Storage::disk('local')
            : \Illuminate\Support\Facades\Storage::disk('public');
        $cvExists = $cvPath !== null && trim($cvPath) !== '' && $cvDisk->exists($cvPath);
        $cvUrl = $cvExists ? $this->registrationService->getUrlDokumen($cvPath) : null;
        $cvBasename = $cvExists ? basename($cvPath) : null;

        $spPath = $latestRegistration->surat_pengantar_path;
        $spDisk = (! empty($spPath) && \Illuminate\Support\Facades\Storage::disk('local')->exists($spPath))
            ? \Illuminate\Support\Facades\Storage::disk('local')
            : \Illuminate\Support\Facades\Storage::disk('public');
        $spExists = $spPath !== null && trim($spPath) !== '' && $spDisk->exists($spPath);
        $spUrl = $spExists ? $this->registrationService->getUrlDokumen($spPath) : null;
        $spBasename = $spExists ? basename($spPath) : null;

        $pmPath = $latestRegistration->proposal_magang_path ?? null;
        $pmDisk = (! empty($pmPath) && \Illuminate\Support\Facades\Storage::disk('local')->exists($pmPath))
            ? \Illuminate\Support\Facades\Storage::disk('local')
            : \Illuminate\Support\Facades\Storage::disk('public');
        $pmExists = $pmPath !== null && trim($pmPath) !== '' && $pmDisk->exists($pmPath);
        $pmUrl = $pmExists ? $this->registrationService->getUrlDokumen($pmPath) : null;
        $pmBasename = $pmExists ? basename($pmPath) : null;

        $sbPath = $latestRegistration->surat_balasan_path;
        $sbDisk = (! empty($sbPath) && \Illuminate\Support\Facades\Storage::disk('local')->exists($sbPath))
            ? \Illuminate\Support\Facades\Storage::disk('local')
            : \Illuminate\Support\Facades\Storage::disk('public');
        $sbExists = $sbPath !== null
            && trim($sbPath) !== ''
            && $sbDisk->exists($sbPath)
            && $latestRegistration->isAccepted();
        $sbRoute = $sbExists ? route('participant.applications.reply-letter.download', $latestRegistration->id) : null;
        $sbInfo = null;
        if ($sbExists) {
            $bytes = $sbDisk->size($sbPath);
            $sbInfo = [
                'basename'      => basename($sbPath),
                'size_kb'       => (int) round($bytes / 1024),
                'human_size'    => number_format((int) round($bytes / 1024), 0, ',', '.').' KB',
                'last_modified' => date('d M Y H:i', $sbDisk->lastModified($sbPath)),
            ];
        }

        return [
            'cv_exists'                    => $cvExists,
            'cv_url'                       => $cvUrl,
            'cv_basename'                  => $cvBasename,
            'surat_pengantar_exists'       => $spExists,
            'surat_pengantar_url'          => $spUrl,
            'surat_pengantar_basename'     => $spBasename,
            'proposal_magang_exists'       => $pmExists,
            'proposal_magang_url'          => $pmUrl,
            'proposal_magang_basename'     => $pmBasename,
            'surat_balasan_exists'         => $sbExists,
            'surat_balasan_download_route' => $sbRoute,
            'surat_balasan_info'           => $sbInfo,
        ];
    }

    /**
     * Bangun timeline urutan status pendaftaran dari Submitted s/d Accepted/Rejected.
     */
    protected function buildStatusTimeline(mixed $reg): array
    {
        $sv = $reg?->status;

        $steps = [
            [
                'step'  => 'submitted',
                'label' => 'Pendaftaran Diajukan',
                'icon'  => 'bi-send-check-fill',
                'color' => 'primary',
            ],
            [
                'step'  => 'under_review',
                'label' => 'Sedang Diverifikasi Admin',
                'icon'  => 'bi-hourglass-split',
                'color' => 'warning',
            ],
            [
                'step'  => 'decision',
                'label' => 'Keputusan Admin (Accepted / Rejected)',
                'icon'  => 'bi-patch-check-fill',
                'color' => 'info',
            ],
            [
                'step'  => 'surat_balasan',
                'label' => 'Surat Balasan Diterbitkan',
                'icon'  => 'bi-file-earmark-pdf-fill',
                'color' => 'indigo',
            ],
        ];

        if ($reg === null || $sv === null) {
            return array_map(static fn (array $s): array => $s + [
                'done'   => false,
                'active' => false,
                'date'   => null,
            ], $steps);
        }

        $isAccepted = false;
        $isRejected = false;
        if (is_object($sv) && method_exists($sv, 'isAccepted')) {
            $isAccepted = $sv->isAccepted();
            $isRejected = $sv->isRejected();
            $svValue = $sv->value ?? 'unknown';
        } else {
            $svValue = (string) $sv;
            $isAccepted = strtolower($svValue) === 'accepted' || strtolower($svValue) === 'diterima';
            $isRejected = strtolower($svValue) === 'rejected' || strtolower($svValue) === 'ditolak';
        }

        $orderMap = [
            'submitted'     => 1,
            'under_review'  => 2,
            'accepted'      => 3,
            'diterima'      => 3,
            'rejected'      => 3,
            'ditolak'       => 3,
        ];
        $currentStepNo = $orderMap[strtolower($svValue)] ?? 0;

        $timeline = [];
        foreach ($steps as $idx => $s) {
            $stepNo = match ($idx) {
                0 => 1,
                1 => 2,
                2 => 3,
                3 => 4,
                default => 99,
            };

            $done = false;
            $active = false;
            $date = null;

            if ($stepNo <= 3) {
                $done = $currentStepNo >= $stepNo;
                if ($done && $stepNo === 3) {
                    $s['color'] = match (true) {
                        $isAccepted => 'success',
                        $isRejected => 'danger',
                        default     => 'info',
                    };
                    $s['icon']  = match (true) {
                        $isAccepted => 'bi-check-circle-fill',
                        $isRejected => 'bi-x-circle-fill',
                        default     => 'bi-patch-check-fill',
                    };
                    $s['label'] = match (true) {
                        $isAccepted => 'DITERIMA (Accepted)',
                        $isRejected => 'DITOLAK (Rejected)',
                        default     => 'Menunggu Keputusan',
                    };
                    $tgl = $reg?->updated_at ?? $reg?->tanggal_submit ?? null;
                    if ($tgl !== null) {
                        if (is_object($tgl) && method_exists($tgl, 'translatedFormat')) {
                            $date = $tgl->translatedFormat('d M Y H:i');
                        } else {
                            $date = date('d M Y H:i', strtotime((string) $tgl));
                        }
                    }
                }
                if ($done && $stepNo === 1 && $reg?->tanggal_submit !== null) {
                    $tanggalSubmit = $reg->tanggal_submit;
                    if (is_object($tanggalSubmit) && method_exists($tanggalSubmit, 'translatedFormat')) {
                        $date = $tanggalSubmit->translatedFormat('d M Y H:i');
                    } else {
                        $date = date('d M Y H:i', strtotime((string) $tanggalSubmit));
                    }
                }
                if (! $done && $stepNo - $currentStepNo === 1) {
                    $active = true;
                }
            } else {
                $disk = \Illuminate\Support\Facades\Storage::disk('public');
                $sbExists = $reg?->surat_balasan_path !== null
                    && $disk->exists($reg->surat_balasan_path);
                $done = $isAccepted && $sbExists;
                $s['color'] = $done ? 'success' : 'secondary';
                if ($done) {
                    $date = date('d M Y H:i', $disk->lastModified($reg->surat_balasan_path));
                }
            }

            $timeline[] = $s + compact('done', 'active', 'date');
        }

        return $timeline;
    }
}
