<?php

namespace App\Http\Controllers\Admin\Store\Reviews;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Reviews\PublishSettings\UpdatePublishSettingRequest;
use App\Models\Store;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\DB;

class PublishSettingController extends Controller
{
    public function index(Store $store)
    {
        $settings = StoreSetting::getSettings($store, [
            'publish.auto_publish_reviews',
            'publish.min_rating',
            'publish.profane_send_email',
        ]);

        $profaneWords = $store
            ->profaneWords()
            ->get(['word'])
            ->pluck('word')
            ->toArray();

        return view('admin.stores.reviews.publish-settings.index', [
            'store' => $store,
            'settings' => $settings,
            'profaneWords' => $profaneWords,
        ]);
    }

    public function update(UpdatePublishSettingRequest $request, Store $store)
    {
        try {
            StoreSetting::setSettings($store, [
                'publish.auto_publish_reviews' => $request->validated('auto_publish_reviews', 0),
                'publish.min_rating' => $request->validated('min_rating', 5),
                'publish.profane_send_email' => $request->validated('profane_send_email'),
            ]);

            // Store profane words
            $store->profaneWords()->delete();

            $store->profaneWords()
                ->createMany(
                    collect($request->validated('profane_words', []))
                        ->map(fn($word) => ['word' => $word])
                        ->toArray());

            DB::commit();

            return success(trans('Publish Settings Updated Successfully.'));
        }catch (\Throwable $e){
            DB::rollBack();

            return error(trans('Something went wrong. Please try again.'), exception: $e);
        }
    }
}
