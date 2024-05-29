<?php

namespace App\Http\Requests\Admin\Customers;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->route('customer')->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('customer')->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:active,suspend'],
        ];
    }
}
