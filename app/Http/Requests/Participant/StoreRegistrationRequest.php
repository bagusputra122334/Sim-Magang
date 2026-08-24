<?php

namespace App\Http\Requests\Participant;

use App\Models\Position;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->isPeserta()
            && $this->user()->hasProfile();
    }

    public function rules(): array
    {
        return [
            'position_id' => [
                'required',
                'integer',
                'exists:positions,id',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $position = Position::find($value);
                    if (! $position) {
                        return;
                    }
                    if (! $position->sedangDibuka()) {
                        $fail('Posisi magang ini tidak sedang dibuka atau sudah ditutup.');
                    }
                    $existing = $this->user()->registrations()
                        ->where('position_id', $value)
                        ->where(function ($q): void {
                            $q->whereIn('status', ['submitted', 'under_review'])
                                ->orWhere(function ($sq): void {
                                    $sq->where('status', 'accepted')
                                        ->where('is_terminated', false);
                                });
                        })
                        ->exists();
                    if ($existing) {
                        $fail('Anda sudah memiliki pendaftaran magang aktif untuk posisi ini. Silakan cek status pendaftaran di Dashboard.');
                    }
                },
            ],

            'periode_mulai' => [
                'required',
                'date',
                'after:today',
            ],
            'periode_selesai' => [
                'required',
                'date',
                'after_or_equal:periode_mulai',
                'after:periode_mulai',
            ],

            'cv' => [
                'required',
                'file',
                'mimes:pdf',
                'max:2048',
            ],

            'surat_pengantar' => [
                'required',
                'file',
                'mimes:pdf',
                'max:3072',
            ],

            'proposal_magang' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'position_id.required'  => 'Anda wajib memilih posisi magang terlebih dahulu.',
            'position_id.integer'   => 'ID posisi tidak valid.',
            'position_id.exists'    => 'Posisi magang yang dipilih tidak ditemukan di database.',

            'periode_mulai.required'    => 'Periode mulai magang wajib diisi.',
            'periode_mulai.date'        => 'Format periode mulai tidak valid (harus tanggal).',
            'periode_mulai.after'       => 'Periode mulai magang harus HARI SETELAH hari ini.',

            'periode_selesai.required'  => 'Periode selesai magang wajib diisi.',
            'periode_selesai.date'      => 'Format periode selesai tidak valid (harus tanggal).',
            'periode_selesai.after_or_equal' => 'Periode selesai magang harus sama atau setelah periode mulai.',
            'periode_selesai.after'     => 'Periode selesai magang harus SETELAH periode mulai (minimal 2 hari).',

            'cv.required'         => 'Berkas CV wajib diunggah.',
            'cv.file'            => 'CV harus berupa file yang diunggah.',
            'cv.mimes'           => 'CV wajib berformat PDF.',
            'cv.max'             => 'Ukuran CV maksimal 2 MB (2048 KB). Kompres PDF jika terlalu besar.',

            'surat_pengantar.required'   => 'Surat Pengantar dari Sekolah / Universitas wajib diunggah.',
            'surat_pengantar.file'       => 'Surat pengantar harus berupa file yang diunggah.',
            'surat_pengantar.mimes'      => 'Surat Pengantar wajib berformat PDF.',
            'surat_pengantar.max'        => 'Ukuran Surat Pengantar maksimal 3 MB (3072 KB).',

            'proposal_magang.required'   => 'Proposal Magang wajib diunggah.',
            'proposal_magang.file'       => 'Proposal magang harus berupa file yang diunggah.',
            'proposal_magang.mimes'      => 'Proposal Magang wajib berformat PDF.',
            'proposal_magang.max'        => 'Ukuran Proposal Magang maksimal 5 MB (5120 KB).',
        ];
    }

    public function attributes(): array
    {
        return [
            'position_id'      => 'Posisi Magang',
            'periode_mulai'    => 'Periode Mulai Magang',
            'periode_selesai'  => 'Periode Selesai Magang',
            'cv'               => 'Curriculum Vitae (CV)',
            'surat_pengantar'  => 'Surat Pengantar Institusi',
            'proposal_magang'  => 'Proposal Magang',
        ];
    }
}
