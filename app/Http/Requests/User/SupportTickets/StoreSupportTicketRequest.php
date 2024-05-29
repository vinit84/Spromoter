<?php

namespace App\Http\Requests\User\SupportTickets;

use App\Models\SupportTicket;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupportTicketRequest extends FormRequest
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
            'department' => ['required', Rule::in(SupportTicket::DEPARTMENTS)],
            'priority' => ['required', Rule::in(SupportTicket::PRIORITIES)],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'message' => strip_tags($this->message, '<p><br><b><strong><i><em><u><ul><ol><li><a><img>'),
        ]);
    }
}
