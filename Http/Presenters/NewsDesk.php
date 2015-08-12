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
	 * Present news_status
	 *
	 * @return string
	 */
	public function news_status($news_status_id)
	{
//dd($news_status_id);
//		return $news_status_id ? trans('kotoba::general.active') : trans('kotoba::general.deactivated');
		$news_status = DB::table('news_status_translations')
			->where('id', '=', $news_status_id)
			->pluck('name');

		return $news_status;

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
