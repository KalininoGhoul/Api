<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\UsersShifts;
use App\Models\WorkShift;

class ShiftOpen implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $shift = WorkShift::find($value);

        if ($shift == null || !$shift->active) {
            $fail("Forbidden. The shift must be active!");
        }

        $userOnShift = UsersShifts::Where('shift_id', $value)->where('user_id', auth()->user()->id);

        if (!$userOnShift) {
            $fail("Forbidden. You don't work this shift!");
        }
    }
}
