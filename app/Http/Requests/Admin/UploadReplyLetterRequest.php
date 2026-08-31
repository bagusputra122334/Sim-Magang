<?php

namespace App\Http\Requests\Admin;

use App\Models\Registration;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UploadReplyLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $registration = $this->route('registration') ?? $this->route('application');

        return [
            'surat_balasan' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
                function (string $attribute, mixed $value, Closure $fail) use ($registration): void {
                    if (! $registration instanceof Registration) {
                        return;
                    }
                    if (! $registration->isAccepted()) {
                        $fail('Surat Balasan hanya boleh diunggah untuk pendaftaran yang statusnya DITERIMA (Accepted). Ubah status terlebih dahulu.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'surat_balasan.required' => 'Berkas Surat Balasan PDF wajib diunggah.',
            'surat_balasan.file'     => 'Surat balasan harus berupa file yang diunggah.',
            'surat_balasan.mimes'    => 'Surat balasan wajib berformat PDF. Tidak menerima format DOCX / JPG.',
            'surat_balasan.max'      => 'Ukuran Surat Balasan maksimal 5 MB (5120 KB). Surat resmi biasanya 1-2 halaman, dibawah 5MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'surat_balasan' => 'Surat Balasan Dinas (PDF)',
        ];
    }
}
