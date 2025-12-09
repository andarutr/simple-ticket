<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Karyawan\TaskController;

Route::get('/', function () {
    return view('welcome');
})->name('login'); // routed named login sementara hehe

// Route::middleware('auth')->group(function(){
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // Admin
    Route::get('/admin/project', [ProjectController::class, 'index']);
    Route::get('/admin/project/edit/{id}', [ProjectController::class, 'edit']);
    Route::get('/admin/project/getData', [ProjectController::class, 'getData']);
    Route::post('/admin/project/store', [ProjectController::class, 'store']);
    Route::put('/admin/project/update', [ProjectController::class, 'update']);
    Route::delete('/admin/project/delete', [ProjectController::class, 'destroy']);
    // Karyawan
    Route::get('/karyawan/task', [TaskController::class, 'index']);
    Route::get('/karyawan/task/summary', [TaskController::class, 'summary']);
    Route::get('/karyawan/task/getData', [TaskController::class, 'getData']);
    Route::get('/karyawan/task/create', [TaskController::class, 'create']);
    Route::post('/karyawan/task/store', [TaskController::class, 'store']);
    Route::get('/karyawan/task/edit/{id}', [TaskController::class, 'edit']);
    Route::put('/karyawan/task/update', [TaskController::class, 'update']);
    Route::delete('/karyawan/task/delete', [TaskController::class, 'destroy']);
// });