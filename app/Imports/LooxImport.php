<?php

namespace App\Imports;

use App\Models\Review;
use Str;

class LooxImport extends Importer
{
    public function model(array $row)
    {
        $product = $this->store->products()
            ->updateOrCreate([
                'unique_id' => $row['productid'],
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

        $product->reviews()->updateOrCreate([
            'store_id' => $this->store->id,
            'unique_id' => $row['id'] ?? Str::random() . '-' . now()->timestamp,
        ], [
            'name' => $row['nickname'],
            'email' => null,
            'title' => null,
            'comment' => $row['review'],
            'rating' => $row['rating'],
            'source' => 'loox',
            'collect_from' => 'import',
            'is_verified' => true,
            'is_approved' => true,
            'is_purchased' => isset($row['verified_purchase']),
            'status' => $row['status'] == 'Active' ? Review::STATUS_PUBLISHED : Review::STATUS_PENDING,
        ]);
    }

    public function rules(): array
    {
        return [
            'productid' => ['required', 'numeric'],

            'id' => ['nullable', 'string'],
            'review' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:Active,Inactive'],

            'nickname' => ['required', 'string'],
            'email' => ['nullable', 'email'],
        ];
    }
}
