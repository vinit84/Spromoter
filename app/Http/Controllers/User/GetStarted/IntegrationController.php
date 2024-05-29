<?php

namespace App\Http\Controllers\User\GetStarted;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IntegrationController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            'X-GitHub-Api-Version' => '2022-11-28'
        ])
            ->withToken(env('GITHUB_TOKEN'))
            ->get('https://api.github.com/repos/bishwajitcadhikary/spromoter-social-reviews-for-woocommerce/releases/latest');

        $pluginDownloadUrl = "https://github.com/bishwajitcadhikary/spromoter-social-reviews-for-woocommerce/releases/download/".$response->json('tag_name')."/spromoter-social-reviews-for-woocommerce.zip";

        return view('user.get-started.integration.index', [
            'pluginDownloadUrl' => $pluginDownloadUrl,
        ]);
    }

    public function finish()
    {
        activeStore()->update([
            'is_integrated' => 1,
        ]);

        return to_route('user.dashboard.index');
    }
}
