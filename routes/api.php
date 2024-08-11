<?php

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\ScopeController;
use Laravel\Passport\Http\Controllers\TransientTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Auth::routes();

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh']);
Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh']);
Route::get('/oauth/scopes', [ScopeController::class, 'all']);
Route::get('/oauth/clients', [ClientController::class, 'forUser']);
Route::post('/oauth/clients', [ClientController::class, 'store']);
Route::put('/oauth/clients/{client_id}', [ClientController::class, 'update']);
Route::delete('/oauth/clients/{client_id}', [ClientController::class, 'destroy']);
Route::get('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'forUser']);
Route::post('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
Route::delete('/oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);
