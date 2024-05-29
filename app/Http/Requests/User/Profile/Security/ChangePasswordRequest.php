<?php

namespace App\Http\Requests\User\Profile\Security;

use App\Rules\MatchPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', new MatchPassword()],
            'password' => ['required', Password::default()->letters()->numbers()->symbols()->uncompromised(), 'confirmed'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'new_password.*' => trans('The password must be at least 8 characters long, contain at least one letter, one number and one special character.'),
        ];
    }
}
