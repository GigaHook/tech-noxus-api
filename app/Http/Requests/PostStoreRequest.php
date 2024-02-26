<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'title' => 'required|string|max:100',
            'text' => 'required|string|max:65535',
            //'image' => 'required|extensions:png,jpg,jpeg|max:5000'
            'images' => 'required|array',
            'images.*' => 'required|extensions:png,jpg,jpeg|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            //'image.extensions' => 'Неверный формат изображения. Доступные форматы: .png, .jpg, .jpeg',
            //'image.max' => 'Изображение слишом большое. Максимальный вес 5Мб',
            'title.max' => 'Слишком длинное содержание',
            'text.max' => 'Слишком длинное содержание',
            'images.*.max' => 'Одно из изображение слишом большое. Максимальный вес 5Мб',
            'images.*.extensions' => 'Неверный формат изображений. Доступные форматы: .png, .jpg, .jpeg'
        ];
    }
}