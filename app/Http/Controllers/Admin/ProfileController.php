<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Country;
use App\Helpers\TimeZones;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\DeactivateProfileRequest;
use App\Http\Requests\Admin\Profile\DeleteAvatarRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Http\Requests\Admin\Profile\UploadAvatarRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('admin.profile.index', [
            'user' => $user,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        $countries = Country::get();
        $timeZones = TimeZones::get();

        return view('admin.profile.edit', [
            'user' => $user,
            'countries' => $countries,
            'timeZones' => $timeZones,
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        Auth::user()->update($request->validated());

        return success(trans('Profile Updated Successfully'));
    }

    public function deactivate(DeactivateProfileRequest $request)
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) {
            return error(trans('You cannot deactivate the Super Admin profile'));
        }

        return success(trans('Profile Deactivated Successfully'));
    }

    public function uploadAvatar(UploadAvatarRequest $request)
    {
        try {
            $user = Auth::user();

            $user->clearMediaCollection('avatar');

            $media = $user->addMedia($request->file('photo'))
                ->toMediaCollection('avatar', 'local');

            $user->update([
                'profile_photo_url' => route('common.avatar', [$user->id, $media->uuid, $media->file_name]),
            ]);

            return success(trans('Avatar Uploaded Successfully'));
        }catch (\Throwable $throwable){
            return error(trans('Failed to upload avatar'));
        }
    }

    public function deleteAvatar(DeleteAvatarRequest $request)
    {

    }
}
