<?php

namespace App\Http\Controllers\User\GetStarted;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Emails\EmailSetup\UpdateReviewRequestEmailRequest;
use App\Models\StoreSetting;

class EmailSetupController extends Controller
{
    public function index()
    {
        $store = activeStore();
        $settings = StoreSetting::getSettings($store, [
            'emails.review_request_email_days',
            'emails.review_request_email_subject',
            'emails.review_request_email_body',
        ]);

        return view('user.get-started.email-setup.index', [
            'settings' => $settings,
        ]);
    }


    public function update(UpdateReviewRequestEmailRequest $request)
    {
        $store = activeStore();

        StoreSetting::setSettings($store, [
            'emails.review_request_email_days' => $request->days,
            'emails.review_request_email_subject' => $request->subject,
            'emails.review_request_email_body' => $request->body,
        ]);

        return success(trans('Review request email updated successfully.'), route('user.get-started.integration.index'));
    }
}
