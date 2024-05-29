<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
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
            'order_id' => ['required', 'string'],
            'customer_name' => ['required', 'string'],
            'customer_email' => ['required', 'email'],
            'order_date' => ['required', 'date'],
            'currency' => ['nullable', 'string'],

            'subtotal' => ['nullable', 'numeric'],
            'subtotal_tax' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
            'total_tax' => ['nullable', 'numeric'],
            'taxes' => ['nullable', 'array'],


            'platform' => ['nullable', 'string', 'in:woocommerce'],
            'status' => ['nullable', 'string'],
            'data' => ['nullable', 'array'],

            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'string'],
            'items.*.name' => ['required', 'string'],
            'items.*.image' => ['nullable', 'string'],
            'items.*.url' => ['nullable', 'string'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['nullable', 'numeric'],
            'items.*.price' => ['nullable', 'numeric'],
            'items.*.specs' => ['nullable', 'array'],
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
