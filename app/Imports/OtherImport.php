<?php

namespace App\Imports;

use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class OtherImport extends Importer
{
    public function model(array $row): void
    {
        $product = $this->store->products()
            ->whereJsonContains('specs.upc', $row['product_upcsku'])
            ->orWhereJsonContains('specs.sku', $row['product_upcsku'])
            ->firstOrCreate([
                'unique_id' => Str::random(10) . '-' . now()->timestamp,
                'name' => null,
                'price' => 0,
                'specs' => [
                    'upc' => $row['product_upcsku'] ?? null,
                    'sku' => $row['product_upcsku'] ?? null,
                    'mpn' => null,
                    'gtin' => null,
                    'ean' => null,
                    'isbn' => null,
                    'asin' => null,
                ],
                'image' => null,
                'url' => null,
                'description' => null,
            ]);

        $product->reviews()->updateOrCreate([
            'store_id' => $this->store->id,
            'unique_id' => Str::uuid(),
            'name' => $row['display_name'],
            'email' => null,
            'title' => $row['review_title'],
            'comment' => $row['review_content'],
            'rating' => $row['review_score'],
            'source' => 'other',
            'collect_from' => 'import',
            'is_verified' => true,
            'is_approved' => true,
            'is_purchased' => false,
            'status' => Review::STATUS_PUBLISHED,
            'attachments' => null,
            'created_at' => $row['review_date'],
            'updated_at' => $row['review_date'],
        ]);
    }

    public function rules(): array
    {
        return [
            'product_upcsku' => ['required'],
            'review_title' => ['required', 'string'],
            'review_content' => ['required', 'string'],
            'review_score' => ['required', 'numeric', 'min:1', 'max:5'],
            'review_date' => ['required', 'date'],

            'display_name' => ['required', 'string'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['review_date'] = isset($data['review_date']) ? $this->transformDate($data['review_date']) : null;

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
