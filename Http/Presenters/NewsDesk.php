<?php

namespace App\Modules\Newsdesk\Http\Presenters;

use Laracasts\Presenter\Presenter;

use DB;


class Newsdesk extends Presenter {

	/**
	 * Present name
	 *
	 * @return string
	 */
	public function name()
	{
		return ucwords($this->entity->name);
	}

	public function articleName($id)
	{
		$title = DB::table('news')
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


// Checkboxes


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function banner()
	{
//dd('loaded');
		$return = '';

		$banner = $this->entity->is_banner;
//dd($featured);
		if ( $banner == 1 ) {
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


// Is


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function isBanner()
	{

		$return = trans('kotoba::general.yes');
		if ( $this->entity->is_banner == 0 ) {
			$return = trans('kotoba::general.no');
		}

		return $return;
	}


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function isFeatured()
	{

		$return = trans('kotoba::general.yes');
		if ( $this->entity->is_featured == 0 ) {
			$return = trans('kotoba::general.no');
		}

		return $return;
	}


}
