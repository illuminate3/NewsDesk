<?php

namespace App\Modules\Newsdesk\Http\Controllers;

use App\Http\Controllers\Controller;

use Cache;
//use Session;
use Theme;

class NewsdeskController extends Controller
{
/* todo update cviebrock/eloquent-sluggable */

	/**
	 * Initializer.
	 *
	 * @return \NewsdeskController
	 */
	public function __construct()
	{
// middleware
		$this->middleware('auth');
		$this->middleware('admin');
		$this->middleware('newsdesk');
	}


	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function welcome()
	{
		return Theme::View('modules.newsdesk.welcome.newsdesk');
	}


	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function changeSite($site_id)
	{
//dd($site_id);
//		Session::put('siteId', $site_id);
		Cache::forget('siteId');
		Cache::forever('siteId', $site_id);

//		return Redirect::back();
		return redirect('/admin/news');
	}


}
