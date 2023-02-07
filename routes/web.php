<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Models\User;
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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('user_profile', ['username' => auth()->user()->username]);
    })->name('dashboard');
});



// 'sanctum' used for managing api tokens and session cookies
Route::get('/followers', function () {
    return view('followers', [
        'profile' => auth()->user(), // the current user
        'followers' => auth()->user()->followers()->get(), // a list of who follow me
    ]);
})->name('followers')->middleware('auth:sanctum'); // to make auth throw session cookies


Route::get('/following', function () {
    return view('following', [
        'profile' => auth()->user(), // the current user
        'following' => auth()->user()->follows()->get(), // a list of who i follow
    ]);
})->name('following')->middleware('auth:sanctum'); // to make auth throw session cookies

// dont put any route after this as it will return error for ex. if i went to route followers it will catch "followers" in '{username}' and search for a user called followers
Route::get('{username}', function ($username) {
    $user = User::where('username', $username)->first(); // search the user table with the 'username' field that matches the value of the $username The first() method is used to retrieve only the first result of the query

    if ($user == null) {
        abort(404);
    } else {
        $posts = $user->posts;
        return view('profile', [
            'profile' => $user,
            'posts' => $posts
        ]);
    }
})->name('user_profile'); // name of the route

Route::resource('/posts', PostController::class);

Route::resource('/comments', CommentController::class);
