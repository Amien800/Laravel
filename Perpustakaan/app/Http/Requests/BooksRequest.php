<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BooksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'summary' => 'required',
            'image' => 'nullable|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required',
            'category_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Title Wajib Di Isi',
            'summary.required' => 'Summary Wajib Di Isi',
            'stok.required' => 'Stok Wajib Di Isi',
            'category_id.required' => 'Category Wajib Di Isi',
            'title.max' => 'Title maksimal 255 Karakter',
            'image.mimes' =>
                'Image Harus Berupa File dengan Format PNG,JPG dan BMP',
            'image.max' => 'Ukuran File tidak boleh lebih dari 2 MB',
        ];
    }
}
