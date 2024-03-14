<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class PostUpdateRequest extends FormRequest
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
            'title' => 'string|max:255',
            'text'  => 'string|max:65535',
            'image' => 'image|extensions:png,jpg,jpeg|max:512',
        ];
    }

    public function messages(): array
    {
        return [
            'image.extensions' => 'Доступные форматы: .png, .jpg, .jpeg',
            'image.max' => 'Изображение слишом большое. Максимальный вес 5Мб',
            'title.max' => 'Слишком длинное содержание',
            'text.max' => 'Слишком длинное содержание',
        ];
    }
}
