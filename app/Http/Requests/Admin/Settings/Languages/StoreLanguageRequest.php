<?php

namespace App\Http\Requests\Admin\Settings\Languages;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that should be applied to the request.
     *
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:languages'],
            'code' => ['required', 'alpha_dash', 'max:255', 'unique:languages'],
            'is_active' => ['required', 'boolean'],
            'is_rtl' => ['required', 'boolean'],
        ];
    }
}
