<?php

namespace App\Http\Requests\Api\V1\Reviews;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            "product_id" => ['required', 'string'],
            "product_title" => ['required', 'string'],
            "product_image_url" => ['required', 'string'],
            "product_url" => ['required', 'url'],

            "product_specs" => ['nullable', 'array'],
            "product_specs.*" => ['nullable', 'in:sku,upc,isbn,brand,mpn'],
            "product_specs.sku" => ['nullable', 'string'],
            "product_specs.upc" => ['nullable', 'string'],
            "product_specs.isbn" => ['nullable', 'string'],
            "product_specs.brand" => ['nullable', 'string'],
            "product_specs.mpn" => ['nullable', 'string'],


            "name" => ['required', 'string', 'max:255'],
            "email" => ['required', 'email', 'max:255'],
            "title" => ['required', 'string', 'max:255'],
            "comment" => ['required', 'string'],
            "rating" => ['required', 'integer', 'min:1', 'max:5'],
            "files" => ['nullable', 'array', 'max:20'],
            "files.*" => ['nullable', 'file', 'max:10240', "mimes:jpg,jpeg,png,mp4,avi"],


            "source" => ['required', 'string', 'in:woocommerce,shopify,magento,bigcommerce,custom'],
            "collect_from" => 'required|string|in:unknown,import,automatic_review_request,widget,facebook_tab,dedicated_page,link_to_preview',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "product_specs" => json_decode($this->input('product_specs'), true),
            "files" => is_array($this->input('files'))
                ? $this->input('files')
                : json_decode($this->input('files'), true),
        ]);
    }
}
