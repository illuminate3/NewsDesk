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
	public function news_status($news_status_id, $locale_id)
	{
//dd($news_status_id);
		$news_status = DB::table('news_status_translations')
			->where('id', '=', $news_status_id)
			->where('locale_id', '=', $locale_id, 'AND')
			->pluck('name');

		return $news_status;

	}


// Checkboxes


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function alert()
	{
//dd('loaded');
		$return = '';

		$alert = $this->entity->is_alert;
//dd($featured);
		if ( $alert == 1 ) {
			$return = "checked";
		}

		return $return;
	}


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


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function zoned()
	{
//dd('loaded');
		$return = '';

		$zoned = $this->entity->is_zone;
//dd($featured);
		if ( $zoned == 1 ) {
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
	public function isAlert()
	{
/*
		$return = trans('kotoba::general.yes');
		if ( $this->entity->is_alert == 0 ) {
			$return = trans('kotoba::general.no');
		}
		return $return;
*/
		$return = '';
		$alert = $this->entity->is_alert;

		if ( $alert == 1 ) {
			$return = '<span class="glyphicon glyphicon-ok text-success"></span>';
		} else {
			$return = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		}

		return $return;
	}


	/**
	 * featured checkbox
	 *
	 * @return string
	 */
	public function isBanner()
	{
/*
		$return = trans('kotoba::general.yes');
		if ( $this->entity->is_banner == 0 ) {
			$return = trans('kotoba::general.no');
		}
		return $return;
*/
		$return = '';
		$banner = $this->entity->is_banner;

		if ( $banner == 1 ) {
			$return = '<span class="glyphicon glyphicon-ok text-success"></span>';
		} else {
			$return = '<span class="glyphicon glyphicon-remove text-danger"></span>';
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
/*
		$return = trans('kotoba::general.yes');
		if ( $this->entity->is_featured == 0 ) {
			$return = trans('kotoba::general.no');
		}
		return $return;
*/
		$return = '';
		$featured = $this->entity->is_featured;

		if ( $featured == 1 ) {
			$return = '<span class="glyphicon glyphicon-ok text-success"></span>';
		} else {
			$return = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		}

		return $return;

	}


	public function isZone()
	{
/*
		$return = trans('kotoba::general.yes');
		if ( $this->entity->is_zone == 0 ) {
			$return = trans('kotoba::general.no');
		}
		return $return;
*/
		$return = '';
		$zone = $this->entity->is_zone;

		if ( $zone == 1 ) {
			$return = '<span class="glyphicon glyphicon-ok text-success"></span>';
		} else {
			$return = '<span class="glyphicon glyphicon-remove text-danger"></span>';
		}

		return $return;

	}


}
