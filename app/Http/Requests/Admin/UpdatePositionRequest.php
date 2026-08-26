<?php

namespace App\Http\Requests\Admin;

use App\Enums\PositionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $position = $this->route('position');
        $positionId = null;

        if ($position instanceof \App\Models\Position) {
            $positionId = (int) $position->id;
        } elseif (is_numeric($position)) {
            $positionId = (int) $position;
        }

        return [
            'nama_posisi' => [
                'required',
                'string',
                'max:100',
                Rule::unique('positions', 'nama_posisi')->ignore($positionId)->whereNull('deleted_at'),
            ],
            'slug' => [
                'required',
                'string',
                'max:150',
                'alpha_dash',
                Rule::unique('positions', 'slug')->ignore($positionId)->whereNull('deleted_at'),
            ],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'kualifikasi' => ['nullable', 'string', 'max:5000'],
            'kuota' => ['nullable', 'integer', 'min:0'],
            'mentor_name' => ['nullable', 'string', 'max:255'],
            'mentor_nip' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::enum(PositionStatus::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $namaPosisi = $this->has('nama_posisi') ? trim((string) $this->input('nama_posisi')) : null;
        $slug = $this->has('slug') ? trim((string) $this->input('slug')) : null;
        $mentorName = $this->has('mentor_name') ? trim((string) $this->input('mentor_name')) : null;
        $mentorNip = $this->has('mentor_nip') ? trim((string) $this->input('mentor_nip')) : null;

        $mergeData = [];

        if ($namaPosisi !== null) {
            $mergeData['nama_posisi'] = $namaPosisi;
        }

        if ($slug !== null) {
            $mergeData['slug'] = $slug;
        }

        if ($mentorName !== null) {
            $mergeData['mentor_name'] = $mentorName !== '' ? $mentorName : null;
        }

        if ($mentorNip !== null) {
            $mergeData['mentor_nip'] = $mentorNip !== '' ? $mentorNip : null;
        }

        if (! $this->has('kuota') || $this->input('kuota') === null || $this->input('kuota') === '') {
            $mergeData['kuota'] = 0;
        }

        if (! empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    public function messages(): array
    {
        return [
            'nama_posisi.required'   => 'Nama posisi magang wajib diisi.',
            'nama_posisi.max'        => 'Nama posisi maksimal 100 karakter.',
            'nama_posisi.unique'     => 'Nama posisi ini sudah ada di database, silakan gunakan nama lain.',

            'slug.required'       => 'Slug URL wajib diisi.',
            'slug.max'              => 'Slug maksimal 150 karakter.',
            'slug.alpha_dash'      => 'Slug hanya boleh huruf, angka, strip, dan underscore.',
            'slug.unique'          => 'Slug ini sudah dipakai posisi lain.',

            'deskripsi.max'        => 'Deskripsi maksimal 5000 karakter.',

            'kualifikasi.max'   => 'Kualifikasi maksimal 5000 karakter.',

            'kuota.required'    => 'Kuota peserta wajib diisi.',
            'kuota.integer'       => 'Kuota harus bilangan bulat (angka).',
            'kuota.min'         => 'Kuota minimal 1 orang.',
            'kuota.max'         => 'Kuota maksimal 500 orang.',

            'status.required'      => 'Status posisi wajib dipilih.',
            'status.enum'       => 'Pilihan status tidak valid (hanya Aktif / Tidak Aktif).',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_posisi'   => 'Nama Posisi Magang',
            'slug'          => 'Slug URL',
            'deskripsi'     => 'Deskripsi & Tugas',
            'kualifikasi'   => 'Kualifikasi',
            'kuota'         => 'Kuota Jumlah Peserta',
            'status'        => 'Status Posisi',
        ];
    }
}
