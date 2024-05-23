<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        //TODO неробит урод
        return [
            'title' => 'nullable|string|max:255',
            'text'  => 'nullable|string|max:65535',
            'image' => 'nullable|image|extensions:png,jpg,jpeg|max:512',
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
