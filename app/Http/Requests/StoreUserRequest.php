<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "name" => "required|max:255",
            "cpf" => "required|max:255",
            "age" => "required|",
            "gender" => "required|max:255",
            "department" => "required|max:255",
            "occupation" => "required|max:255",
            "admission" => "nullable|date",
            "role" => "required"
        ];
    }
}
