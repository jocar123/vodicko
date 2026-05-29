<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserController;

Route::get('/twig', function () {
    return view('welcome', ['name' => 'Jovan']);
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', [TourController::class, 'index'])->name('home');
Route::get('/home/{id}', [TourController::class, 'showTour'])->name('tour.show');

Route::post('/home/{tourId}/add', [TourController::class, 'addUserToTour'])->name('tour.addUserToTour');
Route::post('/home/{tourId}/remove', [TourController::class, 'removeUserFromTour'])->name('tour.removeUserFromTour');

Route::get('/home', [FilterController::class, 'showHome'])->name('home');

Route::post('/tours', [TourController::class, 'store'])->name('tour.store');
Route::delete('/tour/{id}', [TourController::class, 'destroy'])->name('tour.destroy');
Route::get('/tours/create', [TourController::class, 'create'])->name('tour.create');

Route::get('/destinations/create', [DestinationController::class, 'create'])->name('destination.create');
Route::post('/destinations', [DestinationController::class, 'store'])->name('destination.store');

Route::get('/users', [UserController::class, 'showUsers'])->name('users.show');
Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

Route::get('/usersInTours', [TourController::class, 'usersInTours'])->name('usersInTours.show');

Route::put('/users/{id}/addManager', [UserController::class, 'addManager'])->name('users.addManager');
Route::put('/users/{id}/removeManager', [UserController::class, 'removeManager'])->name('users.removeManager');

Route::get('/users/export-excel', [UserController::class, 'exportUsers'])->name('users.exportUsers');
Route::get('/usersInTours/export-excel', [TourController::class, 'exportUsersInTours'])->name('users.exportUsersInTours');

Route::get('/users/export-pdf', [UserController::class, 'exportUsersPdf'])->name('users.exportPdf');
Route::get('/usersinTours/export-pdf', [TourController::class, 'exportUsersInToursPdf'])->name('users.exportUsersInToursPdf');

