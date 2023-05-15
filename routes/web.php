<?php

use App\Http\Controllers\ApiFoxController;
use App\Http\Controllers\ChatGptController;
use App\Http\Controllers\ChatGptConversationController;
use App\Http\Controllers\FoxController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PenguinController;
use App\Http\Controllers\WebCrawlerController;
use Illuminate\Support\Facades\Route;

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

/*===Home===*/
Route::get('/', [GalleryController::class, 'index'])->name('home');

/*===Webcrawler Example get all Information===*/
Route::get('/webcrawler', [WebCrawlerController::class, 'index'])->name('webcrawler');

/*===Search Content Scrapp===*/
Route::get('/search', [FoxController::class, 'index'])->name('fox_search');
//Route::post('/search', [ApiFoxController::class, 'getRequestUrl'])->name('fox_urlsearch');

/*===ChatGpt===*/
Route::get('/chatgptcustom', [ChatGptController::class, 'index'])->name('chatgpt');
Route::get('/chatgptconversation', [ChatGptConversationController::class, 'index'])->name('chatgptconversation');

/*====Pinguim===*/
Route::get('/penguinb2b', [PenguinController::class, 'indexB2B'])->name('penguinb2b');
Route::get('/penguinb2c', [PenguinController::class, 'indexB2C'])->name('penguinb2c');