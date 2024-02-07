<?php

use App\Http\Controllers\AuditSettingController;
use App\Http\Controllers\AutoriteContractanteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\MarketTypeController;
use App\Http\Controllers\ModePassationController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('market-types', MarketTypeController::class);

Route::resource('audit-settings', AuditSettingController::class);

Route::post('/audit-settings', [AuditSettingController::class, 'storeOrUpdate'])->name('audit-settings.storeOrUpdate');

Route::resource('markets', MarketController::class);

Route::resource('mode-passations', ModePassationController::class);

Route::resource('autorite-contractantes', AutoriteContractanteController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
