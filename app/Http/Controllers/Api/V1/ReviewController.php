<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Reviews\StoreReviewRequest;
use App\Mail\ConfirmReviewRequestEmail;
use App\Mail\SpamReviewDetectedEmail;
use App\Models\OrderEmail;
use App\Models\Product;
use App\Models\Review;
use App\Models\Store;
use App\Notifications\NewNotification;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Number;
use Jenssegers\Agent\Agent;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $store = Store::whereUuid($request->header('x-app-id'))->firstOrFail();

        $search = $request->input('search');
        $rating = $request->input('rating');
        $orderBy = $request->input('order_by', 'latest');
        $productId = $request->input('product_id');
        $specs = $request->input('specs') ?? [];

        if ($specs){
            $specs = json_decode($specs, true);
        }

        $reviewQuery = $store->reviews()
            ->whereHas('product', function ($query) use ($productId, $specs) {
                $query->uniqueId($productId);

                foreach ($specs as $key => $value) {
                    $query->orWhereJsonContains('specs->'.$key, $value);
                }
            })
            ->published()
            ->newQuery();

        $reviews = $reviewQuery
            ->when($search, function (Builder $query, $search) {
                $keywords = explode(' ', $search);
                return $query->where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->where('title', 'like', "%{$keyword}%")
                            ->orWhere('comment', 'like', "%{$keyword}%");
                    }
                });
            })
            ->when($rating, function (Builder $query, $rating) {
                return $query->where('rating', $rating);
            })
            ->when($orderBy, function (Builder $query, $orderBy) {
                if ($orderBy == 'latest') {
                    return $query->latest();
                }

                return $query->oldest();
            })
            ->paginate(5);

        $reviewData = $reviews
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'name' => $review->name,
                    'avatar' => "https://api.dicebear.com/7.x/initials/svg?seed=$review->name&radius=50&backgroundColor=c0aede",
                    'comment' => $review->comment,
                    'rating' => $review->rating,
                    'attachments' => $review->attachments,
                    'created_at' => dateFormat($review->created_at),
                    'product_id' => $review->product_id,
                    'is_verified' => $review->is_verified,
                ];
            });

        $totalReviews = $reviewQuery->count();
        $averageRating = $reviewQuery->avg('rating');

        return apiSuccess('', [
            'reviews' => $reviewData->toArray(),
            'has_more' => $reviews->hasMorePages(),
            'total_reviews' => Number::abbreviate($totalReviews ?? 0),
            'average_rating' => Number::format($averageRating ?? 0, 1),
        ]);
    }

    /**
     * @throws Exception
     */
    public function store(StoreReviewRequest $request)
    {
        $store = Store::whereUuid($request->header('x-app-id'))->firstOrFail();
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());

        $ip = $request->ip();
        $token = config('services.ipinfo_token');

        $location = Http::get("https://ipinfo.io/$ip?token=$token")->json();

        try {
            $product = Product::updateOrCreate([
                "store_id" => $store->id,
                "unique_id" => $request->input('product_id'),
            ], [
                "name" => $request->input('product_title'),
                "specs" => $request->validated('product_specs'),
                "image" => $request->input('product_image_url'),
                "url" => $request->input('product_url'),
            ]);

            $review = Review::create([
                "store_id" => $store->id,
                "user_id" => $store->user_id,
                "product_id" => $product->id,
                "name" => $request->input('name'),
                "email" => $request->input('email'),
                "title" => $request->input('title'),
                "comment" => $request->input('comment'),
                "rating" => $request->input('rating'),
                "source" => $request->input('source'),
                "collect_from" => $request->input('collect_from'),
                "agent" => $request->userAgent(),
                "device" => [
                    'os' => $agent->platform(),
                    'os_version' => $agent->version($agent->platform()),
                    'browser' => $agent->browser(),
                    'browser_version' => $agent->version($agent->browser()),
                    'device' => $agent->device(),
                    'device_type' => $agent->deviceType(),
                    'robot' => $agent->robot(),
                    'languages' => $agent->languages(),
                ],
                "location" => $location,
                "is_verified" => false,
                "is_approved" => false,
                "status" => $this->getStatus($store, $request->input('comment'), $request->input('rating')),
            ]);

            if ($request->validated('files')) {
                $attachments = [];
                foreach($request->validated('files') as $file){
                    $media = $review->addMedia($file)
                        ->toMediaCollection('review-files');

                    $attachments[] = [
                        'type' => $media->mime_type,
                        'url' => $media->getFullUrl(),
                    ];
                }

                $review->update([
                    'attachments' => $attachments,
                ]);
            }

            Mail::to($review->email, $review->name)
                ->send(new ConfirmReviewRequestEmail($review));

            if ($review->status == Review::STATUS_SPAM){
                $store->user->notify(new NewNotification(
                    message: 'A new review has been detected as spam.',
                    url: route('user.reviews.moderation.index'),
                    icon: 'ti ti-bug',
                    type: 'error',
                    isMail: $store->setting('publish.profane_send_email', false),
                ));
            }

            DB::commit();

            return apiSuccess(trans('Review stored successfully'), [
                'id' => $review->id,
                'name' => $review->name,
                'avatar' => "https://api.dicebear.com/7.x/initials/svg?seed=$review->name&radius=50&backgroundColor=c0aede",
                'comment' => $review->comment,
                'rating' => $review->rating,
                'attachments' => $review->attachments,
                'created_at' => dateFormat($review->created_at),
            ]);
        }catch (Exception $exception) {
            DB::rollBack();
            return apiError($exception->getMessage());
        }
    }

    public function media(Request $request, $media, $fileName = null)
    {
        $media = Media::where('uuid', $media)->firstOrFail();
        return $media->toInlineResponse($request);
    }

    public function createFromEmail(Request $request, Store $store, OrderEmail $email)
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());

        $ip = $request->ip();
        $token = config('app.ipinfo_token');

        $location = Http::get("https://ipinfo.io/$ip?token=$token")->json();

        $review = Review::create([
            "store_id" => $store->id,
            "user_id" => $store->user_id,
            "product_id" => $email->item->product_id,
            "name" => $email->order->customer_name,
            "email" => $email->order->customer_email,
            "title" => '',
            "comment" => $request->input('comment'),
            "rating" => $request->input('review_score'),
            "source" => 'email',
            "collect_from" => 'email',
            "agent" => $request->userAgent(),
            "device" => [
                'os' => $agent->platform(),
                'os_version' => $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'device' => $agent->device(),
                'device_type' => $agent->deviceType(),
                'robot' => $agent->robot(),
                'languages' => $agent->languages(),
            ],
            "location" => $location,
            "is_verified" => false,
            "is_approved" => false,
            "is_purchased" => true,
            "status" => $this->getStatus($store, $request->input('comment'), $request->input('review_score')),
        ]);

        return $review;
    }

    private function getStatus(Store $store, $comment, $rating){
        $profaneWords = $store->profaneWords()->pluck('word')->toArray();
        $autoPublish = $store->setting('publish.auto_publish_reviews', false);
        $minRating = $store->setting('publish.min_rating', 5);

        foreach ($profaneWords as $word){
            if (str_contains(strtolower($comment), strtolower($word))){
                return Review::STATUS_SPAM;
            }
        }

        if ($rating >= $minRating && $autoPublish){
            return Review::STATUS_PUBLISHED;
        }

        return Review::STATUS_PENDING;
    }
}
