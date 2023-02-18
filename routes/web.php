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

// 'sanctum' used for managing api tokens and session cookies
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('user_profile', ['username' => auth()->user()->username]);
    });

    Route::get('/home', function () {

        $profile = auth()->user();
        // take only 3 results
        $i_follow = $profile->iFollow()->take(3);
        $to_follow = $profile->otherUsers()->take(3);

        return view('home', [
            'posts' => auth()->user()->home(),
            'profile' => $profile,
            'i_follow' => $i_follow,
            'to_follow' => $to_follow,
        ]);
    })->name('home');

    Route::get('/followers', function () {
        return view('followers', [
            'profile' => auth()->user(), // the current user
            'followers' => auth()->user()->followers()->paginate(10), // a list of who follow me
        ]);
    })->name('followers');

    Route::get('/following', function () {
        return view('following', [
            'profile' => auth()->user(), // the current user
            'following' => auth()->user()->follows()->paginate(10), // a list of who i follow
        ]);
    })->name('following');

    Route::get('/inbox', function () {
        $user = auth()->user();

        $requests = $user->followRequest();
        $pendings =  $user->pendingFollowRequest();

        return view('inbox', [
            'profile' => $user,
            'requests' => $requests,
            'pendings' => $pendings,
        ]);
    })->name('inbox');

    Route::get('/explore', function () {
        return view('explore', [
            'profile' => auth()->user(), // the current user
            'posts' => auth()->user()->explore(),
        ]);
    })->name('explore');

    Route::resource('/comments', CommentController::class);

    Route::resource('/posts', PostController::class);
});



// dont put any route after this as it will return error for ex. if i went to route followers it will catch "followers" in '{username}' and search for a user called followers
Route::get('{username}', function ($username) {
    $user = User::where('username', $username)->first(); // search the user table with the 'username' field that matches the value of the $username The first() method is used to retrieve only the first result of the query

    if ($user == null) {
        abort(404);
    } else {
        $posts = $user->posts()->paginate(3);
        return view('profile', [
            'profile' => $user,
            'posts' => $posts
        ]);
    }
})->name('user_profile'); // name of the route
