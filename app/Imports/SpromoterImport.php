<?php

namespace App\Imports;

use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class SpromoterImport extends Importer
{
    public function model(array $row)
    {
        $product = $this->store->products()
            ->updateOrCreate([
                'unique_id' => $row['item_id'],
            ], [
                'name' => null,
                'price' => 0,
                'specs' => [
                    'sku' => $row['sku'] ?? null,
                    'upc' => $row['item_upc'] ?? null,
                ],
                'image' => null,
                'url' => $row['url_link'] ?? null,
                'description' => null,
            ]);

        $review = $product->reviews()->updateOrCreate([
            'store_id' => $this->store->id,
            'unique_id' => Str::random(10) . '-' . now()->timestamp,
        ], [
            'name' => $row['review_user_name'],
            'email' => null,
            'title' => $row['review_title'],
            'comment' => $row['review_body'],
            'rating' => $row['review_rating'],
            'source' => 'spromoter',
            'collect_from' => 'import',
            'is_verified' => true,
            'is_approved' => true,
            'is_purchased' => false,
            'status' => Review::STATUS_PUBLISHED,
            'attachments' => null,
            'created_at' => $row['review_created_date'],
            'updated_at' => $row['review_created_date'],
        ]);
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required'],
            'item_upc' => ['nullable'],
            'sku' => ['nullable'],
            'review_title' => ['required', 'string'],
            'review_body' => ['required', 'string'],
            'review_rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'review_created_date' => ['required', 'date'],

            'review_user_name' => ['required', 'string'],

            'url_link' => ['nullable', 'url'],
        ];
    }


    public function prepareForValidation($data, $index)
    {
        $data['review_created_date'] = isset($data['review_created_date']) ? $this->transformDate($data['review_created_date']) : null;

        return $data;
    }

    private function transformDate($date): string
    {
        if (!is_numeric($date)) {
            return Carbon::parse($date)->toDateTimeString();
        }

        try {
            return Carbon::instance(Date::excelToDateTimeObject($date))
                ->toDateTimeString();
        } catch (Throwable $e) {
            return $date;
        }
    }
}
