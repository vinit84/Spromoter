<?php

namespace App\Http\Requests\Admin\Customers;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreCustomerRequest extends FormRequest
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
            'first_name' => ['required','string','max:50'],
            'last_name' => ['required','string','max:50'],
            'username' => ['required','string', 'max:255', 'unique:users,username'],
            'email' => ['required','email','unique:users,email', 'max:255'],
            'phone' => ['nullable','string','max:20'],
            'phone_code' => ['nullable','string','max:10'],
            'password' => ['required','string','min:8', 'max:100', Password::default()],
            'country' => ['nullable','string','max:50'],
            'status' => ['required','string','in:active,suspend'],
        ];
    }
}
