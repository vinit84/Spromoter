<?php

namespace App\Imports;

use App\Models\Review;
use Illuminate\Support\Str;

class JudgemeImport extends Importer
{
    public function model(array $row)
    {
        $product = $this->store->products()
            ->updateOrCreate([
                'unique_id' => $row['product_id'],
            ], [
                'name' => '',
                'price' => 0,
                'specs' => [
                    'ean' => null,
                    'mpn' => null,
                    'sku' => null,
                    'upc' => null,
                    'asin' => null,
                    'gtin' => null,
                    'isbn' => null,
                    'brand' => null,
                ],
                'image' => null,
                'url' => null,
                'description' => null,
            ]);

        $review = $product->reviews()->updateOrCreate([
            'store_id' => $this->store->id,
        ], [
            'unique_id' => $row['id'] ?? Str::random() . '-' . now()->timestamp,
            'name' => $row['reviewer_name'],
            'email' => $row['reviewer_email'],
            'title' => $row['title'],
            'comment' => $row['body'],
            'rating' => $row['rating'],
            'source' => 'judgeme',
            'collect_from' => 'import',
            'is_verified' => true,
            'is_approved' => true,
            'is_purchased' => false,
            'status' => Review::STATUS_PUBLISHED,
        ]);

        if (isset($row['reply'])) {
            $review->comments()->create([
                'is_owner' => true,
                'comment' => $row['reply'],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'numeric'],

            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_date' => ['required', 'date'],

            'reviewer_name' => ['required', 'string'],
            'reviewer_email' => ['required', 'email'],

            'reply' => ['nullable', 'string'],
        ];
    }
}
