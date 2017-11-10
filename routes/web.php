<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('storage-auth', 'Storage\AuthController@auth');

Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');
Route::get('password/email', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('agreements', ['as' => 'company.agreement.show', 'uses' => 'ServiceAgreementController@show'])
    ->middleware(['auth', 'admin']);
Route::post('agreements', ['as' => 'company.agreement.store', 'uses' => 'ServiceAgreementController@store'])
    ->middleware(['auth', 'admin']);


Route::get('home', function() {
    return Redirect::to('/');
})->middleware('agreement');
Route::get('/', 'HomeController@index')->middleware('agreement');

Route::get('dealer-order-form', function() {
    return view('dealer-order-form');
});
Route::get('customer-order-form', function() {
    return view('customer-order-form');
});

Route::get('esign/errors', ['as' => 'order-esign-errors', 'uses' => 'EsignController@errors']);
Route::get('esign/thanks', ['as' => 'order-esign-thanks', 'uses' => 'EsignController@thanks']);
Route::post('esign-callback', ['as' => 'esign-callback', 'uses' => 'EsignController@callback']);

// lock to dealer role
Route::get('orders/{order_uuid}/initial-esign', ['as' => 'order-esign', 'uses' => 'OrdersController@initialEsignOrderDocument']);
Route::get('orders/{order_uuid}/esign-via-email', ['as' => 'order-esign-email', 'uses' => 'OrdersController@esignOrderDocumentViaEmail']);

// rto company
Route::get('orders/{order_uuid}/esign/{signature_id}', ['as' => 'esign-order-by-signature-id', 'uses' => 'OrdersController@esignOrderDocumentBySignatureId']);

Route::get('dealer-map', function() {
    return view('dealer-map');
});
Route::get('documents/price-list', 'DocumentsController@priceList');
Route::get('documents/order-form', 'DocumentsController@orderForm');
Route::get('documents/rto-docs', 'DocumentsController@rtoDocs');
Route::get('documents/promo99', 'DocumentsController@promo99');
Route::get('documents/building-configuration', 'DocumentsController@buildingConfiguration');
Route::get('documents/customer-delivery-form', 'DocumentsController@customerDeliveryForm');
Route::get('documents/delivery-form', 'DocumentsController@deliveryForm');
Route::get('documents/neighbor-release', 'DocumentsController@neightborRelease');

Route::get('buildings/{building}/inventory-form', 'BuildingsController@pdfInventoryForm');

Route::group(['middleware' => 'uscguard'], function () {

    Route::get('buildings/{building}/work-order', 'BuildingsController@pdfWorkOrder')->name('building/work-order');
    Route::get('buildings/{building}/customer-order', 'BuildingsController@pdfCustomerOrder')->name('building/customer-order');

    Route::get('print/{identifier}', 'PrintController@showprint');
});

Route::group(['middleware' => ['auth', 'qraccess'] ], function() {
    Route::get('qr-build/{id}','QrcodeController@qrBuildLocation');
    Route::get('qr-location/{id}','QrcodeController@qrBuildLocation');
    Route::resource('qrcode', 'QrcodeController');
});