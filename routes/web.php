<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\ReservationController;
// Home 
Route::get('/', [UserController::class,'Index'])->name('index');
// Dashboard (Based On UserType => Admin , User)
Route::get('/dashboard', [UserController::class,'Dashboard'])->middleware(['auth', 'verified'])
->name('dashboard');
// For Add Hall Page
Route::get('/addHall', [AdminController::class,'Add_Hall'])
->middleware('auth')
->name('add_hall');
//Post For Adding New Hall
Route::post('/post_add_hall', [AdminController::class,'postAdd_Hall'])
->middleware('auth')
->name('post_add_hall');
// For Resrving button
Route::post('/hall/{id}/reserve', [UserController::class, 'reserveHall'])
    ->name('hall.reserve')
    ->middleware('auth');
// For payment View
Route::get('/hall/{id}/payment', [PaymentController::class, 'show'])
    ->name('hall.payment')
    ->middleware('auth');
// For Payment Procedure
Route::post('/hall/{id}/pay', [PaymentController::class, 'pay'])
    ->name('hall.pay')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/reservations/export', [ReservationController::class, 'exportCsv']);

Route::get('/payment/success', [PaymentController::class, 'success']);
Route::get('/payment/failure', [PaymentController::class, 'failure']);


require __DIR__.'/auth.php';
