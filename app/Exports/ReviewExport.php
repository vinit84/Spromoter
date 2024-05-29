<?php

namespace App\Exports;

use App\Models\Review;
use App\Models\Store;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReviewExport implements FromCollection, WithHeadings, ShouldQueue
{
    use Exportable;

    public function __construct(protected Store $store)
    {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): \Illuminate\Support\Collection
    {
        return Review::whereStoreId($this->store->id)
        ->select([
            'product_id',
            'name',
            'title',
            'rating',
            'comment',
            'created_at',
        ])
            ->with(['product' => function ($query) {
                $query->select([
                    'id',
                    'name',
                    'url',
                    'specs',
                ]);
            }])
            ->get()
        ->map(function ($review) {
            return [
                'Item ID' => $review->product_id,
                'Item UPC' => $review->product->specs['SKU'] ?? null,
                'SKU' => $review->product->specs['SKU'] ?? null,
                'Review Title' => $review->title,
                'Review Body' => $review->comment,
                'Review Rating' => $review->rating,
                'Review Created Date' => $review->created_at,
                'Review User User' => $review->name,
                'URL Link' => $review->product->url,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Item ID',
            'Item UPC',
            'SKU',
            'Review Title',
            'Review Body',
            'Review Rating',
            'Review Created Date',
            'Review User Name',
            'URL Link'
        ];
    }
}
