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
use App\Models\Laboratory;
use App\http\Controllers\UserController;

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

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/home',function(){
    return view('home');
});


Route::view('user/login',"login")->name("login");
Route::view('register',"user/register")->name("register");
Route::view('user/private',"secret")->name("private");

Route::post('/validate-register',[LoginController::class,"register"])->name("validate-register");
Route::post('/start-session',[LoginController::class,"login"])->name("start-session");
Route::post('/logout',[LoginController::class,"logout"])->name("logout");

Route::resource('store/category',CategoryController::class);
Route::resource('store/product', ProductController::class);
Route::resource('store/laboratory', LaboratoryController::class);
Route::resource('sale/customer', CustomerController::class);
Route::resource('sale/sale', SaleController::class);
Route::resource('purchase/supplier', SupplierController::class);
Route::resource('purchase/income', IncomeController::class);

Route::resource('segurity/user', UserController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
