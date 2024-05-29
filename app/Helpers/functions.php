<?php

use App\Models\Setting;
use App\Models\Store;
use App\Models\StoreSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

if (!function_exists('message')) {
    /**
     * Return a success response.
     *
     * @param null $redirect
     * @param mixed ...$params
     */
    function message($message, $redirect = null, array $data = [], string $status = 'success', int $code = 200, ...$params): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'redirect' => $redirect,
            'data' => $data,
            ...$params,
        ], $code);
    }
}

if (!function_exists('success')) {
    /**
     * Return a success response.
     */
    function success($message, $redirect = null, $data = null, array $params = []): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'redirect' => $redirect,
            'data' => $data,
            ...$params,
        ]);
    }
}

if (!function_exists('warning')) {
    /**
     * Return a success response.
     */
    function warning($message, $redirect = null, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'warning',
            'message' => $message,
            'redirect' => $redirect,
            'data' => $data,
        ]);
    }
}

if (!function_exists('error')) {
    /**
     * Return an error response.
     */
    function error($message, $redirect = null, $errors = null, int $code = 422, Throwable $exception = null, ...$args): JsonResponse
    {
        if (config('app.debug')) {
            $message = $exception ? $exception->getMessage() : $message;
        }
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'redirect' => $redirect,
            'errors' => $errors,
            ...$args,
        ], $code);
    }
}


if (!function_exists('apiSuccess')) {
    /**
     * Return a apiSuccess response.
     */
    function apiSuccess($message, $data = null, array $params = []): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            ...$params,
        ]);
    }
}

if (!function_exists('apiError')) {
    /**
     * Return a apiError response.
     */
    function apiError($message, $data = null, array $params = [], $code = 422): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data,
            ...$params,
        ], $code);
    }
}

if (!function_exists('setting')) {
    /**
     * Get the setting value.
     */
    function setting($key, $default = null): mixed
    {
        return Setting::getSetting($key, $default);
    }
}

if (!function_exists('dateFormat')) {
    /**
     * Format a date.
     *
     * @param $date DateTimeImmutable|DateTime|int|string|null
     * @param string|null $format
     * @param null $append
     * @param null $prepend
     *
     * @return string
     * @throws Exception
     */
    function dateFormat(DateTimeImmutable|DateTime|int|string|null $date, string $format = null, $append = null, $prepend = null): string
    {
        if ($date === null) {
            return '';
        }

        if ($append !== null) {
            $format .= ' ' . $append;
        }

        if ($prepend !== null) {
            $format = $prepend . ' ' . $format;
        }

        if ($format === null) {
            $format = setting('date_format', 'd M, Y');
        }

        return dateTimeFormatter($date, $format);
    }
}

if (!function_exists('dateTimeFormat')) {
    /**
     * Format a date time.
     *
     * @param $date DateTimeImmutable|DateTime|int|string|null
     * @param string|null $format
     * @param null $append
     * @param null $prepend
     *
     * @return string
     * @throws Exception
     */
    function dateTimeFormat(DateTimeImmutable|DateTime|int|string|null $date, string $format = null, $append = null, $prepend = null): string
    {
        if ($date === null) {
            return '';
        }

        if ($append !== null) {
            $format .= ' ' . $append;
        }

        if ($prepend !== null) {
            $format = $prepend . ' ' . $format;
        }

        if ($format === null) {
            $format = setting('date_format', 'd M, Y - h:i A');
        }

        return dateTimeFormatter($date, $format);
    }
}

if (!function_exists('timeFormat')) {
    /**
     * Format a time.
     *
     * @param $date DateTimeImmutable|DateTime|int|string|null
     * @param string|null $format
     * @param null $append
     * @param null $prepend
     *
     * @return string
     * @throws Exception
     */
    function timeFormat(DateTimeImmutable|DateTime|int|string|null $date, string $format = null, $append = null, $prepend = null): string
    {
        if ($date === null) {
            return '';
        }

        if ($append !== null) {
            $format .= ' ' . $append;
        }

        if ($prepend !== null) {
            $format = $prepend . ' ' . $format;
        }

        if ($format === null) {
            $format = setting('date_format', 'h:i A');
        }

        return dateTimeFormatter($date, $format);
    }
}

if (!function_exists('dateTimeFormatter')) {
    /**
     * @param DateTimeImmutable|DateTime|int|string $date
     * @param mixed $format
     * @return string
     * @throws Exception
     */
    function dateTimeFormatter(DateTimeImmutable|DateTime|int|string $date, mixed $format): string
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        } elseif (is_int($date)) {
            $date = new DateTime('@' . $date);
        } elseif ($date instanceof DateTime) {
            $date = clone $date;
        } elseif ($date instanceof DateTimeImmutable) {
            $date = new DateTime($date);
        } else {
            throw new Exception(trans('Invalid date format'));
        }

        return Carbon::parse($date)->translatedFormat($format);
    }
}

if (!function_exists('cacheForget')) {
    /**
     * Forget a cached value.
     */
    function cacheForget(string $key): bool
    {
        return Cache::forget($key);
    }
}

if (!function_exists('isActiveBadge')) {
    /**
     * Return bootstrap badge html.
     *
     * @param bool $status
     * @param string|null $activeText
     * @param string|null $inactiveText
     * @return string
     */
    function isActiveBadge(bool $status, string|null $activeText = null, string|null $inactiveText = null): string
    {
        $activeText = $activeText ?? trans('Active');
        $inactiveText = $inactiveText ?? trans('Inactive');

        return '<span class="badge bg-label-' . ($status ? 'success' : 'danger') . '">' . ($status ? '<i class="ti ti-circle-check me-0 me-sm-1 ti-xs"></i>' . $activeText : '<i class="ti ti-circle-x me-0 me-sm-1 ti-xs"></i>' . $inactiveText) . '</span>';
    }
}

if (!function_exists('daysInWeek')) {
    function daysInWeek($format = "D"): array
    {
        return array_map(function ($day) use ($format) {
            return now()->startOfWeek()->addDays($day)->format($format);
        }, range(0, 6));
    }
}

if (!function_exists('nullSafeDivide')) {
    function nullSafeDivide($dividend, $divisor, $default = 0, $percentage = false): float|int
    {
        if ($divisor == 0) {
            return $default;
        }

        $result = $dividend / $divisor;

        return $percentage ? $result * 100 : $result;
    }
}

if (!function_exists('activeStore')) {
    function activeStore(): Store|null
    {
//        if (session()->has('activeStore')) {
//            return session('activeStore');
//        }

        return $activeStore = auth()->user()->stores()->first();
//        if ($activeStore) {
//            session()->put('activeStore', $activeStore);
//            return $activeStore;
//        }

        return null;
    }
}

if (!function_exists('getStoreSetting')) {
    function getStoreSetting($key, $default = null): mixed
    {
        $store = activeStore();

        if ($store) {
            return StoreSetting::where('key', $key)->where('store_id', $store->id)->first()?->value ?? $default;
        }

        return $default;
    }
}


if (!function_exists('domain_url')) {
    function domain_url($url, $domain = null)
    {
        if (!$domain){
            $domain = config('app.domain');
        }

        if ($domain) {
            return $domain . '/' . $url;
        }

        return url($url);
    }
}
