<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('home', [DashboardController::class, 'index'])->name('dashboard');

    // student route
    Route::group(['prefix' => 'student', 'as' => 'student.'], function () {
        Route::get('all', [StudentController::class, 'allStudent'])->name('all');
        Route::post('store', [StudentController::class, 'studentStore'])->name('store');
        Route::get('edit/{id}', [StudentController::class, 'edit'])->name('edit');
        Route::post('delete/{id}', [StudentController::class, 'delete'])->name('delete');
    });


});

