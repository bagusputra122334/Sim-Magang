<?php

namespace App\Http\Requests\Participant;

use App\Enums\JenisKelamin;
use App\Enums\ParticipantType;
use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null || ! $user->isPeserta()) {
            return false;
        }

        if (! $user->hasProfile()) {
            return true;
        }

        $existing = $user->profile;

        if (! $existing instanceof Profile) {
            return true;
        }

        $blank = ($existing->nik === null || trim((string) $existing->nik) === '')
            && ($existing->nama_lengkap === null || trim((string) $existing->nama_lengkap) === '')
            && ($existing->nis_nim === null || trim((string) $existing->nis_nim) === '')
            && ($existing->institusi === null || trim((string) $existing->institusi) === '');

        return $blank;
    }

    protected function prepareForValidation(): void
    {
        $rawType = strtolower((string) $this->input('participant_type', 'university'));
        $isStudent = in_array($rawType, ['student', 'siswa'], true);

        $nimInput = $this->input('nim');
        $nisNimInput = $this->input('nis_nim');

        if (is_string($nimInput) && str_starts_with(trim($nimInput), '[object')) {
            $nimInput = null;
        }
        if (is_string($nisNimInput) && str_starts_with(trim($nisNimInput), '[object')) {
            $nisNimInput = null;
        }

        if ($isStudent) {
            $this->merge([
                'nim'      => null,
                'semester' => null,
                'nis_nim'  => $nisNimInput !== null && trim((string) $nisNimInput) !== '' ? trim((string) $nisNimInput) : null,
            ]);
        } else {
            $resolvedNim = null;
            if ($nimInput !== null && trim((string) $nimInput) !== '') {
                $resolvedNim = trim((string) $nimInput);
            } elseif ($nisNimInput !== null && trim((string) $nisNimInput) !== '') {
                $resolvedNim = trim((string) $nisNimInput);
            }

            $this->merge([
                'nim'     => $resolvedNim,
                'nis_nim' => $resolvedNim,
            ]);
        }
    }

    public function rules(): array
    {
        $rawType = strtolower((string) $this->input('participant_type', 'university'));
        $isStudent = in_array($rawType, ['student', 'siswa'], true);

        return [
            'participant_type' => [
                'required',
                Rule::enum(ParticipantType::class),
            ],
            'nik' => [
                'required',
                'numeric',
                'digits:16',
                Rule::unique('profiles', 'nik'),
            ],
            'nama_lengkap' => [
                'required',
                'string',
                'min:3',
                'max:150',
            ],
            'nis_nim' => [
                $isStudent ? 'nullable' : 'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9.\-\/]+$/',
                Rule::unique('profiles', 'nis_nim'),
            ],
            'nim' => $isStudent ? ['nullable'] : [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9.\-\/]+$/',
                Rule::unique('profiles', 'nim'),
            ],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
                'after:1980-01-01',
            ],
            'jenis_kelamin' => [
                'required',
                Rule::enum(JenisKelamin::class),
            ],
            'alamat' => ['required', 'string', 'min:10', 'max:2000'],
            'no_telepon' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/',
            ],
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'institusi' => ['required', 'string', 'max:200'],
            'jurusan'   => ['required', 'string', 'max:150'],
            'semester'  => [
                $isStudent ? 'nullable' : 'required',
                $isStudent ? 'nullable' : 'integer',
                $isStudent ? 'nullable' : 'min:1',
                $isStudent ? 'nullable' : 'max:14',
            ],
            'tahun_angkatan' => ['required', 'string', 'max:10', 'regex:/^[0-9\/\-]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'participant_type.required' => 'Kategori peserta (Mahasiswa / Siswa) wajib dipilih.',
            'participant_type.enum'     => 'Kategori peserta tidak valid.',

            'nik.required'              => 'NIK wajib diisi.',
            'nik.numeric'               => 'NIK hanya boleh berisi angka.',
            'nik.digits'                => 'NIK harus tepat 16 digit.',
            'nik.unique'                => 'NIK ini sudah terdaftar di sistem.',

            'nama_lengkap.required'     => 'Nama lengkap wajib diisi.',
            'nama_lengkap.min'          => 'Nama lengkap minimal 3 karakter.',
            'nama_lengkap.max'          => 'Nama lengkap maksimal 150 karakter.',

            'nis_nim.required'          => 'NIM wajib diisi untuk kategori Mahasiswa.',
            'nis_nim.max'               => 'NIS / NIM maksimal 50 karakter.',
            'nis_nim.regex'             => 'NIS / NIM hanya boleh huruf, angka, titik, strip, dan garis miring.',
            'nis_nim.unique'            => 'NIS / NIM ini sudah terdaftar di sistem.',

            'nim.required'              => 'NIM wajib diisi untuk kategori Mahasiswa.',
            'nim.max'                   => 'NIM maksimal 30 karakter.',
            'nim.regex'                 => 'NIM hanya boleh huruf, angka, titik, strip, dan garis miring.',
            'nim.unique'                => 'NIM ini sudah dipakai peserta lain.',

            'tempat_lahir.required'     => 'Tempat lahir wajib diisi.',
            'tempat_lahir.max'          => 'Tempat lahir maksimal 100 karakter.',

            'tanggal_lahir.required'    => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'        => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before'      => 'Tanggal lahir harus sebelum hari ini.',
            'tanggal_lahir.after'       => 'Tanggal lahir tidak masuk akal (terlalu tua).',

            'jenis_kelamin.required'    => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.enum'        => 'Pilihan jenis kelamin tidak valid.',

            'alamat.required'           => 'Alamat wajib diisi.',
            'alamat.min'                => 'Alamat minimal 10 karakter.',
            'alamat.max'                => 'Alamat maksimal 2000 karakter.',

            'no_telepon.required'       => 'Nomor HP / WhatsApp wajib diisi.',
            'no_telepon.max'            => 'Nomor telepon maksimal 20 digit.',
            'no_telepon.regex'          => 'Format nomor HP tidak valid. Gunakan 08xxxxx atau +628xxxxx.',

            'foto.image'                => 'Foto harus berupa file gambar.',
            'foto.mimes'                => 'Foto hanya boleh format JPG, JPEG, atau PNG.',
            'foto.max'                  => 'Ukuran foto maksimal 2 MB.',

            'institusi.required'        => 'Nama institusi (Sekolah / Universitas) wajib diisi.',
            'institusi.max'             => 'Nama institusi maksimal 200 karakter.',

            'jurusan.required'          => 'Jurusan / Program Studi wajib diisi.',
            'jurusan.max'               => 'Jurusan / Program Studi maksimal 150 karakter.',

            'semester.required'         => 'Semester wajib diisi untuk kategori Mahasiswa.',
            'semester.integer'          => 'Semester harus berupa angka bulat.',
            'semester.min'              => 'Semester minimal 1.',
            'semester.max'              => 'Semester maksimal 14.',

            'tahun_angkatan.required'   => 'Tahun angkatan wajib diisi.',
            'tahun_angkatan.max'        => 'Tahun angkatan maksimal 10 karakter.',
            'tahun_angkatan.regex'      => 'Tahun angkatan hanya boleh angka, garis miring, atau strip.',
        ];
    }
}
