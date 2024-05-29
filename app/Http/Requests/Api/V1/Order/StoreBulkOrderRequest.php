<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBulkOrderRequest extends FormRequest
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
            'orders.*.order_id' => ['required', 'string'],
            'orders.*.customer_name' => ['required', 'string'],
            'orders.*.customer_email' => ['required', 'email'],
            'orders.*.order_date' => ['required', 'date'],
            'orders.*.currency' => ['nullable', 'string'],

            'orders.*.subtotal' => ['nullable', 'numeric'],
            'orders.*.subtotal_tax' => ['nullable', 'numeric'],
            'orders.*.total' => ['nullable', 'numeric'],
            'orders.*.total_tax' => ['nullable', 'numeric'],
            'orders.*.taxes' => ['nullable', 'array'],


            'orders.*.platform' => ['nullable', 'string', 'in:woocommerce'],
            'orders.*.status' => ['nullable', 'string'],
            'orders.*.data' => ['nullable', 'array'],

            'orders.*.items' => ['required', 'array'],
            'orders.*.items.*.id' => ['required', 'string'],
            'orders.*.items.*.name' => ['required', 'string'],
            'orders.*.items.*.image' => ['nullable', 'string'],
            'orders.*.items.*.url' => ['nullable', 'string'],
            'orders.*.items.*.description' => ['nullable', 'string'],
            'orders.*.items.*.quantity' => ['nullable', 'numeric'],
            'orders.*.items.*.price' => ['nullable', 'numeric'],
            'orders.*.items.*.specs' => ['nullable', 'array'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => false,
            'message' => trans('The given data was invalid'),
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }
}
