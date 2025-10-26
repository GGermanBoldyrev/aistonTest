<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class AttachmentUploadRequest extends FormRequest
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
            'file' => [
                'required',
                File::types(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'])
                    ->max(10 * 1024),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Файл обязателен для загрузки.',
            'file.max' => 'Максимальный размер файла — 10 МБ.',
            'file.mimes' => 'Разрешены только файлы jpg, jpeg, png, pdf, doc, docx.',
        ];
    }
}
