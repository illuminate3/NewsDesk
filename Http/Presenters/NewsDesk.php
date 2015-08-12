<?php

namespace App\Modules\NewsDesk\Http\Presenters;

use Laracasts\Presenter\Presenter;

use DB;


class NewsDesk extends Presenter {

	/**
	 * Present name
	 *
	 * @return string
	 */
	public function name()
	{
		return ucwords($this->entity->name);
	}

	public function pageName($id)
	{
		$title = DB::table('pages')
			->where('id', '=', $id)
			->pluck('title');
//dd($customer);

		return $title;
	}


	/**
	 * Present print_status
	 *
	 * @return string
	 */
	public function print_status($print_status_id)
	{
//dd($print_status_id);
//		return $print_status_id ? trans('kotoba::general.active') : trans('kotoba::general.deactivated');
		$print_status = DB::table('print_status_translations')
			->where('id', '=', $print_status_id)
			->pluck('name');

		return $print_status;

	}


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function navigation()
	{
//dd('loaded');
		$return = '';

		$navigation = $this->entity->is_navigation;
//dd($featured);
		if ( $navigation == 1 ) {
			$return = "checked";
		}

		return $return;
	}


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function featured()
	{
//dd('loaded');
		$return = '';

		$featured = $this->entity->is_featured;
//dd($featured);
		if ( $featured == 1 ) {
			$return = "checked";
		}

		return $return;
	}


	/**
	 * timed checkbox
	 *
	 * @return string
	 */
	public function timed()
	{
//dd('loaded');
		$return = '';

		$timed = $this->entity->is_timed;
//dd($timed);
		if ( $timed == 1 ) {
			$return = "checked";
		}

		return $return;
	}

}
