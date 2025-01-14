<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;

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

     // Country route
     Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
        Route::get('all', [CountryController::class, 'all'])->name('all');
        Route::post('store', [CountryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CountryController::class, 'edit'])->name('edit');
        Route::post('delete/{id}', [CountryController::class, 'delete'])->name('delete');
    });

    // state route
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
            Route::get('all', [StateController::class, 'all'])->name('all');
            Route::post('store', [StateController::class, 'store'])->name('store');
            Route::get('edit/{id}', [StateController::class, 'edit'])->name('edit');
            Route::post('delete/{id}', [StateController::class, 'delete'])->name('delete');
        });
    });


});

