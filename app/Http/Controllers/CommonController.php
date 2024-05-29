<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CommonController extends Controller
{
    public function avatar(User $user, Media $media, $fileName)
    {
        return $media->toInlineResponse(request());
    }

    public function storePreviewImage(Store $store, Media $media, $fileName)
    {
        return $media->toInlineResponse(request());
    }

    public function changeLanguage($code)
    {
        $hasLanguage = Language::whereCode($code)->whereIsActive(1)->exists();

        abort_if(!$hasLanguage, 404);

        session()->put('locale', $code);

        flash(trans('Language Changed Successfully'));

        return redirect()->back();
    }
}
