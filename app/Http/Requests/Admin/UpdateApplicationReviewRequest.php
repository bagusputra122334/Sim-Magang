<?php

namespace App\Http\Requests\Admin;

use App\Enums\RegistrationStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class UpdateApplicationReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->user();

        return $user !== null && $user->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        $statusRaw = $this->input('status');
        $catatanRaw = $this->input('catatan_admin');

        $this->merge([
            'status'        => is_string($statusRaw) ? strtolower(trim($statusRaw)) : $statusRaw,
            'catatan_admin' => is_string($catatanRaw) ? trim($catatanRaw) : $catatanRaw,
        ]);
    }

    /** @return array<string, array<int, string|\BackedEnum|RequiredIf|Rule>> */
    public function rules(): array
    {
        $isRejected = $this->string('status')->toString() === RegistrationStatus::Rejected->value;

        return [
            'status' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    RegistrationStatus::Accepted->value,
                    RegistrationStatus::Rejected->value,
                ]),
            ],
            'catatan_admin' => [
                'bail',
                Rule::requiredIf($isRejected),
                'nullable',
                'string',
                'max:1000',
                ...($isRejected ? ['min:10'] : []),
            ],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'status'        => 'Status Verifikasi',
            'catatan_admin' => 'Catatan Admin',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'status.required'                => 'Status verifikasi wajib dipilih.',
            'status.in'                      => 'Status hanya boleh Accepted (Diterima) atau Rejected (Ditolak).',
            'catatan_admin.required_if'      => 'Catatan Admin WAJIB diisi (minimal 10 karakter) jika status = Rejected (Ditolak).',
            'catatan_admin.min'              => 'Catatan Admin minimal 10 karakter.',
            'catatan_admin.max'              => 'Catatan Admin maksimal 1000 karakter.',
        ];
    }
}
