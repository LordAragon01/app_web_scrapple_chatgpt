<?php

use App\Http\Controllers\ApiFoxController;
use App\Http\Controllers\ChatGptController;
use App\Http\Controllers\ChatGptConversationController;
use App\Http\Controllers\PenguinApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

/*=== WebCrawler Search ===*/
//Route::get('/searchapi', [ApiFoxController::class, 'requestUrlContent'])->name('searchapi');
Route::post('/searchapi', [ApiFoxController::class, 'getRequestUrl'])->name('getsearchapi');
//Route::post('/searchapiget?indicateurl=""', [ApiFoxController::class, 'getRequestUrl']);

/*=== OpenApi ChatGpt ===*/
//Route::get('/openapicon', [ChatGptController::class, 'openApiCon'])->name('openapicon');
Route::post('/openapicon', [ChatGptController::class, 'openApiCon'])->name('openapicon');
//Route::get('/openapiconchat', [ChatGptConversationController::class, 'openApiChat'])->name('openapiconchat');
Route::post('/openapiconchat', [ChatGptConversationController::class, 'openApiChat'])->name('openapiconchat');
Route::post('/openapiconclearchat', [ChatGptConversationController::class, 'openApiClearChat'])->name('openapiconclearchat');

/*=== Penguin Project ===*/
Route::post('/generatenumber', [PenguinApiController::class, 'generateNumber'])->name('generatenumber');
Route::get('/customerdata', [PenguinApiController::class, 'customerData'])->name('customerdata');
Route::post('/currentnumber', [PenguinApiController::class, 'getCurrentNumber'])->name('currentnumber');
Route::post('/callnumber', [PenguinApiController::class, 'callNumber'])->name('callnumber');