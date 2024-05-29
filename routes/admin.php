<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/', function () {
        return to_route('admin.dashboard.index');
    })->name('index');

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function (){
        Route::get('/', [Admin\NotificationController::class, 'index'])->name('index');
        Route::get('mark/{notification}', [Admin\NotificationController::class, 'visit'])->name('visit');
        Route::get('mark-all-as-read', [Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::post('mark/{notification}', [Admin\NotificationController::class, 'mark'])->name('mark');
        Route::delete('delete/{notification}', [Admin\NotificationController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'business', 'as' => 'business.'], function (){
        Route::resource('plans', Admin\Business\PlanController::class);

        Route::get('invoices', [Admin\Business\InvoiceController::class, 'index'])->name('invoices.index');
    });

    Route::group(['prefix' => 'frontend', 'as' => 'frontend.'], function (){
        Route::resource('pages', Admin\Frontend\PageController::class)->except('show');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [Admin\ProfileController::class,'index'])->name('index');
        Route::get('edit', [Admin\ProfileController::class,'edit'])->name('edit');
        Route::put('update', [Admin\ProfileController::class,'update'])->name('update');
        Route::delete('deactivate', [Admin\ProfileController::class,'deactivate'])->name('deactivate');
        Route::post('upload-avatar', [Admin\ProfileController::class,'uploadAvatar'])->name('upload-avatar');
        Route::delete('delete-avatar', [Admin\ProfileController::class,'deleteAvatar'])->name('delete-avatar');

        Route::get('security', [Admin\SecurityController::class, 'index'])->name('security.index');
        Route::put('security/change-password', [Admin\SecurityController::class, 'changePassword'])->name('security.change-password');
    });

    Route::group(['prefix' => 'customers/{customer}', 'as' => 'customers.'], function (){
        Route::post('restore', [Admin\Customer\CustomerController::class, 'restore'])
            ->name('restore')
            ->withTrashed();
        Route::delete('destroy/force', [Admin\Customer\CustomerController::class, 'forceDestroy'])
            ->name('destroy.force')
            ->withTrashed();
        Route::post('suspend', [Admin\Customer\CustomerController::class, 'suspend'])->name('suspend');
        Route::post('active', [Admin\Customer\CustomerController::class, 'active'])->name('active');
        Route::post('verify', [Admin\Customer\CustomerController::class, 'verify'])->name('verify');
        Route::post('login-as', [Admin\Customer\CustomerController::class, 'loginAs'])->name('login-as');

        Route::get('security', [Admin\Customer\SecurityController::class, 'index'])->name('security.index');
        Route::put('security/change-password', [Admin\Customer\SecurityController::class, 'changePassword'])->name('security.change-password');
    });
    Route::resource('customers', Admin\Customer\CustomerController::class);

    Route::group(['prefix' => 'stores', 'as' => 'stores.'], function (){
        Route::get('customers', [Admin\Store\StoreController::class, 'customers'])->name('customers');
        Route::post('{store}/restore', [Admin\Store\StoreController::class, 'restore'])
            ->name('restore')
            ->withTrashed();
        Route::delete('{store}/force', [Admin\Store\StoreController::class, 'forceDestroy'])
            ->name('destroy.force')
            ->withTrashed();

        Route::group(['prefix' => '{store}/reviews', 'as' => 'reviews.'], function (){
            Route::put('moderation/{review}/change-status/{status}', [Admin\Store\Reviews\ModerationController::class, 'changeStatus'])->name('moderation.change-status');
            Route::put('moderation/bulk-publish', [Admin\Store\Reviews\ModerationController::class, 'bulkPublish'])->name('moderation.bulk-publish');
            Route::put('moderation/bulk-reject', [Admin\Store\Reviews\ModerationController::class, 'bulkReject'])->name('moderation.bulk-reject');
            Route::post('moderation/comment', [Admin\Store\Reviews\ModerationController::class, 'comment'])->name('moderation.comment');
            Route::put('moderation/comment/{reply}/change-status', [Admin\Store\Reviews\ModerationController::class, 'commentChangeStatus'])->name('moderation.comment.change-status');
            Route::get('moderation', [Admin\Store\Reviews\ModerationController::class, 'index'])->name('moderation.index');

            Route::get('import', [Admin\Store\Reviews\ImportController::class, 'index'])->name('import.index');
            Route::post('import', [Admin\Store\Reviews\ImportController::class, 'store'])->name('import.store');
            Route::post('import/{file}/{provider}/confirm', [Admin\Store\Reviews\ImportController::class, 'confirm'])->name('import.confirm');
            Route::delete('import', [Admin\Store\Reviews\ImportController::class, 'deleteFile'])->name('import.delete-temporary-file');

            Route::get('publish-settings', [Admin\Store\Reviews\PublishSettingController::class, 'index'])->name('publish-settings.index');
            Route::put('publish-settings', [Admin\Store\Reviews\PublishSettingController::class, 'update'])->name('publish-settings.update');
        });
    });
    Route::resource('stores', Admin\Store\StoreController::class);

    Route::put('support-tickets/{supportTicket}/change-status', [Admin\SupportTicketController::class, 'changeStatus'])->name('support-tickets.change-status');
    Route::post('support-tickets/{supportTicket}/reply', [Admin\SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::post('support-tickets/mark-selected-as-open', [Admin\SupportTicketController::class, 'markSelectedAsOpen'])->name('support-tickets.mark-selected-as-open');
    Route::post('support-tickets/mark-selected-as-closed', [Admin\SupportTicketController::class, 'markSelectedAsClosed'])->name('support-tickets.mark-selected-as-closed');
    Route::resource('support-tickets', Admin\SupportTicketController::class)->except(['create', 'store']);

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function (){
        Route::group(['prefix' => 'general', 'as' => 'general.'], function (){
            Route::get('/', [Admin\Settings\GeneralController::class, 'index'])->name('index');
        });

        Route::resource('roles', Admin\Settings\RoleController::class)->except(['show']);
        Route::post('users/{user}/suspend', [Admin\Settings\UserController::class, 'suspend'])->name('users.suspend');
        Route::post('users/{user}/active', [Admin\Settings\UserController::class, 'active'])->name('users.active');
        Route::resource('users', Admin\Settings\UserController::class);

        Route::get('email', [Admin\Settings\EmailController::class, 'index'])->name('email.index');
        Route::put('email', [Admin\Settings\EmailController::class, 'update'])->name('email.update');
        Route::post('email/test', [Admin\Settings\EmailController::class, 'test'])->name('email.test');

        Route::get('languages/{language}/translations', [Admin\Settings\LanguageController::class, 'translations'])->name('languages.translations');
        Route::put('languages/{language}/translations', [Admin\Settings\LanguageController::class, 'translationsUpdate'])->name('languages.translations.update');
        Route::resource('languages', Admin\Settings\LanguageController::class)->except(['show']);
    });
});
