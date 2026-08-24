<?php

namespace App\Http\Controllers\Participant;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends ParticipantController
{
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

        return view($this->viewPrefix.'.dashboard', compact(
            'user',
            'profile',
            'hasProfile',
            'latestRegistration',
            'totalRegistrations',
            'documentInfo',
            'timeline',
        ));
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

        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        $cvPath = $latestRegistration->cv_path;
        $cvExists = $cvPath !== null && trim($cvPath) !== '' && $disk->exists($cvPath);
        $cvUrl = $cvExists ? $disk->url($cvPath) : null;
        $cvBasename = $cvExists ? basename($cvPath) : null;

        $spPath = $latestRegistration->surat_pengantar_path;
        $spExists = $spPath !== null && trim($spPath) !== '' && $disk->exists($spPath);
        $spUrl = $spExists ? $disk->url($spPath) : null;
        $spBasename = $spExists ? basename($spPath) : null;

        $pmPath = $latestRegistration->proposal_magang_path ?? null;
        $pmExists = $pmPath !== null && trim($pmPath) !== '' && $disk->exists($pmPath);
        $pmUrl = $pmExists ? $disk->url($pmPath) : null;
        $pmBasename = $pmExists ? basename($pmPath) : null;

        $sbPath = $latestRegistration->surat_balasan_path;
        $sbExists = $sbPath !== null
            && trim($sbPath) !== ''
            && $disk->exists($sbPath)
            && $latestRegistration->isAccepted();
        $sbRoute = $sbExists ? route('participant.applications.reply-letter.download', $latestRegistration->id) : null;
        $sbInfo = null;
        if ($sbExists) {
            $bytes = $disk->size($sbPath);
            $sbInfo = [
                'basename'      => basename($sbPath),
                'size_kb'       => (int) round($bytes / 1024),
                'human_size'    => number_format((int) round($bytes / 1024), 0, ',', '.').' KB',
                'last_modified' => date('d M Y H:i', $disk->lastModified($sbPath)),
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

        $orderMap = [
            'submitted'     => 1,
            'under_review'  => 2,
            'accepted'      => 3,
            'rejected'      => 3,
        ];
        $currentStepNo = $orderMap[$sv->value] ?? 0;

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
                        $sv->isAccepted() => 'success',
                        $sv->isRejected() => 'danger',
                        default           => 'info',
                    };
                    $s['icon']  = match (true) {
                        $sv->isAccepted() => 'bi-check-circle-fill',
                        $sv->isRejected() => 'bi-x-circle-fill',
                        default           => 'bi-patch-check-fill',
                    };
                    $s['label'] = match (true) {
                        $sv->isAccepted() => 'DITERIMA (Accepted)',
                        $sv->isRejected() => 'DITOLAK (Rejected)',
                        default           => 'Menunggu Keputusan',
                    };
                    $tgl = $reg?->updated_at ?? $reg?->tanggal_submit ?? null;
                    if ($tgl !== null) {
                        $date = $tgl->translatedFormat('d M Y H:i');
                    }
                }
                if ($done && $stepNo === 1 && $reg?->tanggal_submit !== null) {
                    $date = $reg->tanggal_submit->translatedFormat('d M Y H:i');
                }
                if (! $done && $stepNo - $currentStepNo === 1) {
                    $active = true;
                }
            } else {
                $disk = \Illuminate\Support\Facades\Storage::disk('public');
                $sbExists = $reg?->surat_balasan_path !== null
                    && $disk->exists($reg->surat_balasan_path);
                $done = $sv->isAccepted() && $sbExists;
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
