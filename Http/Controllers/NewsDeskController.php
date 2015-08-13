<?php

namespace App\Modules\NewsDesk\Http\Controllers;

use App\Http\Controllers\Controller;

use Theme;


class NewsDeskController extends Controller
{


	/**
	 * Initializer.
	 *
	 * @return \NewsDeskController
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
