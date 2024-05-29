<?php

namespace App\Http\Controllers\User\Profile;

use App\Helpers\Country;
use App\Helpers\TimeZones;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\DeactivateProfileRequest;
use App\Http\Requests\User\Profile\DeleteAvatarRequest;
use App\Http\Requests\User\Profile\UpdateProfileRequest;
use App\Http\Requests\User\Profile\UploadAvatarRequest;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $logs = Activity::whereCauserId($user->id)
            ->whereCauserType(get_class($user))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.profile.index', [
            'user' => $user,
            'logs' => $logs,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        $countries = Country::get();
        $timeZones = TimeZones::get();
        $languages = Language::active()->get();

        return view('user.profile.edit', [
            'user' => $user,
            'countries' => $countries,
            'timeZones' => $timeZones,
            'languages' => $languages,
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        Auth::user()->update($request->validated() + [
            'language_id' => $request->language,
        ]);

        return success(trans('Profile Updated Successfully'));
    }

    public function deactivate(DeactivateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'status' => 'deactivated',
        ]);

        // TODO: Send email to user

        Auth::logout();

        return success(trans('Profile Deactivated Successfully'), route('login'));
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
