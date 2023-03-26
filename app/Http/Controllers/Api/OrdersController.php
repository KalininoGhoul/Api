<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\ChangeOrderStatusRequest;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Resources\AttempToNotYourOrderResource;
use App\Http\Resources\ChangeNotExistResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PositionsOnOrderResource;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\ProductsOrders;
use App\Models\WorkShift;
use App\Http\Resources\TakenOrderResource;
use Illuminate\Http\Request;


class OrdersController extends Controller
{
    public function store(CreateOrderRequest $request)
    {
        $acceptedStatus = OrderStatus::where('slug', 'accepted')->first()->id;

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'create_at' => now(),
            'table_id' => $request->table_id,
            'order_status_id' => $acceptedStatus,
            'work_shift_id' => $request->work_shift_id,
            'price' => 0,
            'number_of_person' => $request->number_of_person ?? 1,
        ]);

        return (new OrderResource($order))->response();
    }
    public function show(Request $request, Order $order)
    {
        if (auth()->user()->id !== $order->user_id) {
            return (new AttempToNotYourOrderResource($request))->response()->setStatusCode(403);
        }

        $positions = ProductsOrders::where('order_id', $order->id);
        $positionsCollection = PositionsOnOrderResource::collection($positions->get());

        $priceAll = 0;

        foreach ($positionsCollection->toArray($request) as $item) {
            $priceAll += $item['price'];
        }

        return response([
            'data' => [
                'id' => $order->id,
                'table' => 'Столик №' . $order->table->number,
                'shift_workers' => $order->user->name,
                'create_at' => $order->create_at,
                'status' => $order->orderStatus->name,
                'positions' => $positionsCollection,
                'price' => $priceAll,
            ],
        ]);
    }
    public function update(ChangeOrderStatusRequest $request, Order $order)
    {
        if (auth()->user()->cannot('changeStatus', $order)) {
            return (new AttempToNotYourOrderResource($request))->response()->setStatusCode(403);
        }

        $status = OrderStatus::where('slug', $request->status)->first();
        $newStatusSlug = $status->slug;
        $currentStatus = $order->orderStatus->slug;

        $group = auth()->user()->group->slug;
        $statusList = [];

        switch ($group) {
            case 'waiter': {
                $statusList = [
                    'accepted' => 'canceled',
                    'done' => 'paid-up',
                ];
            }
            case 'cook': {
                $statusList = [
                    'accepted' => 'preparing',
                    'preparing' => 'done',
                ];
            }
        }

        $changeTo = $statusList[$currentStatus] ?? null;

        if ($changeTo !== $newStatusSlug) {
            return (new ChangeNotExistResource($request))->response()->setStatusCode(403);
        }

        $shift = $order->workShift;

        if (!$shift->active) {
            return response([
                'error' => [
                    'code' => 403,
                    'message' => "You cannot change the order status of a closed shift!"
                ],
            ], 403);
        }

        $order->update([
            'order_status_id' => $status->id,
        ]);

        return response([
            'data' => [
                'id' => $order->id,
                'status' => $newStatusSlug,
            ],
        ]);
    }
    public function showActive()
    {
        $shift = WorkShift::where('active', true)->first();

        $acceptedStatus = OrderStatus::firstWhere('slug', 'accepted')->id;
        $preparingStatus = OrderStatus::firstWhere('slug', 'preparing')->id;

        $orders = Order::where('work_shift_id', $shift->id)
            ->where('order_status_id', $acceptedStatus)
            ->orWhere('order_status_id', $preparingStatus)->get();
        
        return OrderResource::collection($orders)->response();
    }
}
