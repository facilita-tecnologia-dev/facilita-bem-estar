<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use App\Enums\InternalUserRoleEnum;
use App\Rules\validateCPF;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::authorize('create', Auth::user())->allowed();
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
            'cpf' => ['required', 'string', new validateCPF],
            'birth_date' => ['required', 'date', Rule::date()->beforeOrEqual(today()->subYears(16)), Rule::date()->after(today()->subCenturies(1))],
            'gender' => ['required', 'string', Rule::enum(GenderEnum::class)],
            'department' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'admission' => ['required', 'date', Rule::date()->beforeOrEqual(today()), Rule::date()->after(today()->subCenturies(1))],
            'role' => ['required', 'string', Rule::enum(InternalUserRoleEnum::class)],
        ];
    }
}
