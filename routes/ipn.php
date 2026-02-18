<?php

use Illuminate\Support\Facades\Route;

Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
