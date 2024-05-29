<?php

namespace App\Http\Requests\Admin\Stores;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
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
            'customer' => ['required', 'exists:users,id,group,customer'],
            'category' => ['required', 'exists:store_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255', 'unique:stores,url'],
        ];
    }
}
