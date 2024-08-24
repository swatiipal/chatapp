<?php

use App\Http\Controllers\ChatController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    $users = User::where('id', '!=', auth()->user()->id)->get();
    return view('dashboard', [
        'users' => $users,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/chat/{id}', function ($id) {
    return view('chat', [
        'id' => $id,
    ]);
})->middleware(['auth', 'verified'])->name('chat');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('/api/chat/{id}', [ChatController::class, 'fetchMessages']);
Route::post('/api/chat/{id}', [ChatController::class, 'sendMessage']);
require __DIR__ . '/auth.php';
