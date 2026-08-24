<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'nip' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique(User::class, 'nip')->ignore($this->user()->id),
            ],
            'position_title' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $nip = $this->has('nip') ? trim((string) $this->input('nip')) : null;
        $positionTitle = $this->has('position_title') ? trim((string) $this->input('position_title')) : null;

        $mergeData = [];

        if ($nip !== null) {
            $mergeData['nip'] = $nip !== '' ? $nip : null;
        }

        if ($positionTitle !== null) {
            $mergeData['position_title'] = $positionTitle !== '' ? $positionTitle : null;
        }

        if (! empty($mergeData)) {
            $this->merge($mergeData);
        }
    }
}
