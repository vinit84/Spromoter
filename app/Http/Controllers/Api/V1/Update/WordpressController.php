<?php

namespace App\Http\Controllers\Api\V1\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WordpressController extends Controller
{
    public function latestVersionInfo()
    {
        $response = Cache::remember('wordpress_latest_version_info', config('app.cache_ttl'), function () {
            return Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'X-GitHub-Api-Version' => '2022-11-28'
            ])
                ->withToken(config('app.github_token'))
                ->get('https://api.github.com/repos/bishwajitcadhikary/spromoter-social-reviews-for-woocommerce/releases/latest')
                ->json();
        });

        if (!isset($response['tag_name'])){
            return apiError('Something went wrong. Please try again later.', 500);
        }


        return response()->json([
            "name" => "SPromoter Social Reviews for WooCommerce",
            "slug" => "spromoter-social-reviews-for-woocommerce",
            "author" => "<a href='https://reviews.spromoter.com'>SPromoter</a>",
            "author_profile" => "https://reviews.spromoter.com",
            "donate_link" => "https://buymeacoffee.com/bishwajitca",
            "version" => str($response['tag_name'])->replace('v', ''),
            "download_url" => "https://github.com/bishwajitcadhikary/spromoter-social-reviews-for-woocommerce/releases/download/".$response['tag_name']."/spromoter-social-reviews-for-woocommerce.zip",
            "requires" => "5.6",
            "tested" => "6.4.2",
            "requires_php" => "5.6",
            "added" => $response['created_at'],
            "last_updated" => $response['published_at'],
            "homepage" => "https://reviews.spromoter.com",
            "sections" => [
                "description" => "SPromoter Social Reviews for WooCommerce helps you to collect and display reviews from your customers on your WooCommerce store.",
                "installation" => "Click the activate button and that's it.",
                "changelog" => "<h4>v1.0.1 released on January 24, 2024</h4><ul><li>Feature: Free for everyone. Removed EDD intergration. Automatic updates integrated with Github release.</li><li>Improvement: Add settings link to plugin page.</li></ul>"
            ],
            "banners" => [
                "low" => asset('assets/img/wp/banner-772x250.png'),
                "high" => asset('assets/img/wp/banner-1544x500.png')
            ]
        ]);
    }

    public function latestVersionDownload()
    {

    }
}
