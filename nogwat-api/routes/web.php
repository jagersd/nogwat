<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;

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
    return view('webhome');
});

Route::get('/privacy-policy', function (){
    return view('privacy-policy');
});

Route::get('/forget-me', function (){
    return view('forget-me');
});

Route::post('/forget-me',[Auth::class, 'sendForgetMeNowRequest']);

