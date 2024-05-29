<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function confirmReviewRequest(Review $review)
    {
        abort_if($review->status == Review::STATUS_SPAM || $review->is_verified != false, 404);

        $review->update([
            'status' => Review::STATUS_SPAM ? $review->status : Review::STATUS_PUBLISHED,
            'is_verified' => true,
        ]);

        return view('emails.confirm-review-request', [
            'review' => $review,
        ]);
    }
}
