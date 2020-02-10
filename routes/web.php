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

//Route::get('migrate-users', 'Utils\MigrateController@migrateUsers');
//Route::get('migrate-products', 'Utils\MigrateController@migrateProducts');
//Route::get('migrate-history-prices', 'Utils\MigrateController@migrateHistoryPrices');
//Route::get('migrate-logs', 'Utils\MigrateController@migrateLogs');

Route::get('generate/price-with-text-g/{product}', 'GenerateController@priceWithTextG');
Route::get('generate/price-g/{product}', 'GenerateController@priceG');
Route::get('generate/price-since-by-development-g/{development}', 'GenerateController@priceSinceByDevelopmentG');
Route::get('generate/lowest-price-between-models-g', 'GenerateController@lowestPriceBetweenModelsG');

