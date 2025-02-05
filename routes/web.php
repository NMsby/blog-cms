<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\PostController as AuthorPostController;
use App\Http\Controllers\Author\CommentController as AuthorCommentController;
use App\Http\Controllers\Author\MediaController as AuthorMediaController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
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

// Guest routes
Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');

// Blog routes
Route::name('blog.')->group(function () {
    Route::get('/blog', [BlogController::class, 'index'])->name('index');
    Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('show');
    Route::get('/category/{category:slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag:slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/search/suggestions', [BlogController::class, 'searchSuggestions'])->name('blog.search.suggestions');
});

// Frontend comment routes
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

// Authentication routes
require __DIR__.'/auth.php';

// Notification routes
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationsController::class, 'index'])->name('index');
    Route::post('/{id}/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('markAsRead');
    Route::post('/mark-all-read', [NotificationsController::class, 'markAllAsRead'])->name('markAllAsRead');
    Route::delete('/{id}', [NotificationsController::class, 'destroy'])->name('destroy');
    Route::post('/clear-all', [NotificationsController::class, 'clearAll'])->name('clearAll');
    Route::get('/count', [NotificationsController::class, 'getUnreadCount'])->name('count');
    Route::get('/{id}', [NotificationsController::class, 'show'])->name('show');
    Route::get('/preferences', [NotificationsController::class, 'preferences'])->name('preferences');
    Route::post('/preferences', [NotificationsController::class, 'updatePreferences'])->name('preferences.update');
    Route::get('/notifications/partial', [NotificationsController::class, 'getPartial'])->name('notifications.partial');
});

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

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

    // TinyMCE Image Upload
    Route::post('upload/image', [UploadController::class, 'imageUpload'])->name('upload.image');
});


// Author routes
Route::middleware(['auth', 'verified', 'role:author'])->prefix('author')->name('author')->group(function () {
    // Dashboard
    Route::get('/', [AuthorDashboardController::class, 'index'])->name('author.dashboard');

    //Posts
    Route::resource('posts', AuthorPostController::class)->except(['destroy']);

    //Comments
    Route::resource('comments', AuthorCommentController::class)->only(['index', 'show']);
    Route::post('comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    //Media
    Route::resource('media', AuthorMediaController::class);
    Route::get('media/selector', [AuthorMediaController::class, 'selector'])->name('media.selector');

    //Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
