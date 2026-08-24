<?php

namespace App\Http\Requests\Admin;

use App\Enums\RegistrationStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class UpdateRegistrationReviewRequest extends FormRequest
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
                Rule::requiredIf($this->string('status')->toString() === RegistrationStatus::Rejected->value),
                'nullable',
                'string',
                Rule::when($this->string('status')->toString() === RegistrationStatus::Rejected->value, [
                    'min:10',
                    'max:1500',
                ], [
                    'max:1500',
                ]),
            ],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'status'        => 'Keputusan Status Verifikasi',
            'catatan_admin' => 'Catatan Admin',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'status.required'                => 'Wajib memilih keputusan verifikasi: Accepted atau Rejected.',
            'status.in'                      => 'Keputusan tidak valid. Hanya Accepted atau Rejected yang diperbolehkan.',
            'catatan_admin.required_if'      => 'Catatan Admin WAJIB DIISI jika Anda memilih Rejected (Ditolak). Jelaskan alasan penolakan.',
            'catatan_admin.string'           => 'Catatan Admin harus berupa teks yang jelas.',
            'catatan_admin.min'              => 'Catatan Admin minimal 10 karakter. Jelaskan alasan penolakan dengan rinci.',
            'catatan_admin.max'              => 'Catatan Admin maksimal 1500 karakter.',
        ];
    }
}
