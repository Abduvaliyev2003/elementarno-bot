<?php

use App\Http\Controllers\TTSController;
use App\Http\Controllers\WebApp\HomeController;
use App\Http\Controllers\WebApp\SearchController;
use App\Http\Controllers\WebApp\WordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/word-category/{id}', [HomeController::class, 'show'])->name('word-category');
Route::get('/word/{id}', [WordController::class, 'show'])->name('word');
Route::get('/search/result', [SearchController::class, 'search'])->name('search.result');
Route::get('/tts', [TTSController::class, 'synthesizeSpeech']);
