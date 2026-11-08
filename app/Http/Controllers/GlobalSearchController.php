<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    /**
     * Handle global quick search across registrations, participants, and positions.
     */
    public function search(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['results' => []], 401);
        }

        $rawQuery = (string) $request->query('q', '');
        $query = trim($rawQuery);

        if (mb_strlen($query) < 1 && ! $request->filled('opd_id')) {
            return response()->json([
                'query'   => '',
                'count'   => 0,
                'results' => [],
            ]);
        }

        $results = [];

        // Alias matching for Indonesian / English statuses
        $statusTerms = [
            'submitted'    => ['submitted', 'diajukan', 'baru', 'masuk'],
            'under_review' => ['under_review', 'under review', 'review', 'diverifikasi', 'verifikasi', 'proses', 'peninjauan'],
            'accepted'     => ['accepted', 'diterima', 'lulus', 'disetujui'],
            'rejected'     => ['rejected', 'ditolak', 'tidak diterima'],
        ];

        $matchedStatuses = [];
        $lowQuery = mb_strtolower($query);
        foreach ($statusTerms as $statusEnum => $aliases) {
            foreach ($aliases as $alias) {
                if (str_contains($alias, $lowQuery) || str_contains($lowQuery, $alias)) {
                    $matchedStatuses[] = $statusEnum;
                    break;
                }
            }
        }

        if ($user->isAdmin()) {
            // ADMIN SEARCH: Scope across all registrations & positions
            $registrations = Registration::with(['user.profile', 'position.opd', 'opd'])
                ->where(function ($q) use ($query, $matchedStatuses) {
                    $q->where('nomor_pendaftaran', 'LIKE', "%{$query}%")
                        ->orWhereHas('user', function ($uq) use ($query) {
                            $uq->where('name', 'LIKE', "%{$query}%")
                               ->orWhere('email', 'LIKE', "%{$query}%");
                        })
                        ->orWhereHas('user.profile', function ($pq) use ($query) {
                            $pq->where('nama_lengkap', 'LIKE', "%{$query}%")
                               ->orWhere('institusi', 'LIKE', "%{$query}%")
                               ->orWhere('nim_nis', 'LIKE', "%{$query}%")
                               ->orWhere('jurusan', 'LIKE', "%{$query}%");
                        })
                        ->orWhereHas('position', function ($posQ) use ($query) {
                            $posQ->where('nama_posisi', 'LIKE', "%{$query}%");
                        })
                        ->orWhereHas('opd', function ($opdQ) use ($query) {
                            $opdQ->where('name', 'LIKE', "%{$query}%")
                                 ->orWhere('code', 'LIKE', "%{$query}%");
                        });

                    if (!empty($matchedStatuses)) {
                        $q->orWhereIn('status', $matchedStatuses);
                    }
                })
                ->latest('id')
                ->limit(8)
                ->get();

            foreach ($registrations as $reg) {
                $name = $reg->user?->profile?->nama_lengkap ?? $reg->user?->name ?? 'Pendaftar';
                $opdName = $reg->opd?->name ?? $reg->position?->opd?->name ?? 'Dinas Komunikasi dan Informatika, Statistik dan Persandian';
                $results[] = [
                    'type'         => 'registration',
                    'id'           => $reg->id,
                    'title'        => $name,
                    'name'         => $name,
                    'email'        => $reg->user?->email,
                    'code'         => $reg->nomor_pendaftaran,
                    'position'     => $reg->position?->nama_posisi ?? 'Posisi Tidak Ditemukan',
                    'opd_name'     => $opdName,
                    'status'       => $reg->status->value,
                    'status_label' => $reg->status->label(),
                    'url'          => route('admin.applications.show', $reg->id),
                ];
            }

            // Positions search
            $positions = Position::with('opd')
                ->where(function ($posQ) use ($query) {
                    $posQ->where('nama_posisi', 'LIKE', "%{$query}%")
                         ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                         ->orWhereHas('opd', function ($opdQ) use ($query) {
                             $opdQ->where('name', 'LIKE', "%{$query}%")
                                  ->orWhere('code', 'LIKE', "%{$query}%");
                         });
                })
                ->latest('id')
                ->limit(4)
                ->get();

            foreach ($positions as $pos) {
                $opdName = $pos->opd?->name ?? 'Dinas Komunikasi dan Informatika, Statistik dan Persandian';
                $results[] = [
                    'type'         => 'position',
                    'id'           => $pos->id,
                    'title'        => $pos->nama_posisi,
                    'name'         => $pos->nama_posisi,
                    'email'        => null,
                    'code'         => 'KUOTA: ' . $pos->kuota . ' ORANG',
                    'position'     => 'Formasi / Lowongan Magang',
                    'opd_name'     => $opdName,
                    'status'       => $pos->status->value ?? 'aktif',
                    'status_label' => $pos->status->label() ?? 'Aktif',
                    'url'          => route('admin.positions.index', ['search' => $pos->nama_posisi]),
                ];
            }
        } else {
            // PARTICIPANT SEARCH: Only own registrations & available positions
            $registrations = Registration::with(['position.opd', 'opd'])
                ->where('user_id', $user->id)
                ->where(function ($q) use ($query, $matchedStatuses) {
                    $q->where('nomor_pendaftaran', 'LIKE', "%{$query}%")
                        ->orWhereHas('position', function ($posQ) use ($query) {
                            $posQ->where('nama_posisi', 'LIKE', "%{$query}%");
                        })
                        ->orWhereHas('opd', function ($opdQ) use ($query) {
                            $opdQ->where('name', 'LIKE', "%{$query}%")
                                 ->orWhere('code', 'LIKE', "%{$query}%");
                        });

                    if (!empty($matchedStatuses)) {
                        $q->orWhereIn('status', $matchedStatuses);
                    }
                })
                ->latest('id')
                ->limit(6)
                ->get();

            foreach ($registrations as $reg) {
                $name = $user->profile?->nama_lengkap ?? $user->name;
                $opdName = $reg->opd?->name ?? $reg->position?->opd?->name ?? 'Dinas Komunikasi dan Informatika, Statistik dan Persandian';
                $results[] = [
                    'type'         => 'registration',
                    'id'           => $reg->id,
                    'name'         => $name,
                    'email'        => $user->email,
                    'code'         => $reg->nomor_pendaftaran,
                    'position'     => $reg->position?->nama_posisi ?? 'Posisi Magang',
                    'opd_name'     => $opdName,
                    'status'       => $reg->status->value,
                    'status_label' => $reg->status->label(),
                    'url'          => route('participant.registrations.show', $reg->id),
                ];
            }

            // Positions search for participant
            $posQuery = Position::with('opd');
            if ($request->filled('opd_id')) {
                $posQuery->where('opd_id', $request->input('opd_id'));
            }

            if (mb_strlen($query) >= 1) {
                $posQuery->where(function ($posQ) use ($query) {
                    $posQ->where('nama_posisi', 'LIKE', "%{$query}%")
                         ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                         ->orWhereHas('opd', function ($opdQ) use ($query) {
                             $opdQ->where('name', 'LIKE', "%{$query}%")
                                  ->orWhere('code', 'LIKE', "%{$query}%");
                         });
                });
            }

            $positions = $posQuery->latest('id')
                ->limit(4)
                ->get();

            foreach ($positions as $pos) {
                $opdName = $pos->opd?->name ?? 'Dinas Komunikasi dan Informatika, Statistik dan Persandian';
                $results[] = [
                    'type'         => 'position',
                    'id'           => $pos->id,
                    'title'        => $pos->nama_posisi,
                    'name'         => $pos->nama_posisi,
                    'email'        => null,
                    'code'         => 'Formasi Tersedia',
                    'position'     => 'Kuota: ' . $pos->kuota . ' Peserta',
                    'opd_name'     => $opdName,
                    'status'       => 'aktif',
                    'status_label' => 'Buka',
                    'url'          => route('participant.registrations.create', ['posisi' => $pos->id]),
                ];
            }
        }

        return response()->json([
            'query'   => $query,
            'count'   => count($results),
            'results' => $results,
        ]);
    }

    /**
     * Public search endpoint for positions & OPD filtering without authentication.
     */
    public function publicSearch(Request $request): JsonResponse
    {
        $rawQuery = (string) $request->query('q', '');
        $query = trim($rawQuery);

        if (mb_strlen($query) < 1 && ! $request->filled('opd_id')) {
            return response()->json([
                'query'   => '',
                'count'   => 0,
                'results' => [],
            ]);
        }

        $posQuery = Position::with('opd')
            ->where('status', \App\Enums\PositionStatus::Aktif);

        if ($request->filled('opd_id')) {
            $posQuery->where('opd_id', $request->input('opd_id'));
        }

        if (mb_strlen($query) >= 1) {
            $posQuery->where(function ($posQ) use ($query) {
                $posQ->where('nama_posisi', 'LIKE', "%{$query}%")
                     ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                     ->orWhereHas('opd', function ($opdQ) use ($query) {
                         $opdQ->where('name', 'LIKE', "%{$query}%")
                              ->orWhere('code', 'LIKE', "%{$query}%");
                     });
            });
        }

        $positions = $posQuery->latest('id')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($positions as $pos) {
            $opdName = $pos->opd?->name ?? 'Dinas Komunikasi dan Informatika, Statistik dan Persandian';
            $results[] = [
                'type'         => 'position',
                'id'           => $pos->id,
                'title'        => $pos->nama_posisi,
                'name'         => $pos->nama_posisi,
                'email'        => null,
                'code'         => 'KUOTA: ' . $pos->kuota . ' ORANG',
                'position'     => 'Formasi / Lowongan Magang',
                'opd_name'     => $opdName,
                'status'       => 'aktif',
                'status_label' => 'Buka',
                'url'          => route('participant.registrations.create', ['posisi' => $pos->id]),
            ];
        }

        return response()->json([
            'query'   => $query,
            'count'   => count($results),
            'results' => $results,
        ]);
    }
}
