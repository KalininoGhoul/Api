<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function changeStatus(User $user, Order $order)
    {
        if ($user->group->slug == 'cook') {
            return true;
        }

        return $order->user_id == $user->id;
    }
}
