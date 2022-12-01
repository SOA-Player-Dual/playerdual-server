<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PlayerGameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;

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

// User routes
Route::get('/user/id/{id}', [UserController::class, 'show']);
Route::get('/user/{urlCode}', [UserController::class, 'showByURLCode']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/gender/{gender}', [UserController::class, 'getByGender']);

// Game routes
Route::get('/game', [GameController::class, 'index']);
Route::get('/game/{id}', [GameController::class, 'show']);
Route::post('/game', [GameController::class, 'store']);

// OTP routes
Route::post('/otp/send', [OTPController::class, 'sendOTP'])->name('sendOTP');
Route::post('/otp/verify', [OTPController::class, 'verifyOTP'])->name('verifyOTP');

// Contract routes
Route::post('/contract', [ContractController::class, 'store']);
Route::get('/contract/user/{id}', [ContractController::class, 'showByUserID']);
Route::get('/contract/player/{id}', [ContractController::class, 'showByUserPlayerID']);
Route::put('/contract/{id}', [ContractController::class, 'update']);
Route::get('/contract/{id}', [ContractController::class, 'show']);

// Rating routes
Route::get('/rating/{id}', [RatingController::class, 'show']);
Route::post('/rating', [RatingController::class, 'store']);

// Follow routes
Route::post('/follow', [FollowController::class, 'store']);
Route::get('/follow/follower/{id}', [FollowController::class, 'showFollower']);
Route::get('/follow/following/{id}', [FollowController::class, 'showFollowing']);
Route::put('/follow', [FollowController::class, 'destroy']);

// Donate routes
Route::post('/donate', [DonateController::class, 'store']);
Route::get('/donate/{id}', [DonateController::class, 'show']);

// Search routes
Route::get('/search', [SearchController::class, 'index']);

// Post routes
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/post', [PostController::class, 'store']);
Route::put('/post/{id}', [PostController::class, 'update']);
Route::delete('/post/{id}', [PostController::class, 'destroy']);

// PlayerGame routes
Route::post('/playergame', [PlayerGameController::class, 'store']);
Route::get('/playergame/{id}', [PlayerGameController::class, 'show']);

// Transaction routes
Route::post('/transaction', [TransactionController::class, 'store']);
Route::put('/transaction/{id}', [TransactionController::class, 'update']);
Route::get('/transaction/{id}', [TransactionController::class, 'show']);
Route::get('/transaction', [TransactionController::class, 'index']);

// Recover password routes
Route::post('/recover', [UserController::class, 'recoverPassword']);
Route::post('/recover/verify', [UserController::class, 'verifyRecoverPassword']);

// Admin routes
Route::put('/admin/{id}/banned', [AdminController::class, 'bannedUserByID']);
Route::put('/admin/{id}/active', [AdminController::class, 'activeUserByID']);
