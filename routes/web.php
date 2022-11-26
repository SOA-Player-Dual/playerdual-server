<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // view with data
    // $mailData['type'] = '11';
    $mailData['otp'] = '1';
    $mailData['name'] = '2';
    $mailData['phone'] = '4';
    return view('register', [
        'mailData' => $mailData
    ]);
});
