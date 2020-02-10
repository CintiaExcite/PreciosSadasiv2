<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//https://laravel.com/docs/5.6/eloquent-resources#conditional-relationships

Route::post('login', 'ApiJWTController@login');
Route::post('register', 'ApiJWTController@register');

/*Rutas consumidas por web*/
Route::get('price-with-text/{product}', 'GenerateController@priceWithTextG');
Route::get('price/{product}', 'GenerateController@priceG');
Route::get('lowest-price-between-models', 'GenerateController@lowestPriceBetweenModelsG');
Route::get('price-sice-by-development/{development}', 'GenerateController@priceSinceByDevelopmentG');
Route::get('discount-product/{product}', 'GenerateController@discountProductG');
Route::get('income-product/{product}', 'GenerateController@incomeProductG');
Route::get('payments-product/{product}', 'GenerateController@paymentsProductG');

/*Rutas para acceso a información desde plugin de Wordpress y crear shortcode*/
Route::get('developments-wp', 'GenerateController@developmentsWpG');
Route::get('products-wp/{development}', 'GenerateController@productsWpG');

/*Ruta de recuperación de contraseña*/
Route::post('users/recovery-password', 'Api\User\UserController@recoveryPassword');





/*DevelopmentsInfo Cintia*/
Route::resource('development-info', 'Api\Development\DevelopmentInfoController', ['except' => ['create', 'edit']]);
Route::resource('development.development-info', 'Api\Development\DevelopmentDevelopInfoController', ['only' => ['index']]);

/*Tokens Cintia*/
Route::resource('tokens', 'Api\Token\TokenController', ['except' => ['create', 'edit']]);
Route::resource('developments.tokens', 'Api\Development\DevelopmentTokenController', ['only' => ['index']]);

/*Amenidades Cintia*/
Route::resource('amenities', 'Api\Amenities\AmenitiesController', ['except' => ['create', 'edit']]);

/*Niveles o Plantas Cintia*/
Route::resource('level', 'Api\Level\LevelController', ['except' => ['create', 'edit']]);

/*Especificaciones Cintia*/
Route::resource('specification', 'Api\Specifications\SpecificationsController', ['except' => ['create', 'edit']]);

/*Transformar numero a letra en precios*/
Route::get('numeroLetras', 'Api\Price\NumbersLettersController@indexletras');
Route::resource('priceee', 'Api\Price\PriceController', ['only' => ['show']]);
Route::resource('products.price', 'Api\Product\ProductPriceController', ['except' => ['create', 'edit', 'destroy']]);





Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', 'ApiJWTController@logout'); 
    Route::get('user', 'ApiJWTController@getAuthUser');

    /*States*/
	Route::resource('states', 'Api\State\StateController', ['except' => ['create', 'edit']]);
	Route::get('states-dt', 'Api\State\StateController@indexDT');
	Route::resource('states.developments', 'Api\State\StateDevelopmentController', ['only' => ['index']]);

	/*Developments*/
	Route::resource('developments', 'Api\Development\DevelopmentController', ['except' => ['create', 'edit']]);
	Route::get('developments-dt', 'Api\Development\DevelopmentController@indexDT');
	Route::put('developments/image/{development}', 'Api\Development\DevelopmentController@updateDevelopmentImage');
	Route::resource('developments.products', 'Api\Development\DevelopmentProductController', ['only' => ['index']]);

	/*Products*/
	Route::resource('products', 'Api\Product\ProductController', ['except' => ['create', 'edit']]);
	Route::get('products-dt', 'Api\Product\ProductController@indexDT');
	Route::put('products/image/{product}', 'Api\Product\ProductController@updateProductImage');
	//Route::resource('products.price', 'Api\Product\ProductPriceController', ['except' => ['create', 'edit', 'destroy']]);
	Route::resource('products.discount', 'Api\Product\ProductDiscountController', ['except' => ['create', 'edit', 'destroy']]);
	Route::resource('products.income', 'Api\Product\ProductIncomeController', ['except' => ['create', 'edit', 'destroy']]);
	Route::resource('products.payment', 'Api\Product\ProductPaymentController', ['except' => ['create', 'edit', 'destroy']]);
	Route::resource('products.impression', 'Api\Product\ProductImpressionController', ['only' => ['index']]);
	Route::get('products-download', 'Api\Product\ProductController@downloadExcel');

	/*Users*/
	Route::resource('users', 'Api\User\UserController', ['except' => ['create', 'edit']]);
	Route::put('users/permits/{user}', 'Api\User\UserController@updatePermits');
	Route::put('users/change-password/{user}', 'Api\User\UserController@changePassword');
	Route::post('users/check-password/{user}', 'Api\User\UserController@checkPassword');

	/*User Permits*/
	Route::resource('user-permits', 'Api\UserPermit\UserPermitController', ['only' => ['index']]);

	/*Histories*/
	Route::resource('prices.historyprice', 'Api\Price\PriceHistoryPriceController', ['only' => ['index']]);
	Route::resource('discounts.historydiscount', 'Api\Discount\DiscountHistoryDiscountController', ['only' => ['index']]);
	Route::resource('incomes.historyincome', 'Api\Income\IncomeHistoryIncomeController', ['only' => ['index']]);
	Route::resource('payments.historypayment', 'Api\Payment\PaymentHistoryPaymentController', ['only' => ['index']]);

	/*Settings*/
	Route::resource('settings', 'Api\Setting\SettingController', ['except' => ['index', 'create', 'store', 'edit', 'destroy']]);
	Route::get('settings-email', 'Api\Setting\SettingController@indexEmail');

	/*Logs*/
	Route::resource('logs', 'Api\Log\LogController', ['only' => ['index']]);

	
});

