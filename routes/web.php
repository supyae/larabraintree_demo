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

Route::get('/payment', 'PaymentController@get')->name('payment.get');
Route::get('/getPayment', 'PaymentController@getPayment')->name('payment.getPayment');



Route::get('/payment/process', 'PaymentController@process')->name('payment.process');

Route::get('/payment/subscribe', 'PaymentController@subscribe')->name('payment.subscribe');

