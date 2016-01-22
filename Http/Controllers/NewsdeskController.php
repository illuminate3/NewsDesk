<?php

namespace App\Modules\Newsdesk\Http\Controllers;

use App\Http\Controllers\Controller;

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


}
