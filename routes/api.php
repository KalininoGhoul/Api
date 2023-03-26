<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);

// Admin
Route::middleware(['check.token', 'check.role:admin'])->group(function () {
    // users
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [RegisterController::class, 'store']);
    // shifts
    Route::post('/work-shift', [WorkShiftsContoller::class, 'store']);
    Route::get('/work-shift/{id}/open', [WorkShiftsContoller::class, 'open']);
    Route::get('/work-shift/{id}/close', [WorkShiftsContoller::class, 'close']);
    Route::post('/work-shift/{id}/user', [WorkShiftsContoller::class, 'addUser']);
    Route::get('/work-shift/{shift}/order', [WorkShiftsContoller::class, 'showOrders']);

});
// Waiter
Route::middleware(['check.token', 'check.role:waiter'])->group(function () {
    Route::post('/order', [OrdersController::class, 'store']);
    Route::get('/order/{order}', [OrdersController::class, 'show'])->whereUlid('order');;
    Route::get('/work-shift/{shift}/orders', [WorkShiftsContoller::class, 'showOrders'])->can('showOrders', 'shift');
    Route::patch('/order/{order}/change-status', [OrdersController::class, 'update']);
}); 
Route::patch('/order/{order}/change-status', [OrdersController::class, 'update'])->middleware(['check.token', 'check.role:waiter,cook']);
// cook
Route::middleware(['check.token', 'check.role:cook'])->group(function () {
    Route::get('/order/taken', [OrdersController::class, 'showActive']);
});