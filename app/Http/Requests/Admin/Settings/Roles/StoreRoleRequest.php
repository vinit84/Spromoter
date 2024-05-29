<?php

namespace App\Http\Requests\Admin\Settings\Roles;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => ['required', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['nullable', 'exists:permissions,id'],
        ];
    }
}
