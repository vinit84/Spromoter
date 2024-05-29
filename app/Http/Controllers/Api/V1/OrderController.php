<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\StoreBulkOrderRequest;
use App\Http\Requests\Api\V1\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $store = Store::whereUuid($request->header('x-app-id'))->firstOrFail();
        DB::beginTransaction();
        try {
            $order = $store->orders()->create([
                'order_id' => $request->validated('order_id'),
                'customer_name' => $request->validated('customer_name'),
                'customer_email' => $request->validated('customer_email'),
                'order_date' => $request->validated('order_date'),
                'currency' => $request->validated('currency'),
                'total' => $request->validated('total'),
                'status' => $request->validated('status'),
                'platform' => $request->validated('platform'),
                'data' => $request->validated('data'),
            ]);

            foreach ($request->validated('items') as $item) {
                $this->createOrderItem($store, $item, $order);
            }

            foreach ($order->items as $item) {
                $item->email()->create([
                    'store_id' => $store->id,
                    'order_id' => $order->id,
                    'status' => 'scheduled',
                    'scheduled_at' => now()->addDays($store->setting('emails.review_request_email_days'))
                ]);
            }

            DB::commit();

            return apiSuccess(trans('Order created successfully'));
        } catch (Exception $exception) {
            DB::rollBack();
            return apiError($exception->getMessage());
        }
    }

    public function bulkStore(StoreBulkOrderRequest $request)
    {
        $store = Store::whereUuid($request->header('x-app-id'))->firstOrFail();

        DB::beginTransaction();
        try {
            foreach ($request->input('orders') as $bulkOrder) {
                $order = $store->orders()->updateOrCreate([
                    'order_id' => $bulkOrder['order_id'],
                ], [
                    'customer_name' => $bulkOrder['customer_name'],
                    'customer_email' => $bulkOrder['customer_email'],
                    'order_date' => $bulkOrder['order_date'],
                    'currency' => $bulkOrder['currency'],
                    'total' => $bulkOrder['total'],
                    'status' => $bulkOrder['status'],
                    'platform' => $bulkOrder['platform'],
                    'data' => $bulkOrder['data'],
                ]);

                foreach ($bulkOrder['items'] as $item) {
                    $this->createOrderItem($store, $item, $order);
                }

                foreach ($order->items as $item) {
                    $item->email()->create([
                        'store_id' => $store->id,
                        'order_id' => $order->id,
                        'status' => 'scheduled',
                        'scheduled_at' => now()->addDays($store->setting('emails.review_request_email_days'))
                    ]);
                }
            }

            DB::commit();

            return apiSuccess(trans('Orders created successfully'));
        } catch (Exception $exception) {
            DB::rollBack();
            return apiError($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * @param Store $store
     * @param mixed $item
     * @param Model|Order $order
     * @return void
     */
    public function createOrderItem(Store $store, mixed $item, Model|Order $order): void
    {
        $product = $store->products()->updateOrCreate([
            "unique_id" => $item['id'],
        ], [
            "name" => $item['name'],
            "image" => $item['image'],
            "url" => $item['url'],
            "description" => $item['description'],
            "specs" => $item['specs'],
            "price" => $item['price'],
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }
}
