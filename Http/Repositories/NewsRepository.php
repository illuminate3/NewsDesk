<?php

namespace App\Modules\Newsdesk\Http\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Newsdesk\Http\Repositories\BaseRepository as BaseRepository;

use App\Modules\Core\Http\Repositories\LocaleRepository;
use App\Modules\Filex\Http\Models\Image;
use App\Modules\Filex\Http\Models\Document;

use App\Modules\Core\Http\Models\Locale;
use App\Modules\Newsdesk\Http\Models\News;
use App\Modules\Newsdesk\Http\Models\NewsTranslation;

use App;
use Auth;
use Cache;
use Config;
use DB;
use Input;
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

		$news_statuses = $this->getNewsStatuses($locale_id);
		$news_statuses = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::cms.news_status', 1) ) + $news_statuses;

		$get_images = $this->getImages();

		$get_documents = $this->getDocuments();

		$user_id = Auth::user()->id;

		return compact(
			'articlelist',
			'get_documents',
			'get_images',
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
		//
	}


	/**
	 * Get user collection.
	 *
	 * @param  int  $id
	 * @return Illuminate\Support\Collection
	 */
	public function edit($id)
	{
		//
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

		if ( !isset($input['is_banner']) ) {
			$is_banner = 0;
		} else {
			$is_banner = $input['is_banner'];
		}

		if ( !isset($input['is_featured']) ) {
			$is_featured = 0;
		} else {
			$is_featured = $input['is_featured'];
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

		$slug = Str::slug($input['title_'.$app_locale_id]);

		$values = [
			'class'						=> $class,
			'is_banner'					=> $is_banner,
			'is_featured'				=> $is_featured,
			'is_timed'					=> $is_timed,
			'publish_end'				=> $publish_end,
			'publish_start'				=> $publish_start,
			'order'						=> $input['order'],
			'news_status_id'			=> $input['news_status_id'],
			'slug'						=> $slug,
			'user_id'					=>  $input['user_id']
		];
//dd($values);

		$news = News::create($values);
//		$last_insert_id = DB::getPdo()->lastInsertId();
		$last_insert_id = $this->getNewsID($slug);
//dd($last_insert_id);

		$locales = Cache::get('languages');
		$original_locale = Session::get('locale');

		foreach($locales as $locale => $properties)
		{

			App::setLocale($properties->locale);

			$values = [
				'content'				=> $input['content_'.$properties->id],
				'summary'				=> $input['summary_'.$properties->id],
				'title'					=> $input['title_'.$properties->id],
				'meta_title'			=> $input['meta_title_'.$properties->id],
				'meta_keywords'			=> $input['meta_keywords_'.$properties->id],
				'meta_description'		=> $input['meta_description_'.$properties->id]
			];

			$news->update($values);
		}

		$this->manageBaum($input['parent_id'], null);

		App::setLocale($original_locale, Config::get('app.fallback_locale'));


		$document_id = Input::get('document_id');
		if ( $document_id != null ) {
			$this->attachDocument($last_insert_id, $document_id);
		}
//dd($document_id);

		$image_id = Input::get('image_id');
		if ( $image_id != null ) {
			$this->attachImage($last_insert_id, $image_id);
		}

		$news = News::find(last_insert_id);
		$values = [
			'image_id'					=> $image_id
		];
		$news->update($values);

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

		if ( !isset($input['is_banner']) ) {
			$is_banner = 0;
		} else {
			$is_banner = $input['is_banner'];
		}

		if ( !isset($input['is_featured']) ) {
			$is_featured = 0;
		} else {
			$is_featured = $input['is_featured'];
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

		if ( isset($input['previous_image_id']) ) {
			$image_id = $input['previous_image_id'];
		} else {
			$image_id = null;
		}


		$news = News::find($id);

		$lang = Session::get('locale');
		$app_locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);

		$values = [
			'class'						=> $class,
			'image_id'					=> $image_id,
			'is_banner'					=> $is_banner,
			'is_featured'				=> $is_featured,
			'is_timed'					=> $is_timed,
			'publish_end'				=> $publish_end,
			'publish_start'				=> $publish_start,
			'order'						=> $input['order'],
			'news_status_id'			=> $input['news_status_id'],
			'slug'						=> Str::slug($input['title_'.$app_locale_id]),
			'user_id'					=>  $input['user_id']
		];

		$news->update($values);

//		$locales = Cache::get('languages');
		$locales = Cache::get('languages');
		$original_locale = Session::get('locale');

		foreach($locales as $locale => $properties)
		{

			App::setLocale($properties->locale);

			$values = [
				'content'				=> $input['content_'.$properties->id],
				'summary'				=> $input['summary_'.$properties->id],
				'title'					=> $input['title_'.$properties->id],
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


	public function attachDocument($id, $document_id)
	{
//dd($id);
		$news = $this->model->find($id);
		$news->documents()->attach($document_id);
	}

	public function detachDocument($id, $document_id)
	{
//dd($image_id);
		$document = $this->model->find($id)->documents()->detach();
	}


	public function attachImage($id, $image_id)
	{
//dd($image_id);
		$news = $this->model->find($id);
		$news->images()->attach($image_id);
	}

	public function detachImage($id, $image_id)
	{
//dd($image_id);
		$image = $this->model->find($id)->images()->detach();
	}


	public function getNewsID($slug)
	{

		$id = DB::table('news')
			->where('slug', '=', $slug)
			->pluck('id');

		return $id;
	}






	public function getImages()
	{
		$images = DB::table('images')->get();
		return $images;
	}

	public function getDocuments()
	{
		$documents = DB::table('documents')->get();
		return $documents;
	}

	public function getListImages()
	{
		$images = DB::table('images')->lists('image_file_name', 'id');
		return $images;
	}

	public function getUsers()
	{
		$users = DB::table('users')->lists('email', 'id');
		return $users;
	}


	public function getNewsStatuses($locale_id)
	{
		$news_statuses = DB::table('news_status_translations')
			->where('locale_id', '=', $locale_id)
			->orderBy('id')
			->lists('name', 'id');

		return $news_statuses;
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
dd($news);

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


}
