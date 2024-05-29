<?php

namespace App\Http\Controllers\Admin\Business;

use App\DataTables\Admin\Business\PlanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Business\Plans\StorePlanRequest;
use App\Http\Requests\Admin\Business\Plans\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Cashier;
use Stripe\Exception\ApiErrorException;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:plan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:plan-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:plan-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:plan-delete', ['only' => ['destroy']]);
    }

    public function index(PlanDataTable $dataTable)
    {
        return $dataTable->render('admin.business.plans.index');
    }

    public function create()
    {
        $featureGroups = Plan::FEATURES;

        return view('admin.business.plans.create', [
            'featureGroups' => $featureGroups
        ]);
    }

    public function store(StorePlanRequest $request)
    {
        if (Plan::checkStripePlanExists($request->validated('slug'))){
            return error(trans('Plan is already exists in Stripe, please choose another slug'), errors: [
                'slug' => 'Plan is already exists in Stripe, please choose another slug'
            ]);
        }

        DB::beginTransaction();
        try {
            $product = Plan::createProduct(
                $request->validated('title'),
                $request->validated('description'),
                $request->validated('slug'),
                $request->validated('features'),
                $request->validated('is_active'),
            );

            $monthlyPrice = Plan::createPrice(
                $product,
                $request->validated('monthly_price'),
                'Monthly',
                'month',
                $request->validated('monthly_order'),
                $request->validated('trial_days'),
            );

            $yearlyPrice = Plan::createPrice(
                $product,
                $request->validated('yearly_price'),
                'Yearly',
                'year',
                $request->validated('monthly_order'),
                $request->validated('trial_days'),
            );

            // Create the plan in the database
            Plan::create([
                'title' => $request->validated('title'),
                'slug' => $request->validated('slug'),
                'stripe_id' => $product->id,

                'monthly_price' => $request->validated('monthly_price'),
                'yearly_price' => $request->validated('yearly_price'),

                'monthly_price_id' => $monthlyPrice->id,
                'yearly_price_id' => $yearlyPrice->id,

                'monthly_order' => $request->validated('monthly_order'),
                'trial_days' => $request->validated('trial_days'),

                'description' => $request->validated('description'),
                'features' => $request->validated('features'),
                'is_active' => $request->boolean('is_active')
            ]);

            DB::commit();
            return success(trans('Plan Created Successfully'), route('admin.business.plans.index'));
        }catch (\Exception $e){
            DB::rollBack();

            Plan::deleteStripePlan($request->validated('slug'));
            return error($e->getMessage());
        }
    }

    public function edit(Plan $plan)
    {
        return view('admin.business.plans.edit', [
            'plan' => $plan,
            'featureGroups' => Plan::FEATURES
        ]);
    }

    /**
     * @param UpdatePlanRequest $request
     * @param Plan $plan
     * @return JsonResponse
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        try {
            $plan->updateProduct(
                $request->validated('title'),
                $request->validated('description'),
                $request->validated('features'),
                $request->validated('is_active')
            );

            $plan->update([
                'title' => $request->validated('title'),
                'description' => $request->validated('description'),
                'features' => $request->validated('features'),
                'card_features' => $request->validated('card_features'),
                'is_active' => $request->boolean('is_active')
            ]);

            return success(trans("Plan Updated Successfully"), route('admin.business.plans.index'));

        }catch (\Exception $e){
            return error($e->getMessage());
        }
    }
}
