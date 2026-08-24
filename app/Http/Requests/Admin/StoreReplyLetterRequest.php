<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreReplyLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->user();

        return $user !== null && $user->isAdmin();
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'surat_balasan' => [
                'bail',
                'required',
                'file',
                'mimes:pdf',
                'max:2048',
            ],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'surat_balasan' => 'Surat Balasan',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'surat_balasan.required' => 'File Surat Balasan WAJIB dipilih.',
            'surat_balasan.file'     => 'Surat Balasan harus berupa file upload yang valid.',
            'surat_balasan.mimes'    => 'Format Surat Balasan HANYA BOLEH berupa file PDF (.pdf).',
            'surat_balasan.max'      => 'Ukuran Surat Balasan MAKSIMAL 2048 KB (2 MB).',
        ];
    }
}
