<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProfileController;
use App\Mail\PostCommentedMail;
use App\Models\User;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('about-us', [SiteController::class, 'aboutUs'])->name('about-us');
Route::get('search', [PostController::class, 'search'])->name('search');
Route::get('posts/{post:slug}', [PostController::class, 'show'])->name('post.show');
Route::get('category/{category:slug}', [PostController::class, 'byCategory'])->name('category');

Route::get('/notification', function () {
    $post = Post::find(1);
    $comment = $post->comments()->first();
 
    return (new PostCommentedMail($post, $comment, auth()->user()))->to(User::find(2)->email);
});