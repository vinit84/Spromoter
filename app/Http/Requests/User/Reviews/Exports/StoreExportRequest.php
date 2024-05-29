<?php

namespace App\Http\Requests\User\Reviews\Exports;

use App\Rules\DateRangeRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExportRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:csv,xlsx'],
            'date_range' => ['nullable', 'string', new DateRangeRule()],
            'email' => ['required', 'email'],
        ];
    }
}
