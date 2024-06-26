<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleConroller;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\FamilleController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\PermissionConroller;
use App\Http\Controllers\Api\AjustementController;
use App\Http\Controllers\Api\Auth\MobileAuthController;
use App\Http\Controllers\Api\OrderReturnController;
use App\Http\Controllers\Api\ProcurementController;
use App\Http\Controllers\Api\SousFamilleController;
use App\Http\Controllers\Api\Auth\WebAuthController;
use App\Http\Controllers\Api\BakehouseController;
use App\Http\Controllers\Api\ProductStockController;
use App\Http\Controllers\Api\TechnicalSheetController;
use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\Api\ProductionHistoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;

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


    Route::get('/select-delivery-person-bakehouse', [UserController::class, 'select_delivery_person_bakehouse']);
    Route::get('/select-users-abonnes', [UserController::class, 'select_abonnes']);
    Route::post('/store-users-delivery', [UserController::class, 'store_select_delivery']);

    Route::apiResource('products', ProductController::class);
    Route::post('/products-update/{uuid}', [ProductController::class,'update']);
    Route::get('/products-procurement', [ProductController::class, 'product_procurement']);
    Route::get('/products-productions', [ProductController::class, 'product_production']);

    Route::apiResource('familles', FamilleController::class);

    Route::apiResource('expenses', ExpenseController::class);

    Route::apiResource('expenses-categories', ExpenseCategoryController::class);

    Route::apiResource('customers', CustomerController::class);
    Route::get('/select-customer-bakehouse', [CustomerController::class, 'select_customer_by_bakehouse']);

    Route::apiResource('suppliers', SupplierController::class);

    Route::apiResource('units', UnitController::class);
    Route::get('/select-unit', [UnitController::class, 'select_unit']);

    Route::apiResource('sous-familles', SousFamilleController::class);

    Route::apiResource('roles', RoleConroller::class);


    Route::apiResource('permissions', PermissionConroller::class);
    Route::get('permission-select', [PermissionConroller::class, 'permissionSelect']);

    Route::apiResource('technical-sheet', TechnicalSheetController::class);
    Route::get('technical-sheet-instock', [TechnicalSheetController::class,'production_in_stock']);

    Route::apiResource('production-histories', ProductionHistoryController::class);
    Route::get('/production-histories-details/{uuid}', [ProductionHistoryController::class, 'details_history_products']);

    Route::apiResource('ajustements', AjustementController::class);
    Route::get('ajustements-details', [AjustementController::class, 'ajustements_details']);

    Route::apiResource('returns-orders', OrderReturnController::class);

    Route::apiResource('deliveries', DeliveryController::class);
    Route::get('/deliveries-validate/{id}', [DeliveryController::class, 'deliveryValidate']);

    Route::apiResource('orders', OrderController::class);
    Route::get('/orders-validate/{id}', [OrderController::class, 'orderValidate']);
    Route::post('/orders-store-epayment', [OrderController::class, 'order_store_Epayement']);
    Route::post('/orders-update-epayment', [OrderController::class, 'order_update_Epayement']);

    Route::get('/sale-stock', [ProductStockController::class, 'saleStock']);
    Route::get('/production-stock', [ProductStockController::class, 'productionStock']);

    Route::get('/procurements', [ProcurementController::class, 'procurementIndex']);
    Route::post('procurement-store', [ProcurementController::class, 'procurementStore']);
    Route::get('/procurement/view/{uuid}', [ProcurementController::class, 'procurementView']);
    route::put('procurement-update/{uuid}', [ProcurementController::class, 'procurementUpdate']);
    Route::delete('/procurement-delete/{id}', [ProcurementController::class, 'procurementDestroy']);

    Route::get('/sales', [SaleController::class, 'saleIndex']);
    Route::post('sale-store', [SaleController::class, 'saleStore']);
    Route::get('/sale/view/{uuid}', [SaleController::class, 'saleView']);
    Route::delete('/sale-delete/{id}', [SaleController::class, 'saleDestroy']);
    Route::get('/sale-sum-today', [SaleController::class, 'saleSum']);
    Route::get('/sale-today-list', [SaleController::class, 'saleUserList']);

    Route::post('/sale-store-epayment', [SaleController::class, 'sale_store_epayment']);
    Route::post('/sale-update-epayment', [SaleController::class, 'sale_update_epayment']);

    Route::get('/transactions/versement-deliveries', [TransactionController::class, 'versement_delivery']);
    Route::get('/transactions/versement-customers', [TransactionController::class, 'versement_customers']);
    Route::get('/transactions/versement-view-delivries/{id}', [TransactionController::class, 'versement_delivery_view']);
    Route::get('/transactions/versement-view-customers/{id}', [TransactionController::class, 'versement_customers_view']);

    Route::get('/transactions/deliveries/{id}', [TransactionController::class, 'DeliveryView']);
    Route::get('/transactions/customers/{id}', [TransactionController::class, 'CustomersView']);
    Route::post('transaction-store', [TransactionController::class, 'storeTransacts']);

    Route::get('/transactions/todays', [TransactionController::class, 'funct_transaction_liste_today']);
    Route::get('/transactions/historiques', [TransactionController::class, 'func_transaction_liste_historiques']);


    Route::get('/bakehouses/dashbord', [BakehouseController::class, 'dashboardIndex']);
    Route::apiResource('bakehouses', BakehouseController::class);

    Route::apiResource('users', UserController::class);
    Route::get('/activate-or-desactivate-user/{id}', [UserController::class, 'activate_or_desactivate_user']);
    Route::get('/me', [WebAuthController::class, 'me']);

    //PDF
    Route::get('/export-supplier-pdf', [SupplierController::class, 'export_supplies_pdf']);
    Route::get('/export-procurement-pdf/{uuid}', [ProcurementController::class, 'export_procurements_pdf']);
    Route::get('/export-technical-pdf/{uuid}', [TechnicalSheetController::class, 'export_technicalsheet_pdf']);
    Route::get('/export-commande-list-pdf', [OrderController::class, 'export_commande_liste_pdf']);
    Route::get('/export-commande-pdf/{uuid}', [OrderController::class, 'export_orders_pdf']);
    Route::get('/export-livraison-list-pdf', [DeliveryController::class, 'export_livraison_liste_pdf']);
    Route::get('/export-livraison-pdf/{uuid}', [DeliveryController::class, 'export_delivery_pdf']);



    ///api mobile
    Route::get('/deliveries-by-date', [DeliveryController::class, 'delivery_by_date']);
    Route::get('/deliveries-by-livreurs', [DeliveryController::class, 'delivery_by_livreurs']);
    Route::get('/order-return-by-livreurs', [OrderReturnController::class, 'order_return_by_livreurs']);
    Route::get('/transactions-by-livreurs', [TransactionController::class, 'transaction_by_livreurs']);
    Route::get('/transactions-livreurs-recent', [TransactionController::class, 'transaction_by_livreurs_recent']);
    Route::get('/reliquat-versements-by-livreurs', [TransactionController::class, 'reliquat_by_livreurs']);
    Route::post('create-transaction-mobile', [TransactionController::class, 'create_transaction_mobile_api']);
    Route::post('update-transaction-mobile', [TransactionController::class, 'update_transaction_mobile_api']);


});

Route::post('/signin', [WebAuthController::class, 'signin']);
Route::post('/mob-signin', [MobileAuthController::class, 'mobile_login']);

