<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\EventController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\AdminController;



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
Route::redirect('/', '/all-events');

Route::controller(CommentController::class)->group(function (){
    Route::post('/edit-comment', [CommentController::class, 'editComment'])->name('editComment');
    Route::post('/submit-comment', [CommentController::class, 'submitComment'])->name('submitComment');
    Route::post('/hide-comment/{commentId}', [CommentController::class, 'hideComment'])->name('hideComment');
    Route::post('/show-comment/{commentId}', [CommentController::class, 'showComment'])->name('showComment');
Route::post('/delete-comment', [CommentController::class, 'deleteComment'])->name('deleteComment');
Route::post('/like-comment', [CommentController::class, 'likeComment'])->name('likeComment');
Route::post('/unlike-comment', [CommentController::class, 'unlikeComment'])->name('unlikeComment');

});


Route::post('/submit-report', [ReportController::class, 'submitReport'])->name('submitReport');

Route::controller(RatingController::class)->group(function (){
Route::post('/submit-rating/{eventId}', [RatingController::class, 'submitRating'])->name('submitRating');
Route::post('/edit-rating/{eventId}', [RatingController::class, 'editRating'])->name('editRating');
});




Route::post('/submit-report', [ReportController::class, 'submitReport'])->name('submitReport');
Route::post('/delete-report/{reportId}', [AdminController::class, 'deleteReport'])->name('delete-report');


Route::post('/send', [MailController::class, 'send']);

Route::controller(EventController::class)->group(function () {
    Route::get('/all-events', 'index')->name('all-events');
    Route::get('/view-event/{id}', 'view')->name('view-event');
    Route::get('/my-events', 'myEvents')->name('my-events');
    Route::post('/create-event', 'createEvent');
    Route::post('/update-event/{id}', 'updateEvent');
    Route::get('/purchase-tickets', [EventController::class, 'purchaseTickets'])->name('purchase-tickets');
    Route::get('/create-event', [EventController::class, 'showCreateEvent'])->name('create-event');
    Route::post('/create-ticket-type/{event}', 'createTicketType')->name('create-ticket-type');
    Route::get('/search-events', [EventController::class, 'searchEvents'])->name('search-events');
    Route::post('/deactivate-event/{eventId}', 'deactivateEvent')->name('deactivate-event');
    Route::post('/activate-event/{eventId}', 'activateEvent')->name('activate-event');
    Route::get('/generate-qrcode/{ticketInstanceId}', [EventController::class, 'generateQRCode'])->name('generate-qrcode');
    Route::get('/view-event/{id}', [EventController::class, 'show'])->name('view-event');
    Route::get('/event/{eventId}', 'EventController@show');
    Route::get('/api/events/{id}/charts/', [EventController::class, 'charts']);
});

Route::controller(FaqController::class)->group(function () {
    Route::get('/faq', [FaqController::class, 'index'])->name('faq');
});


Route::controller(AboutUsController::class)->group(function () {
    Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us');
});

Route::controller(TicketController::class)->group(function () {
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('my-tickets')->middleware('auth');
    Route::post('/update-ticket-stock/{ticketTypeId}', [TicketController::class, 'updateTicketStock'])->name('updateTicketStock');
});


Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', [AdminController::class, 'showAdminPage'])->name('admin');
    Route::put('/deactivateUser/{id}', [AdminController::class, 'deactivateUser'])->name('deactivateUser');
    Route::put('/activateUser/{id}', [AdminController::class, 'activateUser'])->name('activateUser');
    Route::get('/count', [AdminController::class, 'showUserCount'])->name('count');
    Route::get('/getActiveUserCount', [AdminController::class, 'getActiveUserCount']);
    Route::get('/getInactiveUserCount', [AdminController::class, 'getInactiveUserCount']);
    Route::get('/getActiveEventCount', [AdminController::class, 'getActiveEventCount']);
    Route::get('/getInactiveEventCount', [AdminController::class, 'getInactiveEventCount']);
    Route::get('/getEventCountByMonth/{month}', [AdminController::class, 'getEventCountByMonth']);
    Route::get('/getEventCountByDay/{day}', [AdminController::class, 'getEventCountByDay']);
    Route::get('/getEventCountByYear/{year}', [AdminController::class, 'getEventCountByYear']);
});


Route::controller(UserController::class)->group(function () {
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::get('/profile', [UserController::class, 'getCurrentUser'])->name('profile')->middleware('auth');
    Route::post('/add-rating', [UserController::class, 'addRating'])->name('add-rating');
});




// File Upload Route
Route::controller(FileController::class)->group(function () {
    Route::post('/file/upload', [FileController::class, 'upload'])->name('file.upload');
});


// API


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

//ver o nome da função que faz isto
Route::post('/post/comment', [PostController::class, 'like']);

Route::controller(NotificationController::class)->group(function (){
    Route::get('/get-notifications', 'getNotifications')->name('get-notifications')->middleware('auth');
    //Route::post('/notifications/mark-as-read', 'NotificationController@markAsRead')->name('notifications.markAsRead')->middleware('auth');
});


Route::controller(StripeController::class)->group(function () {
    Route::get('/payment', 'showPaymentForm')->name('payment');
    Route::post('/payment/{event_id}', 'processPayment')->name('payment');
});
