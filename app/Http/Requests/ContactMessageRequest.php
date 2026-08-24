<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'phone'    => ['required', 'string', 'min:8', 'max:25'],
            'email'    => ['required', 'email', 'max:150'],
            'category' => ['required', 'string', 'in:mahasiswa,siswa,dosen_guru,lainnya'],
            'message'  => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'name.min'          => 'Nama lengkap minimal 3 karakter.',
            'phone.required'    => 'Nomor WhatsApp / telepon wajib diisi.',
            'phone.min'         => 'Nomor telepon minimal 8 digit.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format alamat email tidak valid.',
            'category.required' => 'Silakan pilih kategori peserta.',
            'category.in'       => 'Pilihan kategori peserta tidak valid.',
            'message.required'  => 'Pesan atau pertanyaan wajib diisi.',
            'message.min'       => 'Pesan minimal berisi 10 karakter.',
            'message.max'       => 'Pesan maksimal 2000 karakter.',
        ];
    }
}
