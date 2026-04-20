<?php

use App\Http\Controllers\EmpolyeeServiceIndexController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('services/employee/{employee:slug}', EmpolyeeServiceIndexController::class)->name('employee.services');
