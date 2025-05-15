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
    $relou = "rellow";
    return view('welcome');
});

Route::get('/welcome', function () {
    $relou = "rellow";
    return response()->json([
        "message" => "hello world"
    ]);
});