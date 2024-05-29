<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\PasswordConfirmedResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LoginResponse::class, new class implements LoginResponse
        {
            public function toResponse($request): JsonResponse|RedirectResponse
            {
                $redirect = $request->user()->isCustomer() ? route('user.dashboard.index') : route('admin.dashboard.index');

                if ($request->wantsJson()) {
                    return success(__('Logged in successfully'), session('url.intended', $redirect));
                } else {
                    flash(trans('Logged in successfully'));

                    return redirect()->intended($redirect);
                }
            }
        });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request): JsonResponse|RedirectResponse
            {
                if ($request->wantsJson()) {
                    return success(__('Logout successfully'), route('login'));
                } else {
                    flash(trans('Logout successfully'));
                    return redirect()->intended(route('login'));
                }
            }
        });

        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse
        {
            public function toResponse($request): JsonResponse|RedirectResponse
            {
                $redirect = auth()->user()->isCustomer() ? route('user.dashboard.index') : route('admin.dashboard.index');

                if ($request->wantsJson()) {
                    return success(trans('Registration Completed'), session('url.intended', $redirect));
                } else {
                    flash(trans('Registration Completed'));

                    return redirect()->intended($redirect);
                }
            }
        });

        $this->app->instance(TwoFactorConfirmedResponse::class, new class implements TwoFactorConfirmedResponse
        {
            public function toResponse($request): JsonResponse|RedirectResponse
            {
                $redirect = $request->user()->isCustomer() ? route('user.dashboard.index') : route('admin.dashboard.index');

                if ($request->wantsJson()) {
                    return success(trans('Two Factor Challenge Completed'), session('url.intended', $redirect));
                } else {
                    flash(trans('Two Factor Challenge Completed'));

                    return redirect()->intended($redirect);
                }
            }
        });

        $this->app->instance(PasswordConfirmedResponse::class, new class implements PasswordConfirmedResponse
        {
            public function toResponse($request): JsonResponse|RedirectResponse
            {
                $redirect = $request->user()->isCustomer() ? route('user.dashboard.index') : route('admin.dashboard.index');

                if ($request->wantsJson()) {
                    return success(trans('Password Confirmed Successfully'), session('url.intended', $redirect));
                } else {
                    flash(trans('Password Confirmed Successfully'));

                    return redirect()->intended($redirect);
                }
            }
        });

        $this->app->instance(VerifyEmailResponse::class, new class implements VerifyEmailResponse
        {
            public function toResponse($request): JsonResponse|RedirectResponse
            {
                $redirect = $request->user()->isCustomer() ? route('user.dashboard.index') : route('admin.dashboard.index');

                if ($request->wantsJson()) {
                    return success(trans('Email Verified Successfully'), session('url.intended', $redirect));
                } else {
                    flash(trans('Email Verified Successfully'));

                    return redirect()->intended($redirect);
                }
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function (Request $request) {
            $redirect = $request->get('redirect');

            if ($redirect) {
                session(['url.intended' => $redirect]);
            }

            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        // Override the default authentication
        Fortify::authenticateUsing(function (Request $request) {
            if (setting('google_recaptcha') && ! config('abn.demo_mode')) {
                $request->validate([
                    'g-recaptcha-response' => 'required|captcha',
                ], [
                    'g-recaptcha-response.required' => trans('Please ensure that you are a human!'),
                    'g-recaptcha-response.captcha' => trans('Captcha error! try again later or contact site admin.'),
                ]);
            }

            $user = User::where('email', $request->input('email'))->first();

            if ($user && Hash::check($request->input('password'), $user->password)) {
                return $user;
            }

            return null;
        });
    }
}
