<?php

use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login'); // routed named login sementara hehe

// Route::middleware('auth')->group(function(){
    Route::get('/admin/project', [ProjectController::class, 'index']);
    Route::get('/admin/project/edit/{id}', [ProjectController::class, 'edit']);
    Route::get('/admin/project/getData', [ProjectController::class, 'getData']);
    Route::post('/admin/project/store', [ProjectController::class, 'store']);
    Route::put('/admin/project/update', [ProjectController::class, 'update']);
    Route::delete('/admin/project/delete', [ProjectController::class, 'destroy']);
// });