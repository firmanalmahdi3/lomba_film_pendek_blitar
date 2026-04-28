<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VoteController;
use App\Http\Controllers\Admin\UserController;

/*
|----------------------------------------------------------------------
| Festival — Web Routes
|----------------------------------------------------------------------
*/

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// About Us
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Voting
Route::prefix('voting')->name('voting.')->group(function () {
    Route::get('/',           [VotingController::class, 'index'])      ->name('index');
    Route::post('/{candidate}', [VotingController::class, 'vote'])     ->name('vote');
    Route::get('/leaderboard', [VotingController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/stats',       [VotingController::class, 'stats'])     ->name('stats');
});

// Auth
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('candidates', CandidateController::class);
    Route::post('candidates/{candidate}/reset-votes', [CandidateController::class, 'resetVotes'])->name('candidates.reset-votes');
    Route::post('candidates/{candidate}/toggle-status', [CandidateController::class, 'toggleStatus'])->name('candidates.toggle-status');
    Route::resource('categories', CategoryController::class);
    Route::get('votes', [VoteController::class, 'index'])->name('votes.index');
    Route::delete('votes/{vote}', [VoteController::class, 'destroy'])->name('votes.destroy');
    Route::post('votes/reset-all', [VoteController::class, 'resetAll'])->name('votes.reset-all');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});
