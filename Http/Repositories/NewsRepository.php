<?php

namespace App\Modules\NewsDesk\Http\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

use App\Modules\NewsDesk\Http\Repositories\BaseRepository as BaseRepository;

use App\Modules\Core\Http\Repositories\LocaleRepository;

use App\Modules\Core\Http\Models\Locale;
use App\Modules\NewsDesk\Http\Models\News;
use App\Modules\NewsDesk\Http\Models\NewsTranslation;

use App;
use Auth;
use Cache;
use Config;
use DB;
use Lang;
use Route;
use Session;
use Illuminate\Support\Str;


class NewsRepository extends BaseRepository {

	/**
	 * The Module instance.
	 *
	 * @var App\Modules\ModuleManager\Http\Models\Module
	 */
	protected $news;

	/**
	 * Create a new ModuleRepository instance.
	 *
   	 * @param  App\Modules\ModuleManager\Http\Models\Module $module
	 * @return void
	 */
	public function __construct(
			LocaleRepository $locale_repo,
			News $news
		)
	{
		$this->locale_repo = $locale_repo;
		$this->model = $news;

//		$this->id = Route::current()->parameter( 'id' );
//		$this->articlelist = Article::getParentOptions( $exceptId = $this->id );
//		$this->articlelist = News::getParentOptions( $exceptId = $this->id );
//dd($this->articlelist);
	}


	/**
	 * Get role collection.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function create()
	{

		$lang = Session::get('locale');
		$locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);

//		$articlelist = $this->getParents( $exceptId = $this->id, $locales );

// 		$articlelist = $this->getParents($locale_id, null);
// 		$articlelist = array('' => trans('kotoba::cms.no_parent')) + $articlelist;
//dd($articlelist);
		$all_articlelist = $this->getParents($locale_id, null);
		$articlelist = array('' => trans('kotoba::cms.no_parent'));
		$articlelist = new Collection($articlelist);
		$articlelist = $articlelist->merge($all_articlelist);



		$users = $this->getUsers();
		$users = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::account.user', 1) ) + $users;
//dd($users);
//		$all_menus = $this->menu->all()->lists('name', 'id');
// 		$all_users = $this->getUsers();
// 		$users = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::account.user', 1));
// 		$users = new Collection($users);
// 		$users = $users->merge($all_users);


		$news_statuses = $this->getNewsStatuses($locale_id);
		$news_statuses = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::cms.news_status', 1) ) + $news_statuses;

		$user_id = Auth::user()->id;

		return compact(
			'articlelist',
			'news_statuses',
			'users',
			'user_id',
			'lang',
			'locale_id'
			);
	}


	/**
	 * Get user collection.
	 *
	 * @param  string  $slug
	 * @return Illuminate\Support\Collection
	 */
	public function show($id)
	{
		$news = $this->model->find($id);
		$links = News::find($id)->newslinks;
//$news = $this->news->show($id);

//$news = $this->model->where('id', $id)->first();
//		$news = new Collection($news);
//dd($news);

		return compact('news', 'links');
	}


	/**
	 * Get user collection.
	 *
	 * @param  int  $id
	 * @return Illuminate\Support\Collection
	 */
	public function edit($id)
	{
		$news = $this->model->find($id);
//dd($news);

		$lang = Session::get('locale');
		$locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);

//		$articlelist = $this->getParents( $exceptId = $this->id, $locales );

// 		$articlelist = $this->getParents($locale_id, $id);
// 		$articlelist = array('' => trans('kotoba::cms.no_parent')) + $articlelist;
//dd($articlelist);
		$all_articlelist = $this->getParents($locale_id, null);
		$articlelist = array('' => trans('kotoba::cms.no_parent'));
		$articlelist = new Collection($articlelist);
		$articlelist = $articlelist->merge($all_articlelist);

		$users = $this->getUsers();
		$users = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::account.user', 1) ) + $users;
//dd($users);
		$news_statuses = $this->getNewsStatuses($locale_id);
		$news_statuses = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::cms.news_status', 1) ) + $news_statuses;

//		$user_id = Auth::user()->id;

