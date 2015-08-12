<?php

namespace App\Modules\NewsDesk\Http\Models;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Laracasts\Presenter\PresentableTrait;

use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

use Baum\Node;
use Cache;
use DB;

//class Content extends Node {
class Content extends Node implements TranslatableContract, SluggableInterface {
//class Content extends Node implements TranslatableContract {

	use PresentableTrait;
	use SluggableTrait;
	use Translatable;

	protected $table = 'contents';

// Presenter -------------------------------------------------------
	protected $presenter = 'App\Modules\NewsDesk\Http\Presenters\NewsDesk';

// Translation Model -------------------------------------------------------
	protected $translator = 'App\Modules\NewsDesk\Http\Models\ContentTranslation';

// DEFINE Hidden -------------------------------------------------------
	protected $hidden = [
		'created_at',
		'updated_at'
		];

// DEFINE Fillable -------------------------------------------------------
	protected $fillable = [
// 		'is_deleted',
// 		'is_online',
		'is_featured',
		'is_timed',
		'is_navigation',
		'order',
		'publish_start',
		'publish_end',
		'print_status_id',
		'slug',
		'user_id',
		// Translatable columns
		'meta_description',
		'meta_keywords',
		'meta_title',
		'class',
		'content',
		'summary',
		'title'
		];

// Sluggable Item -------------------------------------------------------
	protected $sluggable = [
		'build_from' => 'title',
		'save_to'    => 'slug',
	];

// Translated Columns -------------------------------------------------------
	protected $translatedAttributes = [
		'meta_description',
		'meta_keywords',
		'meta_title',
		'content',
//		'slug',
		'summary',
		'title'
		];

// 	protected $appends = [
// 		'status',
// 		'title'
// 		];

	public function getContentAttribute()
	{
		return $this->content;
	}

	public function getSummaryAttribute()
	{
		return $this->summary;
	}

	public function getTitleAttribute()
	{
		return $this->title;
	}


// hasMany
// belongsTo
// belongsToMany



	public static function getRoots()
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
			$roots = static::whereIsCurrent(1)
							->whereIsOnline(1)
							->whereIsDeleted(0)
							->whereParentId(NULL)
// 							->where('slug', '<>', 'home-page')
// 							->where('slug', '<>', 'search')
// 							->where('slug', '<>', 'terms-conditions')
							->orderBy('order')
							->get();
		// });
//dd($roots);

		return $roots;
	}

	public static function getRootsSQL($locale_id)
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
		$page = DB::table('contents')
			->join('content_translations', 'contents.id', '=', 'content_translations.content_id')
			->where('content_translations.locale_id', '=', $locale_id)
			->where('contents.is_online', '=', 1, 'AND')
			->where('contents.is_deleted', '=', 0, 'AND')
			->where('contents.parent_id', '=', null, 'AND')
//			->where('content_translations.slug', '=', $slug, 'AND')
//			->first();
			->orderBy('order')
			->get();
//dd('here');
dd($page);

		return $page;
	}

	public static function getStaticRoots($locale_id)
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
		$page = DB::table('contents')
			->join('content_translations', 'contents.id', '=', 'content_translations.content_id')
			->where('content_translations.locale_id', '=', $locale_id)
			->where('contents.is_online', '=', 1, 'AND')
			->where('contents.is_deleted', '=', 0, 'AND')
			->where('contents.parent_id', '=', null, 'AND')
//			->where('content_translations.slug', '=', $slug, 'AND')
//			->first();
			->orderBy('order')
			->get();
//dd($page);
		return $page;
	}

	public static function getRootsStatic()
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
			return static::join('content_translations', 'contents.id', '=', 'content_translations.content_id')
							->whereIsCurrent(1)
							->whereIsOnline(1)
							->whereIsDeleted(0)
							->whereParentId(NULL)
//			->where('content_translations.locale_id', '=', $locale_id)
// 							->where('slug', '<>', 'home-page')
// 							->where('slug', '<>', 'search')
// 							->where('slug', '<>', 'terms-conditions')
							->orderBy('order')
							->get();
		// });

		// return $roots;
	}

	public static function getParentOptions($exceptId)
	{
//dd($exceptId);
dd(['0' => trans('kotoba::cms.no_parent')]
				+ static::whereIsDeleted(0)
				->lists('title', 'id'));

		return $exceptId
			? ['0' => trans('kotoba::cms.no_parent')]
				+ static::whereIsDeleted(0)
				->whereNotIn('id', [$exceptId])
				->lists('title', 'id')
			: ['0' => trans('kotoba::cms.no_parent')]
				+ static::whereIsDeleted(0)
				->lists('title', 'id');
	}

	public static function getPage( $slug )
	{
	   $page =  static::whereIsCurrent(1)
					   ->whereIsOnline(1)
					   ->whereIsDeleted(0)
					   ->whereSlug($slug)
					   ->first();

		return $page;
	}

	public function scopeInPrint($query)
	{
		return $query
//			->where('is_published', '=', 1);
			->where('print_status_id', '=', 2);
// 			->where('print_status_id', '<', 5, 'OR');
	}

	public function scopeIsFeatured($query)
	{
		return $query->where('is_featured', '=', 1);
	}

	public function scopeIsTimed($query)
	{
		return $query->where('is_timed', '=', 1);
	}

	public function scopeNotFeatured($query)
	{
		return $query->where('is_featured', '=', 0);
	}

	public function scopeNotTimed($query)
	{
		return $query->where('is_timed', '=', 0);
	}

	public function scopePublishEnd($query)
	{
	//	$today = new DateTime();
	//dd($today);
		$date = date("Y-m-d");
	//dd($date);
	//	return $query->where('created_at', '>', $today->modify('-7 days'));
		return $query->where('publish_end', '>=', $date);
	}

	public function scopePublishStart($query)
	{
		$date = date("Y-m-d");
	//dd($date);
		return $query->where('publish_start', '<=', $date);
	}

	public function scopeIsAccessPoint($query)
	{
		return $query->where('class', '=', 'nav-access');
	}

	public function scopeIsNavigation($query)
	{
		return $query->where('is_navigation', '=', 1);
	}

}
