<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:8', 'max:255'],
            'collection_id' => ['nullable'],
            'start_date' => ['nullable', 'date', Rule::date()->afterOrEqual(now())],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'description' => ['required', 'min:8', 'string'],
        ];
    }
}
