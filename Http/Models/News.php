<?php

namespace App\Modules\Newsdesk\Http\Models;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Laracasts\Presenter\PresentableTrait;
use AuraIsHere\LaravelMultiTenant\Traits\TenantScopedModelTrait;
use App\Modules\Core\Http\Traits\TenantableTrait;

use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

use Baum\Node;
use Cache;
use Config;
use DB;
//use Session;
use Setting;

class News extends Node implements TranslatableContract, SluggableInterface {
/* todo upgrade to the new vinkla/translator */

	use PresentableTrait;
	use SluggableTrait;
//	use TenantScopedModelTrait;
	use TenantableTrait;
	use Translatable;

	protected $table = 'news';


// Presenter ---------------------------------------------------------------
	protected $presenter = 'App\Modules\Newsdesk\Http\Presenters\Newsdesk';

// Translation Model -------------------------------------------------------
	protected $translator = 'App\Modules\Newsdesk\Http\Models\NewsTranslation';

// DEFINE Hidden -----------------------------------------------------------
	protected $hidden = [
		'created_at',
		'updated_at'
		];

// DEFINE Fillable ---------------------------------------------------------
	protected $fillable = [
 		'is_alert',
 		'is_banner',
		'is_featured',
		'is_timed',
		'is_navigation',
 		'is_zone',
		'order',
		'publish_start',
		'publish_end',
		'news_status_id',
		'slug',
		'user_id',
		// Translatable columns
		'meta_description',
		'meta_keywords',
		'meta_title',
//		'class',
		'content',
		'summary',
		'title'
		];


// Sluggable Item ----------------------------------------------------------
	protected $sluggable = [
		'build_from' => 'title',
		'save_to'    => 'slug',
	];

// Translated Columns ------------------------------------------------------
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

	public function getNewsAttribute()
	{
		return $this->news;
	}

	public function getSummaryAttribute()
	{
		return $this->summary;
	}

	public function getTitleAttribute()
	{
		return $this->title;
	}


// Relationships -----------------------------------------------------------

// hasOne
// hasMany
// belongsTo
// belongsToMany

	public function documents()
	{
		return $this->belongsToMany('App\Modules\Filex\Http\Models\Document', 'document_news');
	}

	public function images()
	{
		return $this->belongsToMany('App\Modules\Filex\Http\Models\Image', 'image_news');
	}

	public function sites()
	{
		return $this->belongsToMany('App\Modules\Core\Http\Models\Site', 'news_site');
	}


// Functions ---------------------------------------------------------------


	public static function getRoots()
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
			$roots = static::whereIsCurrent(1)
							->whereIsOnline(1)
							->whereIsDeleted(0)
							->whereParentId(NULL)
// 							->where('slug', '<>', 'home-article')
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
		$article = DB::table('news')
			->join('news_translations', 'news.id', '=', 'news_translations.news_id')
			->where('news_translations.locale_id', '=', $locale_id)
			->where('news.is_online', '=', 1, 'AND')
			->where('news.is_deleted', '=', 0, 'AND')
			->where('news.parent_id', '=', null, 'AND')
//			->where('news_translations.slug', '=', $slug, 'AND')
//			->first();
			->orderBy('order')
			->get();
//dd('here');
dd($article);

		return $article;
	}

	public static function getStaticRoots($locale_id)
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
		$article = DB::table('news')
			->join('news_translations', 'news.id', '=', 'news_translations.news_id')
			->where('news_translations.locale_id', '=', $locale_id)
			->where('news.is_online', '=', 1, 'AND')
			->where('news.is_deleted', '=', 0, 'AND')
			->where('news.parent_id', '=', null, 'AND')
//			->where('news_translations.slug', '=', $slug, 'AND')
//			->first();
			->orderBy('order')
			->get();
//dd($article);
		return $article;
	}

	public static function getRootsStatic()
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
			return static::join('news_translations', 'news.id', '=', 'news_translations.news_id')
							->whereIsCurrent(1)
							->whereIsOnline(1)
							->whereIsDeleted(0)
							->whereParentId(NULL)
//			->where('news_translations.locale_id', '=', $locale_id)
// 							->where('slug', '<>', 'home-article')
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

	public static function getArticle( $slug )
	{
	   $article =  static::whereIsCurrent(1)
					   ->whereIsOnline(1)
					   ->whereIsDeleted(0)
					   ->whereSlug($slug)
					   ->first();

		return $article;
	}

// Scopes --------------------------------------------------

	public function scopeLocale($query, $locale_id)
	{
//dd($locale_id);
		return $query->where('locale_id', '=', $locale_id);
	}

// limit

	public function scopeLimitTop($query)
	{
		return $query->limit( Setting::get('top_news_count', Config::get('news.top_news_count')) );
	}

	public function scopeSiteID($query)
	{
//		return $query->where('site_id', '=', 11);
		$siteId = Cache::get('siteId');
//dd($siteId);
		return $query->whereHas('sites', function($query) use($siteId)
		{
//dd($siteId);
			$query->where('sites.id', $siteId);
		});
	}


// IS

	public function scopeIsAlert($query)
	{
		return $query->where('is_alert', '=', 1);
	}

	public function scopeIsArchived($query)
	{
		return $query->where('news_status_id', '=', 4);
	}

	public function scopeIsBanner($query)
	{
		return $query->where('is_banner', '=', 1);
	}

	public function scopeIsDraft($query)
	{
		return $query->where('news_status_id', '=', 1);
	}

	public function scopeIsFeatured($query)
	{
		return $query->where('is_featured', '=', 1);
	}

	public function scopeIsPublished($query)
	{
		return $query->where('news_status_id', '=', 2);
	}



	public function scopeIsTimed($query)
	{
//		return $query->where('is_timed', '=', 1);

		$date = date("Y-m-d");
		return $query
			->where('is_timed', '=', 1)
			->where('publish_start', '<=', $date . " 00:00:00")
			->where('publish_end', '>=', $date . " 23:59:59");

	}

	public function scopeIsZone($query)
	{
		return $query->where('is_zone', '=', 1);
	}


// Not

	public function scopeNotAlert($query)
	{
		return $query->where('is_alert', '=', 0);
	}

	public function scopeNotFeatured($query)
	{
		return $query->where('is_featured', '=', 0);
	}

	public function scopeNotTimed($query)
	{
		return $query->where('is_timed', '=', 0);
	}


// Dates

	public function scopePublishEnd($query)
	{
	//	$today = new DateTime();
	//dd($today);
		$date = date("Y-m-d");
	//dd($date);
	//	return $query->where('created_at', '>', $today->modify('-7 days'));
		return $query->where('publish_end', '>=', $date . " 23:59:59");
	}

	public function scopePublishStart($query)
	{
		$date = date("Y-m-d");
	//dd($date);
		return $query->where('publish_start', '<=', $date . " 00:00:00");
	}


}
