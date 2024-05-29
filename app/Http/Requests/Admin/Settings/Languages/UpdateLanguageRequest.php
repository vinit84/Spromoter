<?php

namespace App\Http\Requests\Admin\Settings\Languages;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that should be applied to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('languages')->ignore($this->route('language')->id)],
            'is_active' => ['required', 'boolean'],
            'is_rtl' => ['required', 'boolean'],
        ];
    }
}
