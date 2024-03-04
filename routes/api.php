<?php

use App\Http\Controllers\Api\PermissionConroller;
use App\Http\Controllers\Api\RoleConroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\FamilleController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ProcurementController;
use App\Http\Controllers\Api\SousFamilleController;
use App\Http\Controllers\Api\Auth\WebAuthController;
use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\Api\ProductionHistoryController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\TechnicalSheetController;

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

Route::group(['middleware'=>'jwt.auth'],function(){

    Route::apiResource('products', ProductController::class);
    Route::get('/products-procurement', [ProductController::class, 'product_procurement']);
    Route::get('/products-productions', [ProductController::class, 'product_production']);

    Route::apiResource('familles', FamilleController::class);

    Route::apiResource('expenses', ExpenseController::class);

    Route::apiResource('expenses-categories', ExpenseCategoryController::class);

    Route::apiResource('customers', CustomerController::class);

    Route::apiResource('suppliers', SupplierController::class);

    Route::apiResource('units', UnitController::class);
    Route::get('/select-unit', [UnitController::class, 'select_unit']);

    Route::apiResource('sous-familles', SousFamilleController::class);

    Route::apiResource('roles', RoleConroller::class);

    Route::apiResource('permissions', PermissionConroller::class);
    Route::get('permission-select', [PermissionConroller::class, 'permissionSelect']);

    Route::apiResource('technical-sheet', TechnicalSheetController::class);

    Route::apiResource('production-histories', ProductionHistoryController::class);

    Route::get('/procurements', [ProcurementController::class, 'procurementIndex']);
    Route::post('procurement-store', [ProcurementController::class, 'procurementStore']);
    Route::get('/procurement/view/{uuid}', [ProcurementController::class, 'procurementView']);
    route::put('procurement-update/{uuid}', [ProcurementController::class, 'procurementUpdate']);
    Route::delete('/procurement-delete/{id}', [ProcurementController::class, 'procurementDestroy']);

    Route::get('/sales', [SaleController::class, 'saleIndex']);
    Route::post('sale-store', [SaleController::class, 'saleStore']);
    Route::get('/sale/view/{uuid}', [SaleController::class, 'saleView']);
    Route::delete('/sale-delete/{id}', [SaleController::class, 'saleDestroy']);


});

Route::post('/signin', [WebAuthController::class, 'signin']);

