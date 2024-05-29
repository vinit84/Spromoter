<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $store = Store::whereUuid($request->header('x-app-id'))->firstOrFail();
        $productIds = $request->input('products');

        $ratings = $store->products()
            ->select('id', 'unique_id')
            ->whereIn('unique_id', $productIds)
            ->with('reviews', function ($query) {
                $query->published();
            })
            ->get()
            ->mapWithKeys(function ($product) {
                $rating = $product->reviews->avg('rating');
                $count = $product->reviews->count();
                return [
                    $product->unique_id => [
                        'rating' => Number::format($rating, 1),
                        'count' => Number::abbreviate($count),
                    ]
                ];
            });

        return apiSuccess('Rating retrieved', $ratings);
    }
}
