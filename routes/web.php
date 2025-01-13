<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/all-student', [StudentController::class, 'allStudent'])->name('all-student');

Route::group(['prefix' => 'student', 'as' => 'student.'], function () {
    Route::post('store', [StudentController::class, 'studentStore'])->name('store');
});

