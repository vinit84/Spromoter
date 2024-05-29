<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Settings\General\UpdateReviewSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function index()
    {
        return view('admin.settings.general.index');
    }

    public function updateReviewSettings(UpdateReviewSettingRequest $request)
    {
        Setting::setSettings($request->validated());
    }
}
