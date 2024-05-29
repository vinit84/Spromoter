<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function index()
    {
        $reviews = activeStore()->reviews()->newQuery();

        $overview = [
            'overviewReviewRequestsSent' => 0,
            'overviewReviewsCollected' => Number::abbreviate($reviews->count(), 2),
            'overviewReviewsPublished' => Number::abbreviate($reviews->published()->count()),
            'overviewAverageRating' => $reviews->published()->avg('rating') ?? 0,
        ];

        return view('user.dashboard.index', [
            ...$overview,
        ]);
    }
}
