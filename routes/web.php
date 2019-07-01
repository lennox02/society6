<?php

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

Route::get('/', function () {
    return view('welcome');
});

//Creatives
Route::get('/creatives/{creatives_id}', 'CreativesController@getCreatives');
Route::post('/creatives/create', 'CreativesController@createCreatives');
Route::post('/creatives/update', 'CreativesController@updateCreatives');

//Products
Route::post('/products/create', 'ProductsController@createProducts');

//Orders
Route::post('/orders/create', 'OrdersController@createOrders');

//Vendors
//Marco Fine Arts
Route::get('/vendors/marcoFineArts', 'MacroFineArtsController@getPendingOrders');
