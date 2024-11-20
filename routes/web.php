<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get ('send/email' ,function(){
    // Mail::to('omarzenaldeen50@gmail.com')->send(new test());
    Mail::to('omarzenaldeen50@gmail.com')->send(new \App\Mail\Test("Hello, World!"));
});
