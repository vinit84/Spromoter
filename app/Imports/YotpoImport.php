<?php

namespace App\Imports;

use App\Models\Review;
use Carbon\Carbon;
use ErrorException;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class YotpoImport extends Importer
{
    public function model(array $row)
    {
        if ($this->isConfirm) {
            $product = $this->store->products()
                ->updateOrCreate([
                    'unique_id' => $row['product_id'],
                ], [
                    'name' => $row['product_title'],
                    'price' => 0,
                    'specs' => [
                        'ean' => $row['product_ean'] ?? null,
                        'mpn' => $row['product_mpn'] ?? null,
                        'sku' => $row['product_sku'] ?? null,
                        'upc' => $row['product_upc'] ?? null,
                        'asin' => $row['product_asin'] ?? null,
                        'gtin' => $row['product_gtin'] ?? null,
                        'isbn' => $row['product_isbn'] ?? null,
                        'brand' => $row['product_brand'] ?? null,
                    ],
                    'image' => $row['product_image_url'],
                    'url' => $row['product_url'],
                    'description' => $row['product_description'],
                ]);

            $product->reviews()->updateOrCreate([
                'store_id' => $this->store->id,
                'unique_id' => $row['review_id'],
            ], [
                'name' => $row['reviewer_display_name'],
                'email' => $row['reviewer_email'],
                'title' => $row['review_title'],
                'comment' => $row['review_content'],
                'rating' => $row['review_score'],
                'source' => 'yotpo',
                'collect_from' => 'import',
                'is_verified' => true,
                'is_approved' => true,
                'is_purchased' => isset($row['order_id']),
                'status' => $row['review_status'] == 'Published' ? Review::STATUS_PUBLISHED : Review::STATUS_PENDING,
                'created_at' => $row['review_creation_date'] ?? now(),
                'updated_at' => $row['review_creation_date'] ?? now(),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer'],
            'product_title' => ['required', 'string'],
            'product_description' => ['nullable', 'string'],
            'product_url' => ['nullable', 'string'],
            'product_image_url' => ['nullable', 'string'],
            'product_upc' => ['nullable'],
            'product_sku' => ['nullable'],
            'product_mpn' => ['nullable'],
            'product_isbn' => ['nullable'],

            'review_id' => ['required', 'numeric'],
            'review_title' => ['nullable', 'string'],
            'review_content' => ['required', 'string'],
            'review_score' => ['required', 'integer', 'min:1', 'max:5'],
            'review_creation_date' => ['required', 'date'],
            'review_status' => ['required'],

            'reviewer_display_name' => ['required', 'string'],
            'reviewer_email' => ['nullable', 'email'],

        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['review_creation_date'] = isset($data['review_creation_date']) ? $this->transformDate($data['review_creation_date']) : null;

        return $data;
    }

    private function transformDate($date): string
    {
        try {
            return Carbon::instance(Date::excelToDateTimeObject($date))
                ->toDateTimeString();
        } catch (ErrorException $e) {
            return $date;
        }
    }
}
