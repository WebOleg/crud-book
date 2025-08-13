<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('authors', AuthorController::class);
Route::resource('books', BookController::class);

Route::get('/api/authors', [AuthorController::class, 'getAuthors']);

Route::get('/health', [App\Http\Controllers\HealthController::class, 'check']);
