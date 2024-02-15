<?php

use App\Http\Controllers\AttributaireController;
use App\Http\Controllers\AuditSettingController;
use App\Http\Controllers\AutoriteContractanteController;
use App\Http\Controllers\CPMPController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\MarketImportController;
use App\Http\Controllers\MarketTypeController;
use App\Http\Controllers\ModePassationController;
use App\Http\Controllers\SecteurController;
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

Route::get('/markets/to_audit/{exportType?}', [MarketController::class, 'getFilteredMarkets'])->name('markets.toAudit');
Route::resource('markets', MarketController::class);

Route::resource('mode-passations', ModePassationController::class);

Route::resource('autorite-contractantes', AutoriteContractanteController::class);

Route::resource('cpmps', CPMPController::class);

Route::resource('secteurs', SecteurController::class);

Route::resource('attributaires', AttributaireController::class);

Route::get('/import/markets', [MarketImportController::class, 'importIndex']);
Route::post('/import/markets', [MarketImportController::class, 'import']);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
