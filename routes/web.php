<?php

use App\Http\Controllers\AttributaireController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuditSettingController;
use App\Http\Controllers\AutoriteContractanteController;
use App\Http\Controllers\CPMPController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\MarketImportController;
use App\Http\Controllers\MarketTypeController;
use App\Http\Controllers\ModePassationController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('market-types', MarketTypeController::class);

    Route::resource('audit-settings', AuditSettingController::class);

    Route::post('/audit-settings', [AuditSettingController::class, 'storeOrUpdate'])->name('audit-settings.storeOrUpdate');

    Route::get(
        '/markets/to_audit/summary',
        [MarketController::class, 'marketsToAuditSummary']
    )->name('markets.toAuditSummary');
    Route::get(
        '/markets/to_audit/{exportType?}',
        [MarketController::class, 'getFilteredMarkets']
    )->name('markets.toAudit');
    Route::post(
        '/market/selections/save',
        [MarketController::class, 'saveMarketSelections']
    )->name('market.selections.save');
    Route::resource('markets', MarketController::class);

    Route::post(
        '/mode-passations/update-rank',
        [ModePassationController::class, 'updateRank']
    )->name('mode-passations.updateRank');

    // Audits
    Route::get(
        '/audits/audited',
        [AuditController::class, 'auditedMarkets']
    )->name('audits.audited');
    Route::get(
        '/audits/cancel',
        [AuditController::class, 'cancelAudit']
    )->name('audits.cancel');
    Route::get(
        '/audits/excel',
        [AuditController::class, 'exportAuditsToExcel']
    )->name('audits.export.excel');
    Route::resource('audits', AuditController::class);
    
    // Modes de passation
    Route::resource('mode-passations', ModePassationController::class);

    Route::resource('autorite-contractantes', AutoriteContractanteController::class);

    Route::resource('cpmps', CPMPController::class);

    Route::resource('secteurs', SecteurController::class);

    Route::resource('attributaires', AttributaireController::class);

    Route::get('/import/markets', [MarketImportController::class, 'importIndex']);
    Route::post('/import/markets', [MarketImportController::class, 'import']);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post(
        '/users/{user}/assign-role',
        [UserController::class, 'assignRole']
    )->name('users.assignRole');
    Route::get(
        '/custom-register',
        [UserController::class, 'showRegistrationForm']
    )->name('users.register');
    Route::post(
        '/custom-register',
        [UserController::class, 'customRegister']
    )->name('users.register.store');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::put(
        '/roles/{role}/permissions',
        [RoleController::class, 'updatePermissions']
    )->name('roles.updatePermissions');

});
