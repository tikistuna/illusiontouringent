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



Route::get('/', 'PublicController@index')->name('public.index');
Route::get('/load/{id}/{city?}', 'PublicController@lazy_load');
Route::get('/suscribers/verification/phone/{phone}/{validation_code}', 'SuscriberController@validate_phone');
Route::get('/suscribers/verification/email/{email}/{validation_code}', 'SuscriberController@validate_email');
Route::get('/suscribers/unsuscribe', 'SuscriberController@destroy_show')->name('suscribers.destroy');
Route::delete('/suscribers/unsuscribe', 'SuscriberController@destroy');
Route::resource('suscribers', 'SuscriberController', ['only' => ['store']]);
Route::get('/city/{city}', 'PublicController@by_city')->name('public.cities');
Route::get('/past/{city?}', 'PublicController@past')->name('public.past');
Route::get('/navigation/{past?}', 'PublicController@navigation')->name('public.navigation');








