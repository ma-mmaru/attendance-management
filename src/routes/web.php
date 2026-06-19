<?php

use Illuminate\Support\Facades\Route;
use APP\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/admin/login', function () {
    return view('auth.login');
})->name('admin.login');

Route::get('/attendance', function () {
    return view('attendance');
})->name('attendance');

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::post('/attendance/rest-in', [AttendanceController::class, 'restIn'])->name('attendance.reset-in');
    Route::post('/attendance/rest-out', [AttendanceController::class, 'restOut'])->name('attendance.rest-out');
});