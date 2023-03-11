<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


// Route de la page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/upload-media', [Controller::class, 'uploadMedia'])->middleware('auth')->name('upload.media');
Route::delete('/medias/{id}', [Controller::class, 'deleteMedia'])->middleware('auth')->name('delete.video');

// Routes d'authentification
Auth::routes();

// Routes du profil utilisateur
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile.index');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::put('/profile/avatar', [Controller::class, 'uploadMedia'])->middleware('auth')->name('profile.update.avatar');


// Route pour afficher le profil d'un utilisateur spécifique
Route::get('/profile/{user}', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');
