<?php

namespace App\Http\Requests\Shifts;

use App\Http\Requests\ApiValidate;
use App\Models\User;

class StoreShiftRequest extends ApiValidate
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'start' => ['required', 'date_format:Y-m-d H:i'],
            'end' => ['required', 'date_format:Y-m-d H:i'],
        ];
    }
}
