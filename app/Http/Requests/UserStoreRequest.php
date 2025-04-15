<?php

namespace App\Http\Requests;

use App\Rules\validateCPF;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:255',
            'cpf' => ['required', 'unique:users', 'string', new validateCPF],
            'age' => 'required|integer|min:18|max:120',
            'gender' => 'required|string',
            'department' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'admission' => 'required|date',
            'role' => 'required|string|in:internal-manager,employee',
        ];
    }
}
