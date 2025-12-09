<?php

use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login'); // routed named login sementara hehe

// Route::middleware('auth')->group(function(){
    Route::get('/admin/project', [ProjectController::class, 'index']);
// });