<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignStoreRequest extends FormRequest
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
            'collection_id' => ['required'],
            'start_date' => ['required', 'date', Rule::date()->afterOrEqual(today())],
            'end_date' => ['required', 'date', 'after:start_date'],
            'description' => ['required', 'min:8', 'string'],
        ];
    }
}
