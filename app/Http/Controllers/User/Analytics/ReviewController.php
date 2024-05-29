<?php

namespace App\Http\Controllers\User\Analytics;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $store = activeStore();

        $storeReviews = $store->reviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $geoGraphicReviews = $store->reviews()
            ->selectRaw('location->>"$.country" as country, COUNT(*) as count, location->>"$.iso_code" as iso_code')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->mapWithKeys(function ($item) {
                return [strtolower($item->iso_code) => [
                    'count' => $item->count,
                    'country' => $item->country,
                ]];
            });

        return view('user.analytics.reviews.index', [
            'store' => $store,
            'storeReviews' => $storeReviews,
            'percentageRatings' => $this->getPercentageRatings($storeReviews),
            'geoGraphicReviews' => $geoGraphicReviews,
            'deviceReviewsPercentage' => $this->getDeviceReviewsPercentage($store),
        ]);
    }

    public function reviewsChart(Request $request)
    {
        $store = activeStore();

        $dateRange = $request->get('date_range', today()->subDays(30).' - '.today()->endOfDay());
        $dateRange = explode(' - ', $dateRange);
        $startDate = Carbon::parse($dateRange[0] ?? $store->created_at)->startOfDay();
        $endDate = Carbon::parse($dateRange[1])->endOfDay();

        $ratings = $request->get('ratings', []);

//        if ($startDate->isAfter($endDate)) {
//            return error(trans('The start date must be before the end date.'));
//        }
//
//        if ($startDate->isBefore($store->created_at)){
//            return error(trans('The start date must be after the store was created.'));
//        }
//
//        if ($endDate->isAfter(today()->endOfDay())){
//            return error(trans('The end date must be before today.'));
//        }

        $reviews = $store->reviews()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('rating', $ratings)
            ->orderBy('date')
            ->groupBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->count];
            });

        return response()->json([
            'dates' => $reviews->keys(),
            'reviews' => $reviews->values(),
        ]);
    }

    private function getPercentageRatings($storeReviews)
    {
        $percentageRatings = [];

        for ($i = 1; $i <= 5; $i++) {
            $count = $storeReviews[$i] ?? 0;

            if ($count > 0) {
                $percentage = $count / array_sum($storeReviews) * 100;
                $percentageRatings[$i] = round($percentage, 2);
            } else {
                // Set the percentage to 0 if there are no reviews for this rating.
                $percentageRatings[$i] = 0;
            }
        }

        return $percentageRatings;
    }

    private function getDeviceReviewsPercentage($store)
    {
        $deviceReviews = $store->reviews()
            ->selectRaw('device->>"$.device_type" as device_type, COUNT(*) as count')
            ->whereRaw('device->>"$.device_type" IS NOT NULL')
            ->groupBy('device_type')
            ->get();

        $totalReviews = $deviceReviews->sum('count');

        return $deviceReviews
            ->mapWithKeys(function ($item) use ($totalReviews) {
                $percentage = ($item->count / $totalReviews) * 100;
                return [str($item->device_type)->title()->value() => round($percentage, 2)];
            });
    }
}
