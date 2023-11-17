<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


use App\Http\Controllers\EventController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

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

// Home
Route::redirect('/', '/allevents');

/*// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});*/


Route::get('/allevents', [EventController::class, 'index'])->name('allevents');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/view-event/{id}', [EventController::class, 'view'])->name('view-event');

Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us');

Route::get('/my-events', [EventController::class, 'myEvents'])->name('my-events');

Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('my-tickets');

Route::get('/create-event', [EventController::class, 'showCreateEvent'])->name('create-event');

Route::get('/admin', [UserController::class, 'showAdminPage'])->name('admin');



// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

//Route::post('/purchase-tickets/{event_id}', [TicketController::class, 'purchase'])->name('purchase-tickets');
Route::post('/create-event', [EventController::class, 'createEvent']);

