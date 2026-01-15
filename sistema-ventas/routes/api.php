<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Product Routes
Route::prefix('products')->group(function () {
    Route::post('/', 'App\Modules\Products\Controller\ProductController@createProduct');
    Route::get('/{productId}', 'App\Modules\Products\Controller\ProductController@getProductById');
    Route::get('/', 'App\Modules\Products\Controller\ProductController@getAllProducts');
    Route::put('/{productId}', 'App\Modules\Products\Controller\ProductController@updateProduct');
    Route::delete('/{productId}', 'App\Modules\Products\Controller\ProductController@deleteProduct');
    Route::get('/category/{productCategoryId}', 'App\Modules\Products\Controller\ProductController@getProductsByCategory');
});

// Sale Routes
Route::prefix('sales')->group(function () {
    Route::post('/', 'App\Modules\Sales\Controller\SaleController@createSale');
    Route::get('/{saleId}', 'App\Modules\Sales\Controller\SaleController@getSaleById');
    Route::get('/', 'App\Modules\Sales\Controller\SaleController@getAllSales');
    Route::put('/{saleId}', 'App\Modules\Sales\Controller\SaleController@updateSale');
    Route::delete('/{saleId}', 'App\Modules\Sales\Controller\SaleController@deleteSale');
});

// Product Category Routes
Route::prefix('product-categories')->group(function () {
    Route::post('/', 'App\Modules\ProductCategories\Controller\ProductCategoryController@createProductCategory');
    Route::get('/{productCategoryId}', 'App\Modules\ProductCategories\Controller\ProductCategoryController@getProductCategoryById');
    Route::get('/', 'App\Modules\ProductCategories\Controller\ProductCategoryController@getAllProductCategories');
    Route::put('/{productCategoryId}', 'App\Modules\ProductCategories\Controller\ProductCategoryController@updateProductCategory');
    Route::delete('/{productCategoryId}', 'App\Modules\ProductCategories\Controller\ProductCategoryController@deleteProductCategory');
});
