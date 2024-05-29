<?php

namespace App\Http\Requests\User\Profile;

use App\Helpers\Country;
use App\Helpers\TimeZones;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
            'phone' => ['required', 'string', 'max:15'],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'company' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'country' => ['required', 'string', 'max:255', Rule::in(collect(Country::get())->pluck('name')->toArray())],
            'language' => ['required', 'exists:languages,id,is_active,1'],
            'timezone' => ['required', 'string', 'max:255', Rule::in(collect(TimeZones::get())->keys()->toArray())],
        ];
    }
}
