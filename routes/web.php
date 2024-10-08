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
    return view('/');
});


Route::get('/', 'App\Http\Controllers\Customer_controller@index');
Route::get('login', 'App\Http\Controllers\Customer_controller@index');
Route::get('register', 'App\Http\Controllers\Customer_controller@register');
Route::post('signup', 'App\Http\Controllers\Customer_controller@signup');
Route::get('dashboard', 'App\Http\Controllers\Customer_controller@dashboard');