<?php

/*
|--------------------------------------------------------------------------
| Origami
|--------------------------------------------------------------------------
*/

Route::pattern('news', '[0-9a-z]+');

// Resources
// Controllers

Route::group(['prefix' => 'newsdesk'], function() {
	Route::get('welcome', [
		'uses'=>'NewsdeskController@welcome'
	]);
});



// Route::group(array('before' => 'auth'), function()
// {
// 	\Route::get('elfinder', 'Barryvdh\Elfinder\ElfinderController@showIndex');
// 	\Route::any('elfinder/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');
// 	\Route::get('elfinder/ckeditor4', 'Barryvdh\Elfinder\ElfinderController@showCKeditor4');
// });


// API DATA

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/


// Route::resource('news', 'ArticlesController', array('except' => array('show')));
//
// Route::get('{slug}', array('as' => 'news', 'uses' => 'ArticleController@show'))
// 	->where('slug', App\Modules\Newsdesk\Http\Models\Article::$slugPattern);

// Controllers

// API DATA
// 	Route::get('api/sites', array(
// 	//	'as'=>'api.sites',
// 		'uses'=>'SitesController@data'
// 		));

//Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function() {
Route::group(['prefix' => 'admin'], function() {

// Controllers

	Route::get('news/repair', array(
		'uses'=>'NewsController@repairTree'
		));

// Resources

	Route::resource('news', 'NewsController');
	Route::resource('news_statuses', 'NewsStatusesController');

// Routes
// 	Route::delete('news/{id}', array(
// 		'as'=>'news.destroy',
// 		'uses'=>'NewsController@destroy'
// 		));
// 	Route::delete('sites/{id}', array(
// 	//	'as'=>'sites.destroy',
// 		'uses'=>'SitesController@destroy'
// 		));

// API DATA
// 	Route::get('api/news_statuses', array(
// //		'as'=>'api.news_statuses',
// 		'uses'=>'NewsStatusesController@data'
// 		));

});

/*
Route::get('blog', 'FrontendController@get_blog');
Route::get('blog/{any}', 'FrontendController@get_post')->where('any', '.*');
Route::get('{page}', 'FrontendController@get_page')->where('page', '.*');
*/

Route::get('/news/{news}', 'FrontDeskController@get_article')->where('news', '.*');



/*
Route::resource('news', 'ArticlesController', array('except' => array('show')));

Route::group(array('prefix' => 'news'), function () {

	Route::post("{id}/up", array(
		'as' => "articles.up",
		'uses' => "ArticlesController@up",
	));
	Route::post("{id}/down", array(
		'as' => "articles.down",
		'uses' => "ArticlesController@down",
	));

	Route::get('export', array(
		'as' => 'articles.export',
		'uses' => 'ArticlesController@export',
	));

	Route::get('{id}/confirm', array(
		'as' => 'articles.confirm',
		'uses' => 'ArticlesController@confirm',
	));

});

// The slug route should be registered last since it will capture any slug-like
// route
Route::get('{slug}', array('as' => 'news', 'uses' => 'ArticleController@show'))
	->where('slug', App\Modules\Newsdesk\Http\Models\Article::$slugPattern);
*/

/*
//Route::when('assets/*', 'AssetsController');
*/