		return compact(
			'news',
			'lang',
//			'locales',
			'articlelist',
			'news_statuses',
			'users'
			);
	}


	/**
	 * Get all models.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function store($input)
	{
//dd($input);

		if ( !isset($input['class']) ) {
			$class = null;
		} else {
			$class = $input['class'];
		}

		if ( !isset($input['is_featured']) ) {
			$is_featured = 0;
		} else {
			$is_featured = $input['is_featured'];
		}

		if ( !isset($input['is_published']) ) {
			$is_published = 0;
		} else {
			$is_published = $input['is_published'];
		}

		if ( !isset($input['is_navigation']) ) {
			$is_navigation = 0;
		} else {
			$is_navigation = $input['is_navigation'];
		}

		if ( !isset($input['is_timed']) ) {
			$is_timed = 0;
		} else {
			$is_timed = $input['is_timed'];
		}

		if ( $input['publish_end'] == '' ) {
			$publish_end = null;
		} else {
			$publish_end = $input['publish_end'];
		}

		if ( $input['publish_start'] == '' ) {
			$publish_start = null;
		} else {
			$publish_start = $input['publish_start'];
		}

		if ( ($input['news_status_id'] == 3 || $input['news_status_id'] == 4) ) {
			$is_published = 1;
		}

		$lang = Session::get('locale');
		$app_locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);
//		$app_locale_id = $this->getLocaleID(Config::get('app.locale'));

		$values = [
//			'name'			=> $input['name'],
//			'is_current'		=> 1,
//			'is_online'			=> $input['is_online'],
//			'is_online'			=> $is_online,
//			'is_featured'		=> $input['is_featured'],
			'is_published'		=> $is_published,
			'is_featured'		=> $is_featured,
			'is_timed'			=> $is_timed,
			'is_navigation'		=> $is_navigation,
//			'link'				=> $input['link'],
//			'class'				=> $input['class'],
			'class'				=> $class,
			'order'				=> $input['order'],
			'news_status_id'	=> $input['news_status_id'],
//			'publish_end'		=> $input['publish_end'],
//			'publish_start'		=> $input['publish_start'],
			'publish_end'		=> $publish_end,
			'publish_start'		=> $publish_start,
//			'slug'				=> $input['title_1'],
			'slug'				=> Str::slug($input['title_'.$app_locale_id]),
//			'user_id'			=> 1
			'user_id'			=>  $input['user_id']
		];
//dd($values);

		$news = News::create($values);

//		$locales = Cache::get('languages');
		$locales = Cache::get('languages');
		$original_locale = Session::get('locale');

		foreach($locales as $locale => $properties)
		{

			App::setLocale($properties->locale);

			$values = [
				'content'		=> $input['content_'.$properties->id],
				'summary'		=> $input['summary_'.$properties->id],
				'title'			=> $input['title_'.$properties->id],

//				'slug'			=> $input['slug_'.$properties->id],
//				'slug'			=> Str::slug($input['title_'.$properties->id]),
//				'slug'			=> Str::slug($input['title_'.$properties->id]),

				'meta_title'			=> $input['meta_title_'.$properties->id],
				'meta_keywords'			=> $input['meta_keywords_'.$properties->id],
				'meta_description'		=> $input['meta_description_'.$properties->id]
			];

			$news->update($values);
		}

		$this->manageBaum($input['parent_id'], null);

		App::setLocale($original_locale, Config::get('app.fallback_locale'));
		return;
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
//dd($input);

		if ( !isset($input['class']) ) {
			$class = null;
		} else {
			$class = $input['class'];
		}

		if ( !isset($input['is_featured']) ) {
			$is_featured = 0;
		} else {
			$is_featured = $input['is_featured'];
		}

		if ( !isset($input['is_published']) ) {
			$is_published = 0;
		} else {
			$is_published = $input['is_published'];
		}

		if ( !isset($input['is_timed']) ) {
			$is_timed = 0;
		} else {
			$is_timed = $input['is_timed'];
		}

		if ( !isset($input['is_navigation']) ) {
			$is_navigation = 0;
		} else {
			$is_navigation = $input['is_navigation'];
		}

		if ( $input['publish_end'] == '' ) {
			$publish_end = null;
		} else {
			$publish_end = $input['publish_end'];
		}

		if ( $input['publish_start'] == '' ) {
			$publish_start = null;
		} else {
			$publish_start = $input['publish_start'];
		}

		if ( ($input['news_status_id'] == 3 || $input['news_status_id'] == 4) ) {
			$is_published = 1;
		}

		$news = News::find($id);

		$lang = Session::get('locale');
		$app_locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);
//		$app_locale_id = $this->getLocaleID(Config::get('app.locale'));

		$values = [
//			'name'			=> $input['name'],
//			'is_current'		=> 1,
//			'is_online'			=> $input['is_online'],
//			'is_online'			=> $is_online,
//			'is_featured'		=> $input['is_featured'],
			'is_published'		=> $is_published,
			'is_featured'		=> $is_featured,
			'is_timed'			=> $is_timed,
			'is_navigation'		=> $is_navigation,
//			'link'				=> $input['link'],
//			'class'				=> $input['class'],
			'class'				=> $class,
			'order'				=> $input['order'],
			'news_status_id'	=> $input['news_status_id'],
//			'publish_end'		=> $input['publish_end'],
//			'publish_start'		=> $input['publish_start'],
			'publish_end'		=> $publish_end,
			'publish_start'		=> $publish_start,
//			'slug'				=> $input['title_1'],
//			'slug'				=> Str::slug($input['title_'.$properties->id]),
			'slug'				=> Str::slug($input['title_'.$app_locale_id]),
//			'user_id'			=> 1
			'user_id'			=>  $input['user_id']
		];

		$news->update($values);

//		$locales = Cache::get('languages');
		$locales = Cache::get('languages');
		$original_locale = Session::get('locale');

		foreach($locales as $locale => $properties)
		{

			App::setLocale($properties->locale);

			$values = [
				'content'		=> $input['content_'.$properties->id],
				'summary'		=> $input['summary_'.$properties->id],
				'title'			=> $input['title_'.$properties->id],

//				'slug'			=> Str::slug($input['title_'.$properties->id]),

				'meta_title'			=> $input['meta_title_'.$properties->id],
				'meta_keywords'			=> $input['meta_keywords_'.$properties->id],
				'meta_description'		=> $input['meta_description_'.$properties->id]
			];

			$news->update($values);

		}

		$this->manageBaum($input['parent_id'], $id);

		App::setLocale($original_locale, Config::get('app.fallback_locale'));
		return;
	}


// Functions ----------------------------------------------------------------------------------------------------

	public function getNewsID($name)
	{

		$id = DB::table('news')
			->where('name', '=', $name)
			->pluck('id');

		return $id;
	}

//	public function getParents($exceptId, $locale)
	public function getParents($locale_id, $id)
	{
		if ($id != null ) {
			$query = News::select('news_translations.title AS title', 'news.id AS id')
				->join('news_translations', 'news.id', '=', 'news_translations.news_id')
				->where('news_translations.locale_id', '=', $locale_id)
				->where('news.id', '!=', $id, 'AND')
				->get();
		} else {
			$query = News::select('news_translations.title AS title', 'news.id AS id')
			->join('news_translations', 'news.id', '=', 'news_translations.news_id')
			->where('news_translations.locale_id', '=', $locale_id)
			->get();
		}

		$parents = $query->lists('title', 'id');
//dd($parents);

		return $parents;
	}

	public function manageBaum($parent_id, $id)
	{
//dd($parent_id);

		if ($parent_id != 0 && $id != null) {
			$node = News::find($id);
			$node->makeChildOf($parent_id);
		}

		if ($parent_id == 0 && $id != null) {
			$node = News::find($id);
			$node->makeRoot();
		}

	}

	public function getArticleID($slug)
	{
//dd($slug);
/*
		$article_ID = DB::table('news_translations')
			->where('news_translations.slug', '=', $slug)
			->pluck('news_id');
*/
		$article_ID = DB::table('news')
			->where('slug', '=', $slug)
			->pluck('id');
