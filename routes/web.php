<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\AdminController;
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


Route::controller(EventController::class)->group(function () {
    Route::get('/allevents', 'index')->name('allevents');
    Route::get('/view-event/{id}', 'view')->name('view-event');
    Route::get('/my-events', 'myEvents')->name('my-events');
    Route::post('/create-event', 'createEvent');
    Route::post('/update-event/{id}', 'updateEvent');
    Route::get('/create-event-page', [EventController::class, 'showCreateEvent'])->name('create-event-page');
    Route::post('/deactivate-event/{eventId}', 'deactivateEvent')->name('deactivate-event');
    Route::post('/activate-event/{eventId}', 'activateEvent')->name('activate-event');
});

Route::controller(FaqController::class)->group(function () {
    Route::get('/faq', [FaqController::class, 'index'])->name('faq');
});

Route::controller(AboutUsController::class)->group(function () {
    Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us');
});

Route::controller(TicketController::class)->group(function () {
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('my-tickets');
});


Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', [AdminController::class, 'showAdminPage'])->name('admin');
    Route::put('/deactivateUser/{id}', [AdminController::class, 'deactivateUser'])->name('deactivateUser');
    Route::put('/activateUser/{id}', [AdminController::class, 'activateUser'])->name('activateUser');
});



Route::controller(UserController::class)->group(function () {
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::get('/profile', [UserController::class, 'getCurrentUser'])->name('profile');
});



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

