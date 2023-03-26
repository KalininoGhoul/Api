<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\ApiValidate;
use App\Models\User;

class StoreUserRequest extends ApiValidate
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
            'name' => ['required', 'string'],
            'login' => ['required', 'unique:users,login'],
            'password' => ['required'],
            'surname' => ['string'],
            'patronymic' => ['string'],
        ];
    }
}
