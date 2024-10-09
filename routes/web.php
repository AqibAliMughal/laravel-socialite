<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })
// ->middleware(['auth', 'verified'])
// ->name('dashboard');

Route::get('/test', function () {
    return ('test');
})->name('test');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/socialite/{driver}', [SocialLoginController::class, 'toProvider'])->where('driver', 'github|google');
Route::get('/auth/{driver}/login',[SocialLoginController::class, 'handleCallBack'])->where('driver', 'github|google');

require __DIR__.'/auth.php';
