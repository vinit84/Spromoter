<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Subscription\StoreCheckoutRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => [
                'line1' => $user->address,
                'city' => $user->city,
                'state' => $user->state,
                'postal_code' => $user->postal_code,
                'country' => $user->country,
            ],
        ]);

        return $user->redirectToBillingPortal(route('user.dashboard.index'));
    }

    public function checkout(StoreCheckoutRequest $request)
    {
        $user = auth()->user();

        $plan = Plan::findOrFail($request->plan);
        $interval = $request->interval;

        $user->createOrGetStripeCustomer([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => [
                'line1' => $user->address,
                'city' => $user->city,
                'state' => $user->state,
                'postal_code' => $user->postal_code,
                'country' => $user->country,
            ],
        ]);

        // Check if user already has the same plan
//        if ($user->subscribedToPrice($plan->monthly_price_id, $plan->title)) {
//            return redirect()->route('user.subscriptions.index')->with('error', 'You already have the same plan.');
//        }

        $price = $interval === 'monthly' ? $plan->monthly_price_id : $plan->yearly_price_id;
        $totalOrders = $interval === 'monthly' ? $plan->monthly_order : $plan->monthly_order * 12;
        $canGetTrial = $user->subscriptions()->count() === 0;

        return $user->newSubscription($plan->title, $price)
            ->allowPromotionCodes()
            ->when($plan->trial_days > 0 && $canGetTrial, function ($builder) use ($plan) {
                $builder->trialDays($plan->trial_days);
            })
            ->withMetadata([
                'plan_id' => $plan->id,
                'store_id' => activeStore()->id,
                'interval' => $interval,
                'total_orders' => $totalOrders
            ])
            ->checkout([
                'success_url' => route('user.subscriptions.success', $plan) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('user.subscriptions.failed', $plan),
            ]);
    }

    public function success(Request $request, Plan $plan)
    {
        return view('user.subscriptions.success', compact('plan'));
    }

    public function failed(Request $request, Plan $plan)
    {
        return view('user.subscriptions.failed', compact('plan'));
    }
}
