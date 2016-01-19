<?php

namespace App\Modules\Newsdesk\Http\Repositories;

use App\Modules\Newsdesk\Http\Models\NewsStatus;

use App;
use Cache;
use Config;
use DB;
use Session;

class NewsStatusRepository extends BaseRepository {

	/**
	 * The Module instance.
	 *
	 * @var App\Modules\ModuleManager\Http\Models\Module
	 */
	protected $news_status;

	/**
	 * Create a new ModuleRepository instance.
	 *
   	 * @param  App\Modules\ModuleManager\Http\Models\Module $module
	 * @return void
	 */
	public function __construct(
		NewsStatus $news_status
		)
	{
		$this->model = $news_status;
	}


	/**
	 * Get role collection.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function create()
	{
//		$allPermissions =  $this->permission->all()->lists('name', 'id');
//dd($allPermissions);
		$lang = Session::get('locale');

		return compact('lang');
	}


	/**
	 * Get user collection.
	 *
	 * @param  string  $slug
	 * @return Illuminate\Support\Collection
	 */
	public function show($id)
	{
		$status = $this->model->find($id);
//dd($module);

		return compact('status');
	}


	/**
	 * Get user collection.
	 *
	 * @param  int  $id
	 * @return Illuminate\Support\Collection
	 */
	public function edit($id)
	{
		$status = $this->model->find($id);
//dd($module);

		return $status;
//		return compact('status');
	}


	/**
	 * Get all models.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function store($input)
	{
//dd($input);
		$this->model = new NewsStatus;
		$this->model->create($input);
	}


	/**
	 * Update a role.
	 *
	 * @param  array  $inputs
	 * @param  int    $id
	 * @return void
	 */
	public function update($input, $id)
	{
// dd($input);
// 		$status = NewsStatus::find($id);
// 		$status->update($input);

		$status = NewsStatus::find($id);

		$locales = Cache::get('languages');
		$original_locale = Session::get('locale');
//dd($locales);

		foreach($locales as $locale => $properties)
		{

			App::setLocale($properties->locale);

			$values = [
				'name'				=> $input['name_'.$properties->id],
				'description'		=> $input['description_'.$properties->id]
			];

			$status->update($values);

		}

		App::setLocale($original_locale, Config::get('app.fallback_locale'));
		return;
	}

}
