<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\VoucherController;
use App\Models\Laboratory;
use App\http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CashOpeningController;
use App\Http\Controllers\MovementController;
use App\Models\Voucher;

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

// 👉 Estas rutas NO deben estar dentro de middleware('auth')
Route::get('/', function () {
    return view('auth/login');
});

Auth::routes(); // Incluye rutas como /login, /logout, /register, etc.

Route::get('/home', [HomeController::class, 'index'])->name('home');



Route::middleware(['auth','check.session','role:admin,superadmin,cashier,user'])->group(function() {
    Route::resource('store/category', CategoryController::class);
    Route::resource('store/product', ProductController::class);
    Route::resource('store/laboratory', LaboratoryController::class);
    Route::resource('store/inventory', InventoryController::class);
    Route::resource('sale/customer', CustomerController::class);
    Route::resource('sale/sale', SaleController::class);
    Route::resource('purchase/supplier', SupplierController::class);
    Route::resource('purchase/income', IncomeController::class);
    Route::resource('purchase/voucher', VoucherController::class);
    Route::resource('segurity/user', UserController::class);
    Route::resource('sale/cash_opening', CashOpeningController::class);
    Route::resource('movement', MovementController::class);

    Route::put('segurity/user/{id}/{person_id?}', [UserController::class, 'update'])->name('user.update');

    Route::post('sale/pay_credit}', [SaleController::class, 'pay_credit'])->name('sale.pay_credit');

    Route::get('sale/cash_close/{id?}', [CashOpeningController::class, 'cash_close'])->name('sale.cash_close');
    Route::post('sale/cash_close/{id?}', [CashOpeningController::class, 'cash_close'])->name('sale.cash_close');
    Route::get('sale/cash_opening/validate_cash_opening/{id}', [CashOpeningController::class, 'validate_cash_opening'])->name('sale.cash_opening.validate_cash_opening');
    Route::get('purchase/income/search_product/{codeOrName}', [IncomeController::class, 'search_product']);
    Route::get('purchase/income/view_voucher/{voucherId}', [IncomeController::class, 'view_voucher']);
    Route::get('sale/sale/search_product/{codeName}', [SaleController::class, 'search_product']);
    Route::get('sale/sale/receipt/{sale_id}', [SaleController::class, 'receipt']);
    Route::post('sale/sale/return_sale', [SaleController::class, 'return_sale'])->name('sale.sale.return_sale');
    Route::post('store/inventory/proccess_out/{income_detail_id}', [InventoryController::class, 'proccess_out'])->name('store.inventory.proccess_out');
    Route::get('movement/filter', [MovementController::class, 'filterByDate'])->name('movement.filter');
    Route::get('movement/types/{type}', [MovementController::class, 'getTypesByCategory']);

    });










