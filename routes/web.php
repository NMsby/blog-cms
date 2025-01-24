<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
//use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Frontend routes
Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

// Authentication routes
require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Posts Management
    Route::resource('posts', PostController::class);

    // Categories Management
    Route::resource('categories', CategoryController::class);

    // Tags Management
    Route::resource('tags', TagController::class);

    // Comments Management
    Route::resource('comments', CommentController::class)->except(['create', 'store', 'edit']);
    Route::post('comments/batch', [CommentController::class, 'batch'])->name('comments.batch');
    Route::get('comments/pending', [CommentController::class, 'pending'])->name('comments.pending');

    // Media Management
    Route::resource('media', MediaController::class);
    Route::get('media/{media}/download', [MediaController::class, 'download'])->name('media.download');

    // Users Management (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // User Profile
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Settings (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
    });
});
