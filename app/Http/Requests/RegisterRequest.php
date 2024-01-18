<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'login' => 'required|string|min:4|max:20|unique:App\Models\User,login',
            'password' => 'required|string|min:4|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'login.unique' => 'Этот логин уже занят',
        ];
    }
}
