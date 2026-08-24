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

        if (mb_strlen($query) < 1) {
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
            $registrations = Registration::with(['user.profile', 'position'])
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
                $results[] = [
                    'type'         => 'registration',
                    'id'           => $reg->id,
                    'name'         => $name,
                    'email'        => $reg->user?->email,
                    'code'         => $reg->nomor_pendaftaran,
                    'position'     => $reg->position?->nama_posisi ?? 'Posisi Tidak Ditemukan',
                    'status'       => $reg->status->value,
                    'status_label' => $reg->status->label(),
                    'url'          => route('admin.applications.show', $reg->id),
                ];
            }

            // Positions search
            $positions = Position::where('nama_posisi', 'LIKE', "%{$query}%")
                ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                ->latest('id')
                ->limit(4)
                ->get();

            foreach ($positions as $pos) {
                $results[] = [
                    'type'         => 'position',
                    'id'           => $pos->id,
                    'name'         => $pos->nama_posisi,
                    'email'        => null,
                    'code'         => 'KUOTA: ' . $pos->kuota . ' ORANG',
                    'position'     => 'Formasi / Lowongan Magang',
                    'status'       => $pos->status->value ?? 'aktif',
                    'status_label' => $pos->status->label() ?? 'Aktif',
                    'url'          => route('admin.positions.index', ['search' => $pos->nama_posisi]),
                ];
            }
        } else {
            // PARTICIPANT SEARCH: Only own registrations & available positions
            $registrations = Registration::with(['position'])
                ->where('user_id', $user->id)
                ->where(function ($q) use ($query, $matchedStatuses) {
                    $q->where('nomor_pendaftaran', 'LIKE', "%{$query}%")
                        ->orWhereHas('position', function ($posQ) use ($query) {
                            $posQ->where('nama_posisi', 'LIKE', "%{$query}%");
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
                $results[] = [
                    'type'         => 'registration',
                    'id'           => $reg->id,
                    'name'         => $name,
                    'email'        => $user->email,
                    'code'         => $reg->nomor_pendaftaran,
                    'position'     => $reg->position?->nama_posisi ?? 'Posisi Magang',
                    'status'       => $reg->status->value,
                    'status_label' => $reg->status->label(),
                    'url'          => route('participant.registrations.show', $reg->id),
                ];
            }

            // Positions search for participant
            $positions = Position::where('nama_posisi', 'LIKE', "%{$query}%")
                ->latest('id')
                ->limit(4)
                ->get();

            foreach ($positions as $pos) {
                $results[] = [
                    'type'         => 'position',
                    'id'           => $pos->id,
                    'name'         => $pos->nama_posisi,
                    'email'        => null,
                    'code'         => 'Formasi Tersedia',
                    'position'     => 'Kuota: ' . $pos->kuota . ' Peserta',
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
}
