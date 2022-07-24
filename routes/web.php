<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibrarianController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    return redirect()->route('books.index');
});

Route::get('/home', function () {
    return redirect()->route('books.index');
});

Route::any('books/search', [App\Http\Controllers\BookController::class, 'search'])->name('books.search');
Route::resource('books', BookController::class);

Route::resource('genres', GenreController::class);

Route::get('reader/borrow/pending', [ReaderController::class, 'pending'])->name('reader.borrow.pending');
Route::get('reader/borrow/accepted', [ReaderController::class, 'accepted'])->name('reader.borrow.accepted');
Route::get('reader/borrow/rejected', [ReaderController::class, 'rejected'])->name('reader.borrow.rejected');
Route::get('reader/borrow/returned', [ReaderController::class, 'returned'])->name('reader.borrow.returned');
Route::get('reader/borrow/late', [ReaderController::class, 'late'])->name('reader.borrow.late');
Route::resource('reader/borrow', ReaderController::class)->names([
    'show' => 'reader.borrow.show',
    'create' => 'reader.borrow.create',
    'store' => 'reader.borrow.store'
]);
Route::get('/librarian/borrow/index', function () {
    return redirect()->route('librarian.borrow.pending');
});
Route::get('librarian/borrow/pending', [LibrarianController::class, 'pending'])->name('librarian.borrow.pending');
Route::get('librarian/borrow/accepted', [LibrarianController::class, 'accepted'])->name('librarian.borrow.accepted');
Route::get('librarian/borrow/rejected', [LibrarianController::class, 'rejected'])->name('librarian.borrow.rejected');
Route::get('librarian/borrow/returned', [LibrarianController::class, 'returned'])->name('librarian.borrow.returned');
Route::get('librarian/borrow/late', [LibrarianController::class, 'late'])->name('librarian.borrow.late');
Route::resource('librarian/borrow', LibrarianController::class)->names([
    'show' => 'librarian.borrow.show',
    'update' => 'librarian.borrow.update',
]);
Route::get('/profile', [HomeController::class, 'profile'])->name('profile')->middleware('auth');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
