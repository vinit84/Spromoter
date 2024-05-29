<?php

namespace App\Http\Controllers\User\Emails;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Emails\EmailSetup\UpdateReviewRequestEmailRequest;
use App\Mail\ReviewRequestEmail;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Mail;

class EmailSetupController extends Controller
{
    public function index()
    {
        return view('user.emails.email-setup.index');
    }

    public function reviewRequestEmail()
    {
        $store = activeStore();
        $settings = StoreSetting::getSettings($store, [
            'emails.review_request_email_days',
            'emails.review_request_email_subject',
            'emails.review_request_email_body',
        ]);

        return view('user.emails.email-setup.review-request-email', [
            'settings' => $settings,
        ]);
    }

    public function reviewRequestEmailPreview()
    {
        $setting = getStoreSetting('emails.review_request_email_body');

        $markdown = new Markdown(view(), config('mail.markdown'));
        $preview = $markdown->render("user.emails.email-setup.template", [
            'body' => $setting ?? "",
            'layout' => [
                'store' => activeStore()->name
            ],
            'image' => 'https://via.placeholder.com/150',
            'product' => 'Product Name',
            'description' => 'Product Description',
            'purchaseDate' => '2021-01-01',
        ]);
        $preview = html_entity_decode($preview);

        return response()->json([
            'preview' => $preview,
        ]);
    }

    public function reviewRequestEmailUpdate(UpdateReviewRequestEmailRequest $request)
    {
        $store = activeStore();

        StoreSetting::setSettings($store, [
            'emails.review_request_email_days' => $request->days,
            'emails.review_request_email_subject' => $request->subject,
            'emails.review_request_email_body' => $request->body,
        ]);

        return success(trans('Review request email updated successfully.'));
    }

    public function sendTestEmail(Request $request)
    {
        /*$request->validate([
            'email' => 'required|email',
        ]);

        $store = activeStore();

        $subject = getStoreSetting('emails.review_request_email_subject');
        $body = getStoreSetting('emails.review_request_email_body');

        Mail::to($request->input('email'))
            ->send(new ReviewRequestEmail($subject, [
                'body' => $body,
                'layout' => [
                    'store' => $store->name,
                    'logo' => $store->logo,
                ],
                'image' => 'https://via.placeholder.com/150',
                'product' => 'Product Name',
                'description' => 'Product Description',
                'purchaseDate' => '2021-01-01',
            ]));

        return success(trans('Test email sent successfully.'));*/
    }
}
