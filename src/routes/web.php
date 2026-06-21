<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminAttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/admin/login', function () {
    return view('auth.login');
})->name('admin.login');

Route::middleware(['auth'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::post('/attendance/rest-in', [AttendanceController::class, 'restIn'])->name('attendance.rest-in');
    Route::post('/attendance/rest-out', [AttendanceController::class, 'restOut'])->name('attendance.rest-out');
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'detail'])->name('attendance.detail');
    Route::post('/attendance/detail/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
});

Route::middleware(['auth'])->prefix('admin')->group(function(){
    Route::get('/attendance/list', [AdminAttendanceController::class, 'list'])->name('admin.attendance.list');
    Route::get('/attendance/detail/{id}', [AdminAttendanceController::class, 'detail'])->name('admin.attendance.detail');
});