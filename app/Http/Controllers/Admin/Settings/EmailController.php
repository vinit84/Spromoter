<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Email\TestEmailRequest;
use App\Http\Requests\Admin\Settings\Email\UpdateEmailRequest;
use App\Mail\EmailTesting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Jackiedo\DotenvEditor\DotenvEditor;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:email-setting-read'])->only(['index']);
        $this->middleware(['permission:email-setting-update'])->only(['update']);
    }

    public function index()
    {
        return view('admin.settings.email.index');
    }

    public function update(UpdateEmailRequest $request, DotenvEditor $dotenvEditor)
    {
        $dotenvEditor->setKeys([
            'MAIL_MAILER' => $request->validated('mail_mailer'),
            'MAIL_HOST' => $request->validated('mail_host'),
            'MAIL_PORT' => $request->validated('mail_port'),
            'MAIL_USERNAME' => $request->validated('mail_username'),
            'MAIL_PASSWORD' => $request->validated('mail_password'),
            'MAIL_ENCRYPTION' => $request->validated('mail_encryption'),
            'MAIL_FROM_ADDRESS' => $request->validated('mail_from_address'),
            'MAIL_FROM_NAME' => $request->validated('mail_from_name'),
        ])->save();

        return success(trans('SMTP Updated Successfully'));
    }

    public function test(TestEmailRequest $request)
    {
        try {
            Mail::to($request->validated('to'))
                ->send(new EmailTesting($request->validated('subject'), $request->validated('body')));

            return success(trans('Email Sent Successfully'));
        } catch (\Throwable $exception) {
            throw $exception;
            return error(trans('Please Check Your SMTP Settings'));
        }
    }
}