//dd($article_ID);

		return $article_ID;
	}

	public function getNews($article_ID)
	{
//dd($article_ID);
 		$news = News::find($article_ID);
/*
		$article = DB::table('news')
			->join('news_translations', 'news.id', '=', 'news_translations.news_id')
			->where('news_translations.locale_id', '=', $locale_id)
//			->where('news.is_current', '=', 1, 'AND')
			->where('news.is_online', '=', 1, 'AND')
			->where('news.is_deleted', '=', 0, 'AND')
			->where('news_translations.slug', '=', $slug, 'AND')
			->pluck('news.id');
*/
//dd($news);

		return $news;
	}

	public function getArticle($locale_id, $slug)
	{
//dd($slug);
		$article = DB::table('news')
			->join('news_translations', 'news.id', '=', 'news_translations.news_id')
			->where('news_translations.locale_id', '=', $locale_id)
//			->where('news.is_current', '=', 1, 'AND')
			->where('news.is_online', '=', 1, 'AND')
			->where('news.is_deleted', '=', 0, 'AND')
//			->where('news_translations.slug', '=', $slug, 'AND')
			->where('news.slug', '=', $slug, 'AND')
			->pluck('news.id');
//dd($article);

 		$news = News::find($article);
dd($news);

/*
	   $article =  static::whereIsCurrent(1)
					   ->whereIsOnline(1)
					   ->whereIsDeleted(0)
					   ->whereSlug($slug)
					   ->first();
*/
		return $article;
	}


	public function getRoots($locale_id)
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

/*
			return static::whereIsCurrent(1)
							->whereIsOnline(1)
							->whereIsDeleted(0)
							->whereParentId(NULL)
							->where('slug', '<>', 'home-article')
							->where('slug', '<>', 'search')
							->where('slug', '<>', 'terms-conditions')
							->orderBy('order')
							->get();
*/
		// });

		// return $roots;
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

	public function getUsers()
	{
		$users = DB::table('users')->lists('email', 'id');
		return $users;
	}

	public function getNewsStatuses($locale_id)
	{
//		$news_statuses = DB::table('newss')->lists('name', 'id');
//dd($news_statuses);
/*
		$news_statuses = DB::table('news_statuses')
			->join('news_statuses', 'news_statuses.id', '=', 'news_status_translations.news_status_id')
			->where('news_status_translations.locale_id', '=', $locale_id)
			->orderBy('news_status_translations.id')
			->get();
*/
		$news_statuses = DB::table('news_status_translations')
			->where('locale_id', '=', $locale_id)
			->orderBy('id')
			->lists('name', 'id');

		return $news_statuses;
	}

// 	public function makeSlugFromTitle($title)
// 	{
// 		$slug = Str::slug($title);
// 		$count = NewsTranslation::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
//
// 		return $count ? "{$slug}-{$count}" : $slug;
// 	}

}
