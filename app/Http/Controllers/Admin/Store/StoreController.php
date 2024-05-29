<?php

namespace App\Http\Controllers\Admin\Store;

use App\DataTables\Admin\Store\StoreDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Stores\StoreStoreRequest;
use App\Http\Requests\Admin\Stores\UpdateStoreRequest;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class StoreController extends Controller
{
    public function index(StoreDataTable $dataTable)
    {
        $categories = StoreCategory::whereIsActive(1)
            ->withCount('stores')
            ->get();

        return $dataTable->render('admin.stores.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $categories = StoreCategory::whereIsActive(1)->get();

        return view('admin.stores.create', [
            'categories' => $categories
        ]);
    }

    public function store(StoreStoreRequest $request)
    {
        Store::create([
            'user_id' => $request->validated('customer'),
            'store_category_id' => $request->validated('category'),
        ] + $request->validated());

        return success(trans('Store Created Successfully'), route('admin.stores.index'));
    }

    public function show(Store $store)
    {
        $overview = [
            'overviewReviewRequestsSent' => 0,
            'overviewReviewsCollected' => Number::abbreviate($store->reviews()->count(), 2),
            'overviewReviewsPublished' => Number::abbreviate($store->reviews()->published()->count()),
            'overviewAverageRating' => $store->reviews()->published()->avg('rating') ?? 0,
        ];

        return view('admin.stores.show', [
            'store' => $store,
            ...$overview,
        ]);
    }

    public function edit(Store $store)
    {
        $categories = StoreCategory::whereIsActive(1)->get();

        return view('admin.stores.edit', [
            'store' => $store,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateStoreRequest $request, Store $store)
    {
        $store->update([
            'store_category_id' => $request->validated('category'),
        ] + $request->validated());

        return success(trans('Store Updated Successfully'), route('admin.stores.index'));
    }

    public function customers(Request $request)
    {
        $search = $request->get('search');

        $customers = User::selectRaw('id, CONCAT(first_name, " ", last_name) as text')
            ->when($search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            })
            ->whereGroup('customer')
            ->paginate(10);

        return response()->json($customers);
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return success(trans('Store Deleted Successfully'));
    }

    public function forceDestroy(Store $store)
    {
        $store->forceDelete();

        return success(trans('Store Permanently Deleted Successfully'));
    }

    public function restore(Store $store)
    {
        $store->restore();

        return success(trans('Store Restored Successfully'));
    }
}
