<?php

namespace App\Http\Controllers\Admin\Store\Reviews;

use App\DataTables\User\Reviews\ModerationDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Review\Moderation\StoreCommentRequest;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ModerationController extends Controller
{
    public function index(ModerationDataTable $dataTable, Store $store)
    {
        $averageRating = $store->reviews()->avg('rating');
        $totalReviews = $store->reviews()->count();
        $thisWeekReviews = $store->reviews()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $storeReviews = $store->reviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();
        $positiveReviewsPercentage = nullSafeDivide($store->reviews()->where('rating', '>=', 4)->count(), $totalReviews, percentage: true);


        $previousDayReviews = $store->reviews()
            ->whereDate('created_at', today()->subDay())
            ->count();
        $newReviews = $store->reviews()
            ->whereDate('created_at', now())
            ->count();
        $newReviewsPercentage = nullSafeDivide(($newReviews - $previousDayReviews), $previousDayReviews) * 100;


        $products = $store->products()->get();

        return $dataTable->with([
            'store' => $store,
        ])->render('admin.stores.reviews.moderation.index', [
            'store' => $store,
            'products' => $products,

            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'thisWeekReviews' => $thisWeekReviews,
            'storeReviews' => $storeReviews,
            'percentageRatings' => $this->getPercentageRatings($storeReviews),
            'positiveReviewsPercentage' => $positiveReviewsPercentage,
            'newReviews' => $newReviews,
            'previousDayReviews' => $previousDayReviews,
            'newReviewsPercentage' => $newReviewsPercentage,
            'weeklyReviews' => $this->getWeeklyReviews($store),
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

    private function getWeeklyReviews($store){
        $weeklyReviewsCollection = $store->reviews()
            ->whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()])
            ->selectRaw('DATE_FORMAT(created_at, "%a") as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderByRaw('FIELD(day, "'.implode('", "', daysInWeek()).'")')
            ->pluck('count', 'day');

        $weeklyReviews = [];
        foreach (daysInWeek() as $day) {
            $weeklyReviews[$day] = $weeklyReviewsCollection[$day] ?? 0;
        }
        return collect($weeklyReviews);
    }

    public function changeStatus(Review $review, $status)
    {
        if (!in_array($status, ['rejected', 'published'])){
            return error(trans('Invalid status provided.'));
        }
        $review->update([
            'status' => $status,
        ]);

        return success(trans('Review status has been updated.'));
    }

    public function bulkPublish(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => ['required', 'array'],
            'ids.*' => [
                'required',
                Rule::exists('reviews', 'id')
                    ->where('store_id', activeStore()->id)
            ],
        ]);

        if ($validator->fails()) {
            if (count($request->input('ids')) < 1){
                return error(trans('Please select at least one review.'));
            }else{
                return error($validator->messages()->first());
            }
        }

        Review::whereIn('id', $request->input('ids'))->get()->map(function ($review){
            $review->update([
                'status' => 'published',
            ]);
        });

        return success(trans("Selected reviews have been published."));
    }

    public function bulkReject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => ['required', 'array'],
            'ids.*' => [
                'required',
                Rule::exists('reviews', 'id')
                    ->where('store_id', activeStore()->id)
            ],
        ]);

        if ($validator->fails()) {
            if (count($request->input('ids')) < 1){
                return error(trans('Please select at least one review.'));
            }else{
                return error($validator->messages()->first());
            }
        }

        Review::whereIn('id', $request->input('ids'))->get()->map(function ($review){
            $review->update([
                'status' => 'rejected',
            ]);
        });

        return success(trans("Selected reviews have been published."));
    }

    public function comment(StoreCommentRequest $request)
    {

        $review = Review::where([
            'id' => $request->input('review_id'),
            'store_id' => activeStore()->id,
        ])->firstOrFail();

        $comment = $review->comments()->create([
            'comment' => $request->input('comment'),
            'is_owner' => true,
        ]);

        return success(trans('Comment has been added.'), data: [
            'comment' => view('admin.stores.reviews.moderation.table.comment', compact('comment'))->render(),
        ]);
    }

    public function commentChangeStatus(ReviewComment $reply)
    {
        $reply->update([
            'is_public' => !$reply->is_public,
        ]);

        if ($reply->is_public){
            return success(trans('Comment status has been changed to public.'));
        }else{
            return success(trans('Comment status has been changed to private.'));
        }
    }
}
