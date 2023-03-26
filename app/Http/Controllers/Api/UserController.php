<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UsersRequest;
use App\Http\Resources\LogoutErrorResource;
use App\Http\Resources\UsersCollection;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Resources\LogoutUserResource;
use App\Http\Requests\LogoutRequest;

class UserController extends Controller
{
    public function index(UsersRequest $request)
    {
        return new UsersCollection(User::all());
    }
    public function login(LoginRequest $request)
    {
        $userData = $request->validated();

        $user = User::firstWhere('login', $userData['login']);

        if (isset($user)) {
            if (Hash::check($userData['password'], $user->password)) {
                $user->update(['api_token' => Str::random(100)]);

                return [
                    'data' => [
                        'user_token' => $user->api_token,
                    ],
                ];
            }
        }
        return response([
            'error' => [
                'code' => 401,
                'message' => 'Authentication failed',
            ],
        ], 401);

    }
    public function logout(UsersRequest $request)
    {
        $token = $request->bearerToken();
        $user = User::firstWhere('api_token', $token);

        $user->update(['api_token' => null]);

        return new LogoutUserResource($request);
    }
    public function test(Request $request)
    {
        $this->authorize('create', User::class);
    }
}
