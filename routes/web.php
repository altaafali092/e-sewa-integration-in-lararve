<?php

use App\Http\Controllers\EsewaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/esewa',[EsewaController::class,'esewapay'])->name('sewa.pay');
Route::get('/success',[EsewaController::class,'success'])->name('success');
Route::get('/failed',[EsewaController::class,'failed'])->name('failed');

Route::post('/version',[EsewaController::class,'version'])->name('version');
