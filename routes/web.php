<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassBookingController;
use App\Http\Controllers\ClassScheduleController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('home', [DashboardController::class, 'index'])->name('dashboard');


    // admin all route list
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        // teacher route
        Route::group(['prefix' => 'teacher', 'as' => 'teacher.'], function () {
            Route::get('all', [TeacherController::class, 'all'])->name('all');
            Route::post('store', [TeacherController::class, 'store'])->name('store');
            Route::get('edit/{id}', [TeacherController::class, 'edit'])->name('edit');
            Route::post('delete/{id}', [TeacherController::class, 'delete'])->name('delete');
            Route::get('get-state', [TeacherController::class, 'getState'])->name('get-state');
        });

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

        // class schedules route
        Route::group(['prefix' => 'class-schedule', 'as' => 'class-schedule.'], function () {
            Route::get('list', [ClassScheduleController::class, 'list'])->name('list');
            Route::post('store', [ClassScheduleController::class, 'store'])->name('store');
            Route::get('edit/{id}', [ClassScheduleController::class, 'edit'])->name('edit');
            Route::post('delete/{id}', [ClassScheduleController::class, 'delete'])->name('delete');
            Route::get('get-filter-course', [ClassScheduleController::class, 'getFilterCourse'])->name('get-filter-course');
        });

        // student class booking route
        Route::group(['prefix' => 'class-booking', 'as' => 'class-booking.'], function () {
            Route::get('list', [ClassBookingController::class, 'list'])->name('list');
            Route::get('add', [ClassBookingController::class, 'add'])->name('add');
            Route::get('get-filter-course', [ClassBookingController::class, 'getFilterCourse'])->name('get-filter-course');
            Route::get('get-teacher-filter', [ClassBookingController::class, 'getTeacherFilter'])->name('get-teacher-filter');
            Route::get('get-teacher-class-list', [ClassBookingController::class, 'getTeacherClassList'])->name('get-teacher-class-list');


            Route::post('store', [ClassBookingController::class, 'store'])->name('store');
            Route::get('edit/{id}', [ClassBookingController::class, 'edit'])->name('edit');
            Route::post('delete/{id}', [ClassBookingController::class, 'delete'])->name('delete');
        });

    });


});

