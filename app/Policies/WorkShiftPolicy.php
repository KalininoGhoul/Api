<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UsersShifts;
use App\Models\WorkShift;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WorkShiftPolicy
{
    use HandlesAuthorization;

    public function showOrders(User $user, WorkShift $shift)
    {
        if ($user->group->slug == 'admin') {
            return true;
        }

        $userOnShift = UsersShifts::where('shift_id', $shift->id)->where('user_id', $user->id)->get();

        if ($userOnShift->isEmpty()) {
            return false;
        }

        return true;
    }
}
