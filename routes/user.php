<?php

use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'user.', 'middleware' => ['auth', 'customer', 'store', 'verified']], function () {

    Route::get('/', function (){
        return to_route('user.dashboard.index');
    })->name('index');

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('/', [User\DashboardController::class, 'index'])->name('index');
    });

    Route::get('impersonate/leave', [User\ImpersonateController::class, 'leave'])
        ->withoutMiddleware('active')
        ->name('impersonate.leave');

    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function (){
        Route::get('/', [User\NotificationController::class, 'index'])->name('index');
        Route::get('mark/{notification}', [User\NotificationController::class, 'visit'])->name('visit');
        Route::get('mark-all-as-read', [User\NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::post('mark/{notification}', [User\NotificationController::class, 'mark'])->name('mark');
        Route::delete('delete/{notification}', [User\NotificationController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [User\Profile\ProfileController::class,'index'])->name('index');
        Route::get('edit', [User\Profile\ProfileController::class,'edit'])->name('edit');
        Route::put('update', [User\Profile\ProfileController::class,'update'])->name('update');
        Route::delete('deactivate', [User\Profile\ProfileController::class,'deactivate'])->name('deactivate');
        Route::post('upload-avatar', [User\Profile\ProfileController::class,'uploadAvatar'])->name('upload-avatar');
        Route::delete('delete-avatar', [User\Profile\ProfileController::class,'deleteAvatar'])->name('delete-avatar');

        Route::get('security', [User\Profile\SecurityController::class, 'index'])->name('security.index');
        Route::put('security/change-password', [User\Profile\SecurityController::class, 'changePassword'])->name('security.change-password');

        Route::get('api-keys', [User\Profile\ApiKeyController::class, 'index'])->name('api-keys.index');
        Route::post('api-keys', [User\Profile\ApiKeyController::class, 'store'])->name('api-keys.store');

        Route::get('billing', [User\BillingController::class, 'index'])->name('billing.index');
        Route::get('billing/portal', [User\BillingController::class, 'portal'])->name('billing.portal');
    });

    Route::get('integrations', [User\IntegrationController::class, 'index'])->name('integrations.index');

    Route::group(['prefix' => 'get-started', 'as' => 'get-started.'], function (){
        Route::get('wizard', [User\GetStarted\WizardController::class, 'index'])->name('wizard.index')->withoutMiddleware('store');
        Route::post('wizard', [User\GetStarted\WizardController::class, 'store'])->name('wizard.store')->withoutMiddleware('store');

        Route::get('email-setup', [User\GetStarted\EmailSetupController::class, 'index'])->name('email-setup.index');
        Route::put('email-setup', [User\GetStarted\EmailSetupController::class, 'update'])->name('email-setup.update');

        Route::get('integration', [User\GetStarted\IntegrationController::class, 'index'])->name('integration.index');
        Route::get('integration/finish', [User\GetStarted\IntegrationController::class, 'finish'])->name('integration.finish');
    });

    Route::get('plans', [User\PlanController::class, 'index'])->name('plans.index');

    Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function (){
        Route::get('/', [User\InvoiceController::class, 'index'])->name('index');
        Route::get('{invoice}', [User\InvoiceController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => 'subscriptions'], function (){
        Route::get('/', [User\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('checkout', [User\SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
        Route::get('checkout/{plan}/success', [User\SubscriptionController::class, 'success'])->name('subscriptions.success');
        Route::get('checkout/{plan}/failed', [User\SubscriptionController::class, 'failed'])->name('subscriptions.failed');
    });

    Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function (){
        Route::group(['prefix' => 'moderation', 'as' => 'moderation.'], function (){
            Route::get('/', [User\Reviews\ModerationController::class, 'index'])
                ->name('index');
            Route::put('bulk-publish', [User\Reviews\ModerationController::class, 'bulkPublish'])
                ->name('bulk-publish');
            Route::put('bulk-reject', [User\Reviews\ModerationController::class, 'bulkReject'])
                ->name('bulk-reject');
            Route::post('comment', [User\Reviews\ModerationController::class, 'comment'])
                ->name('comment');
            Route::put('comment/{reply}/change-status', [User\Reviews\ModerationController::class, 'commentChangeStatus'])
                ->name('comment.change-status');
            Route::put('change-status/{review}/{status}', [User\Reviews\ModerationController::class, 'changeStatus'])
                ->name('change-status');
        });

        Route::group(['prefix' => 'export', 'as' => 'export.'], function (){
            Route::get('/', [User\Reviews\ExportController::class, 'index'])->name('index');
            Route::get('/create/{type?}', [User\Reviews\ExportController::class, 'create'])->name('create');
            Route::post('/', [User\Reviews\ExportController::class, 'store'])->name('store');
            Route::get('/download/{export}', [User\Reviews\ExportController::class, 'download'])->name('download');
        });

        Route::group(['prefix' => 'import', 'as' => 'import.'], function (){
            Route::get('/', [User\Reviews\ImportController::class, 'index'])->name('index');
            Route::post('/', [User\Reviews\ImportController::class, 'store'])->name('store');
            Route::delete('/', [User\Reviews\ImportController::class, 'deleteFile'])->name('delete-temporary-file');
            Route::post('/{file}/{provider}/confirm', [User\Reviews\ImportController::class, 'confirm'])->name('confirm');
        });

        Route::get('publish-settings', [User\Reviews\PublishSettingController::class, 'index'])->name('publish-settings.index');
        Route::put('publish-settings', [User\Reviews\PublishSettingController::class, 'update'])->name('publish-settings.update');
    });

    Route::group(['prefix' => 'emails', 'as' => 'emails.'], function (){
        Route::get('email-setup', [User\Emails\EmailSetupController::class, 'index'])->name('email-setup.index');
        Route::get('email-setup/review-request-email', [User\Emails\EmailSetupController::class, 'reviewRequestEmail'])->name('email-setup.review-request-email');
        Route::get('email-setup/review-request-email/preview', [User\Emails\EmailSetupController::class, 'reviewRequestEmailPreview'])->name('email-setup.review-request-email-preview');
        Route::put('email-setup/review-request-email', [User\Emails\EmailSetupController::class, 'reviewRequestEmailUpdate'])->name('email-setup.review-request-email-update');

        Route::post('email-setup/test-email', [User\Emails\EmailSetupController::class, 'sendTestEmail'])->name('email-setup.send-test-email');

        Route::get('email-status', [User\Emails\EmailStatusController::class, 'index'])->name('email-status.index');
        Route::post('email-status/{email}/send', [User\Emails\EmailStatusController::class, 'send'])->name('email-status.send');
        Route::delete('email-status/{email}/destroy', [User\Emails\EmailStatusController::class, 'destroy'])->name('email-status.destroy');
    });

    Route::group(['prefix' => 'analytics', 'as' => 'analytics.'], function (){
        Route::get('reviews', [User\Analytics\ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/chart', [User\Analytics\ReviewController::class, 'reviewsChart'])->name('reviews.chart');

        Route::get('emails', [User\Analytics\EmailController::class, 'index'])->name('emails.index');
    });

    Route::put('support-tickets/{supportTicket}/change-status', [User\SupportTicketController::class, 'changeStatus'])->name('support-tickets.change-status');
    Route::post('support-tickets/{supportTicket}/reply', [User\SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::resource('support-tickets', User\SupportTicketController::class)->withoutMiddleware('store');
});
