<?php

namespace App\Http\Controllers\User\GetStarted;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Stores\StoreStoreRequest;
use App\Jobs\TakeStoreScreenshotJob;
use App\Models\StoreCategory;
use Illuminate\Support\Facades\Auth;

class WizardController extends Controller
{
    public function index()
    {
        if (activeStore()?->is_integrated){
            return redirect()->route('user.dashboard.index');
        }

        if (activeStore()){
            return redirect()->route('user.get-started.email-setup.index');
        }

        $categories = StoreCategory::active()->get();

        return view('user.get-started.wizard.index', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreStoreRequest $request)
    {
        $store = Auth::user()->stores()->create([
                'total_orders' => 200,
                'used_orders' => 200,
            ] + $request->validated());

        TakeStoreScreenshotJob::dispatch($store);

        return success(trans('Store Created Successfully'), route('user.get-started.email-setup.index'));
    }
}
