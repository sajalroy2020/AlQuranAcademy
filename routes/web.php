<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CountryController;
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
        Route::get('get-state', [StudentController::class, 'getState'])->name('get-state');
    });

     // Country route
     Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
        Route::get('all', [CountryController::class, 'all'])->name('all');
        Route::post('store', [CountryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CountryController::class, 'edit'])->name('edit');
        Route::post('delete/{id}', [CountryController::class, 'delete'])->name('delete');
    });

    // admin route list 
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        // state route
        Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
            Route::get('all', [StateController::class, 'all'])->name('all');
            Route::post('store', [StateController::class, 'store'])->name('store');
            Route::get('edit/{id}', [StateController::class, 'edit'])->name('edit');
            Route::post('delete/{id}', [StateController::class, 'delete'])->name('delete');
        });

        // course route
        Route::group(['prefix' => 'course', 'as' => 'course.'], function () {
            Route::get('all', [CourseController::class, 'all'])->name('all');
            Route::post('store', [CourseController::class, 'store'])->name('store');
            Route::get('edit/{id}', [CourseController::class, 'edit'])->name('edit');
            Route::post('delete/{id}', [CourseController::class, 'delete'])->name('delete');
        });
    });


});

