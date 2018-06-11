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



Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware'=>'auth'], function(){
	Route::get('/admin', 'AdminController@index');
	Route::get('/admin/oauth', 'AdminController@oauth');
	Route::resource('admin/events', 'EventController', ['except' => ['show']]);
	Route::resource('admin/photos', 'PhotoController', ['except' => ['destroy', 'index', 'show']]);
	Route::resource('admin/folders', 'FolderController');
	Route::resource('admin/cities', 'CityController');
	Route::resource('admin/venues', 'VenueController');
	Route::resource('admin/ticket_sellers', 'TicketSellerController');
	Route::match(['get', 'post'] ,'/admin/eventTicketSeller/{id}', 'EventController@eventTicketSeller')->name('event_ticket_seller');
	Route::match(['get', 'post'], '/admin/parse/file', 'AdminController@parse_file');

	Route::get('/api/admin/events', 'EventController@api_index');
	Route::get('/api/admin/cities', 'CityController@api_index');
	Route::get('/api/admin/ticket_sellers', 'TicketSellerController@api_index');
	Route::get('/api/admin/venues', 'VenueController@api_index');
	Route::get('/api/admin/folders', 'FolderController@api_index');
	Route::get('/api/admin/events/lastCreated', 'EventController@api_last_created');
	Route::get('/api/admin/cities/lastCreated', 'CityController@api_last_created');
	Route::get('/api/admin/folders/lastCreated', 'FolderController@api_last_created');
	Route::get('/api/admin/ticket_sellers/lastCreated', 'TicketSellerController@api_last_created');
	Route::patch('/api/events/toggle/{id}', 'EventController@toggleEventStatus');
});

Route::get('/', 'PublicController@index')->name('public.index');
Route::get('/load/{id}/{city?}', 'PublicController@lazy_load');
Route::get('/suscribers/verification/phone/{phone}/{validation_code}', 'SuscriberController@validate_phone');
Route::get('/suscribers/verification/email/{email}/{validation_code}', 'SuscriberController@validate_email');
Route::get('/suscribers/unsuscribe', 'SuscriberController@destroy_show')->name('suscribers.destroy');
Route::delete('/suscribers/unsuscribe', 'SuscriberController@destroy');
Route::resource('suscribers', 'SuscriberController', ['only' => ['store']]);
Route::get('/city/{city}', 'PublicController@by_city')->name('public.cities');
Route::get('/past/{city?}', 'PublicController@past')->name('public.past');
Route::post('/nexmo/delivery-receipt', 'SuscriberController@deliveryReceipt');
Route::post('/mailgun/mail-recipient', 'AdminController@mailRecipient');








