<?php


use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

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

// Authentication...
$limiter = config('fortify.limiters.login');
$twoFactorLimiter = config('fortify.limiters.two-factor');

Route::post('/login', [AuthController::class, 'login'])->middleware(array_filter(['guest',
        $limiter ? 'throttle:'.$limiter : null,
]));


// Registration...
if (Features::enabled(Features::registration())) {
    Route::post('/register/{email}', [RegisteredUserController::class, 'store'])->middleware(['guest'])->name('register');
}

// Authenticated...
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin routes...
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/invite/{email}', [UserController::class, 'invite']);
});

