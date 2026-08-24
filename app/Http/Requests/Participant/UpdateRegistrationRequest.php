<?php

namespace App\Http\Requests\Participant;

use App\Models\Position;
use App\Models\Registration;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null || ! $user->isPeserta() || ! $user->hasProfile()) {
            return false;
        }

        /** @var mixed $registrationRaw */
        $registrationRaw = $this->route('registration');
        $registration = null;

        if ($registrationRaw instanceof Registration) {
            $registration = $registrationRaw;
        } elseif (is_numeric($registrationRaw)) {
            $registration = Registration::query()->find((int) $registrationRaw);
        }

        if (! $registration instanceof Registration) {
            return false;
        }

        return $registration->dapatDiubah()
            && (int) $registration->user_id === (int) $user->id;
    }

    public function rules(): array
    {
        /** @var Registration|null $current */
        $current = $this->route('registration');

        return [
            'position_id' => [
                'required',
                'integer',
                'exists:positions,id',
                function (string $attribute, mixed $value, Closure $fail) use ($current): void {
                    $position = Position::find($value);
                    if (! $position) {
                        return;
                    }
                    if (! $position->sedangDibuka()) {
                        $fail('Posisi magang ini tidak sedang dibuka atau sudah ditutup.');
                    }
                    if ($current === null) {
                        return;
                    }
                    $existing = $this->user()->registrations()
                        ->where('position_id', $value)
                        ->where('id', '!=', $current->id)
                        ->whereIn('status', ['submitted', 'under_review', 'accepted'])
                        ->exists();
                    if ($existing) {
                        $fail('Anda sudah memiliki pendaftaran aktif untuk posisi magang yang lain.');
                    }
                },
            ],

            'periode_mulai' => [
                'required',
                'date',
            ],
            'periode_selesai' => [
                'required',
                'date',
                'after_or_equal:periode_mulai',
            ],

            'cv' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:2048',
            ],

            'surat_pengantar' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:3072',
            ],

            'proposal_magang' => [
                'nullable',
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

            'periode_selesai.required'  => 'Periode selesai magang wajib diisi.',
            'periode_selesai.date'      => 'Format periode selesai tidak valid (harus tanggal).',
            'periode_selesai.after_or_equal' => 'Periode selesai magang harus sama atau setelah periode mulai.',

            'cv.file'            => 'CV harus berupa file yang diunggah.',
            'cv.mimes'           => 'CV wajib berformat PDF.',
            'cv.max'             => 'Ukuran CV maksimal 2 MB (2048 KB). Kompres PDF jika terlalu besar.',

            'surat_pengantar.file'       => 'Surat pengantar harus berupa file yang diunggah.',
            'surat_pengantar.mimes'      => 'Surat Pengantar wajib berformat PDF.',
            'surat_pengantar.max'        => 'Ukuran Surat Pengantar maksimal 3 MB (3072 KB).',

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
