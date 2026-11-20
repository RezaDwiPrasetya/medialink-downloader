<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi input URL.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'string',
                'url',
                'starts_with:http://,https://',
                'max:2048',
            ],
        ];
    }

    /**
     * Pesan kesalahan validasi dalam Bahasa Indonesia yang ramah.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'url.required' => 'URL media wajib diisi.',
            'url.url' => 'Format URL tidak valid.',
            'url.starts_with' => 'URL harus diawali dengan http:// atau https://.',
            'url.max' => 'Panjang URL melebihi batas 2048 karakter.',
        ];
    }
}
