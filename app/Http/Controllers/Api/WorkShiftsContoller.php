<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttempToNotYourOrderResource;
use Illuminate\Http\Request;
use App\Http\Requests\ApiValidate;
use App\Http\Requests\Shifts\AddUserRequest;
use App\Http\Requests\Shifts\EditShiftRequest;
use App\Http\Requests\Shifts\StoreShiftRequest;
use App\Models\User;
use App\Models\UsersShifts;
use App\Models\WorkShift;
use Illuminate\Support\Carbon;
use App\Models\Order;
use App\Http\Resources\OrderResource;

class WorkShiftsContoller extends Controller
{
    public function store(StoreShiftRequest $request)
    {
        $start = new Carbon($request->start);
        $end = new Carbon($request->end);

        if ($start->isPast() || $end < $start) {
            return response([
                'error' => [
                    'code' => 400,
                    'message' => 'Wrong date',
                ],
            ], 400);
        }

        $workShift = WorkShift::create($request->all());

        return response([
            'id' => $workShift->id,
            'start' => $workShift->start,
            'end' => $workShift->end,
        ], 201);
    }
    public function open(EditShiftRequest $request, $id)
    {

        $openedShifts = WorkShift::where('active', true)->get();

        if ($openedShifts->isEmpty()) {
            $shift = WorkShift::find($id);
            $shift->update(['active' => true]);

            return $shift;
        }

        return response([
            'error' => [
                'code' => 403,
                'message' => "Forbidden. There are open shifts!"
            ],
        ], 403);
    }
    public function close(ApiValidate $request, $id)
    {
        $shift = WorkShift::find($id);

        if ($shift->active) {
            $shift->update(['active' => false]);

            return $shift;
        }

        return response([
            'error' => [
                'code' => 403,
                'message' => "Forbidden. The shift is already closed!"
            ],
        ], 403);
    }
    public function addUser(AddUserRequest $request, Int $shiftId)
    {
        $userOnShift = UsersShifts::Where('user_id', $request->user_id)
                                    ->where('shift_id', $shiftId)
                                    ->first();

        if ($userOnShift !== null) {
            return response([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden. The worker is already on shift!',
                ]
            ], 403);
        }

        $user = User::find($request->user_id);

        UsersShifts::create([
            'user_id' => $user->id,
            'shift_id' => $shiftId,
        ]);

        return response([
            'data' => [
                'id_user' => $user->id,
                'status' => 'added',
            ]
        ]);
    }
    public function showOrders(Request $request, WorkShift $shift)
    {
        if (!auth()->user()->can('showOrders', $shift)) {
            return (new AttempToNotYourOrderResource($request))->response()->setStatusCode(403);
        }

        $orders = Order::where('work_shift_id', $shift->id);

        $amountForAll = $orders->sum('price');

        return response([
            'data' => [
                'id' => $shift->id,
                'start' => $shift->start,
                'end' => $shift->end,
                'active' => $shift->active,
                'orders' => OrderResource::collection($orders->get()),
                'amount_for_all' => +$amountForAll,
            ],
        ]);
    }
}
