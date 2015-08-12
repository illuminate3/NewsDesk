<?php

/*
|--------------------------------------------------------------------------
| Origami
|--------------------------------------------------------------------------
*/

// Resources
// Controllers

Route::group(['prefix' => 'newsdesk'], function() {
	Route::get('welcome', [
		'uses'=>'NewsDeskController@welcome'
	]);
});

// API DATA

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/


/*
Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localizationRedirect', 'localeSessionRedirect' ]
],
function()
{
	// ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP //
// 	Route::get('/', function()
// 	{
// //dd(LaravelLocalization::getSupportedLocales());
// //dd(LaravelLocalization::getSupportedLanguagesKeys());
//dd(LaravelLocalization::getCurrentLocale());
// 		return View::make('hello');
// 	});

});
*/

// Route::resource('pages', 'PagesController', array('except' => array('show')));
//
// Route::get('{slug}', array('as' => 'page', 'uses' => 'PageController@show'))
// 	->where('slug', App\Modules\NewsDesk\Http\Models\Page::$slugPattern);

// Controllers

// API DATA
// 	Route::get('api/sites', array(
// 	//	'as'=>'api.sites',
// 		'uses'=>'SitesController@data'
// 		));

//Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
Route::group(['prefix' => 'admin'], function() {

	Route::pattern('id', '[0-9]+');

// Controllers
	Route::resource('contents', 'ContentsController');
	Route::resource('print_statuses', 'PrintStatusesController');

// Routes
// 	Route::delete('contents/{id}', array(
// 		'as'=>'contents.destroy',
// 		'uses'=>'ContentsController@destroy'
// 		));
// 	Route::delete('sites/{id}', array(
// 	//	'as'=>'sites.destroy',
// 		'uses'=>'SitesController@destroy'
// 		));

// API DATA
	Route::get('api/print_statuses', array(
//		'as'=>'api.print_statuses',
		'uses'=>'PrintStatusesController@data'
		));

});

Route::get('{page}', 'FrontendController@get_page')->where('page', '.*');



// Route::get('/', 'PageController@show');
// Route::get('/', array(
// 	'as' => 'home',
// 	'uses' => 'PageController@show'
// 	));

/*
Route::resource('pages', 'PagesController', array('except' => array('show')));

Route::group(array('prefix' => 'pages'), function () {

	Route::post("{id}/up", array(
		'as' => "pages.up",
		'uses' => "PagesController@up",
	));
	Route::post("{id}/down", array(
		'as' => "pages.down",
		'uses' => "PagesController@down",
	));

	Route::get('export', array(
		'as' => 'pages.export',
		'uses' => 'PagesController@export',
	));

	Route::get('{id}/confirm', array(
		'as' => 'pages.confirm',
		'uses' => 'PagesController@confirm',
	));

});

// The slug route should be registered last since it will capture any slug-like
// route
Route::get('{slug}', array('as' => 'page', 'uses' => 'PageController@show'))
	->where('slug', App\Modules\NewsDesk\Http\Models\Page::$slugPattern);
*/

/*
//Route::when('assets/*', 'AssetsController');
*/
