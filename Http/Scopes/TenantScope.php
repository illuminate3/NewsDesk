<?php

namespace App\Modules\Newsdesk\Http\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

use Auth;
use Cache;
use route;
use Session;


class TenantScope implements ScopeInterface
{


	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return void
	 */
	public function apply(Builder $builder, Model $model)
	{

//dd(Auth::user());
//dd(Session::get('siteId'));
//dd(Cache::get('siteId'));

		if ( Auth::user() != null) {
			if ( Auth::user()->can('manage_newsdesk') ) {
			}
		} else {

			if (Session::has('siteId'))
			{
				$siteId = session('siteId');
				$builder->whereHas('sites', function($query) use($siteId)
				{
					$query->where('sites.id', $siteId);
				});
			}

		}



/*
			if (Session::has('siteId'))
			{
				$siteId = session('siteId');
				$builder->whereHas('sites', function($query) use($siteId)
				{
					$query->where('sites.id', $siteId);
				});
			}
*/


	}


	/**
	 * Remove the scope from the given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return void
	 */
	public function remove(Builder $builder, Model $model)
	{
		dd('remove called');
	}


}
