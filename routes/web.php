<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewriterController;
use App\Http\Livewire\Rewriter\Rewrite;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile' , [ProfileController::class , 'setToken'])->name('profile.setToken');
    Route::post('/profile' , [ProfileController::class , 'edit']);

    // Rewriter
    Route::get('/rewriter' , [RewriterController::class , 'index'])->name('rewriter');
    Route::get('/rewriter/result' , [RewriterController::class , 'result'])->name('rewriter.result');
});

require __DIR__.'/auth.php';
