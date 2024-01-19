<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class PostStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:65535',
            'image' => 'required|image|extensions:png,jpg,jpeg|max:512'
        ];
    }

    public function messages(): array
    {
        return [
            'image.extensions' => 'Неверный формат изображения. Доступные форматы: .png, .jpg, .jpeg',
            'image.max' => 'Изображение слишом большое. Максимальный вес 5 Мб',
            'title.max' => 'Слишком длинное содержание',
            'text.max' => 'Слишком длинное содержание',
        ];
    }
}
