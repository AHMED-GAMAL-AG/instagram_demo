<?php

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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group( function () {
    Route::get('/dashboard', function () {
        return redirect()->route('user_profile' , ['username' => auth()->user()->username]);
    })->name('dashboard');
});

Route::get('{username}', function ($username) {
    $user = User::where('username', $username)->first(); // search the user table with the 'username' field that matches the value of the $username The first() method is used to retrieve only the first result of the query
    if ($user == null) {
        abort(404);
    } else {
        return view('profile', ['profile' => $user]);
    }
})->name('user_profile'); // name of the route
