<?php

namespace App\Http\Requests\User\Reviews\PublishSettings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class UpdatePublishSettingRequest extends FormRequest
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
            'auto_publish_reviews' => ['required', 'boolean'],
            'min_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'profane_words' => ['nullable', 'array'],
            'profane_words.*' => ['required', 'string'],
            'profane_send_email' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $words= json_decode($this->input('profane_words', '[]'), true);
        $words = Collection::make($words)->map(fn($word) => $word['value'])->toArray();

        $this->merge([
            'auto_publish_reviews' => $this->boolean('auto_publish_reviews'),
            'profane_words' => $words,
            'profane_send_email' => $this->boolean('profane_send_email'),
        ]);
    }
}
