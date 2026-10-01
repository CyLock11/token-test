<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user/get', [UserController::class, 'get'])->name('user.get');
Route::post('/user/create', [UserController::class, 'create'])->name('user.create');
Route::post('/user/login', [UserController::class, 'login'])->name('user.login');
Route::post('/user/update_username', [UserController::class, 'update_username'])->name('user.update_username');