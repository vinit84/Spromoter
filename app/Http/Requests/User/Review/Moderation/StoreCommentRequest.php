<?php

namespace App\Http\Requests\User\Review\Moderation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'review_id' => ['required', 'exists:reviews,id'],
            'comment' => ['required', 'string', 'max:500'],
        ];
    }
}
