<?php

/*
|--------------------------------------------------------------------------
| Origami
|--------------------------------------------------------------------------
*/

Route::pattern('news', '[0-9a-z]+');
Route::pattern('id', '[0-9]+');

// Resources
// Controllers

Route::group(['prefix' => 'newsdesk'], function() {
	Route::get('welcome', [
		'uses'=>'NewsdeskController@welcome'
	]);
});


Route::get('/news/{news}', 'FrontDeskController@get_article')->where('news', '.*');
Route::get('/news-archives', 'FrontDeskController@getArchives')->where('news', '.*');


// API DATA

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/


//Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
Route::group(['prefix' => 'admin'], function() {

// Controllers

	Route::get('news/repair', array(
		'uses'=>'NewsController@repairTree'
		));


// Resources

	Route::resource('news', 'NewsController');
	Route::resource('news_statuses', 'NewsStatusesController');

});
