<?php

namespace App\Http\Requests\Admin;

use App\Enums\RegistrationStatus;
use App\Models\Registration;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $registration = $this->route('registration');

        return [
            'status' => [
                'required',
                Rule::enum(RegistrationStatus::class),
                Rule::notIn([RegistrationStatus::Submitted->value]),
                function (string $attribute, mixed $value, Closure $fail) use ($registration): void {
                    if (! $registration instanceof Registration) {
                        return;
                    }
                    if ($registration->isAccepted() || $registration->isRejected()) {
                        $fail('Pendaftaran ini sudah final. Status tidak boleh diubah lagi (sudah diterima / ditolak).');
                    }
                },
            ],
            'catatan_admin' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'      => 'Status hasil verifikasi wajib dipilih.',
            'status.enum'         => 'Pilihan status tidak valid (hanya Under Review, Accepted, Rejected).',
            'status.not_in'       => 'Status tidak boleh Submitted (Anda sedang memverifikasi, bukan submit).',

            'catatan_admin.string' => 'Catatan admin harus berupa teks.',
            'catatan_admin.max'    => 'Catatan admin maksimal 5000 karakter (kira-kira 1 halaman A4).',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'          => 'Status Pendaftaran',
            'catatan_admin'   => 'Catatan Review Admin',
        ];
    }
}
