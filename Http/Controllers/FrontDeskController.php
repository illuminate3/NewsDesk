<?php

namespace App\Modules\Newsdesk\Http\Controllers;

use App\Modules\Core\Http\Repositories\LocaleRepository;

use App\Modules\Newsdesk\Http\Models\News;
use App\Modules\Newsdesk\Http\Repositories\NewsRepository;

use Illuminate\Http\Request;
use App\Modules\Newsdesk\Http\Requests\DeleteRequest;
use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

use Carbon\Carbon;
use Config;
use Flash;
use Hashids\Hashids;
use Meta;
//use SEOMeta;
use Session;
use Route;
use TenantScope;
use Theme;

class FrontDeskController extends NewsdeskController {

	public function __construct(
			LocaleRepository $locale_repo,
			News $news,
			NewsRepository $news_repo
		)
	{
//dd('__construct');
		$this->locale_repo = $locale_repo;
		$this->news = $news;
		$this->news_repo = $news_repo;

		$lang = Session::get('locale');
		$locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);


		$this->article = Route::current()->parameter('news');
//		$this->article = Route::current()->getUri();
//dd($this->article);

		$slugs = explode('/', $this->article);
//dd($slugs);
		$lastSlug = Route::current()->getName() == 'search' ? 'search' : $slugs[count($slugs)-1];
//dd($lastSlug);

		$article_ID = $this->news_repo->getArticleID($slug = $lastSlug);
//dd($article_ID);
		$this->currentArticle = $this->news->with('images', 'documents', 'sites')->find($article_ID);
//		$this->currentArticle = $this->news_repo->with('images', 'documents')->getNews($article_ID);
//dd($this->currentArticle);

	}

	public function get_article()
	{
//dd('get_article');
//dd($this->currentArticle);
		if ( $this->currentArticle ) {
// 			$mainMenu = NiftyMenus::getMainMenu( $this->currentArticle );
// dd($mainMenu);
//dd($this->currentArticle);
// 			$root = $this->currentArticle->getRoot();
// 			$secMenu = NiftyMenus::getSecMenu($root, $this->currentArticle );

//			return View::make('frontends.article', ['news' => $this->currentArticle, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);

			$article = $this->currentArticle;
			$this->setSEO($article);
/*
    0 => "meta_description"
    1 => "meta_keywords"
    2 => "meta_title"
    3 => "content"
    4 => "slug"
    5 => "summary"
    6 => "title"
*/

//dd($article);
// 			$mainMenu = $mainMenu;
// 			$secMenu = $secMenu;

//		$document = $this->document->find($id);
//		$extension = File::extension($document->document_file_name);
		$lang = Session::get('locale');
		$js_lang = array(
//			'CLOSE' => trans('kotoba::button.close'),
			'CLOSE' => "Close",
//			'TITLE' => $document->document_file_name
			'TITLE' => "View Document"
		);

		$modal_title = trans('kotoba::general.command.delete');
		$modal_body = trans('kotoba::general.ask.delete');
		$modal_route = 'admin.documents.destroy';
		$modal_id = $article->id;
		$model = '$document';


		return Theme::View('modules.newsdesk.frontdesk.index',
			compact(
				'js_lang',
				'article',
				'modal_title',
				'modal_body',
				'modal_route',
				'modal_id',
				'model'
			));
		}
		else
			App::abort(404);
	}

	public function getArchives()
	{

		$lang = Session::get('locale');
		$locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);
//dd(session()->get('siteId'));

// $models = News::all()->with('sites');
// dd($models);
//TenantScope::addTenant( Config::get('tenant.default_tenant_columns'), session()->get('siteId') );
//TenantScope::addTenant('site_id', 1);

// 		$archives = News::IsPublished()->NotAlert()->get();
// //		$archives = News::all();
// dd($archives);
// 		$archive_list = News::all();

/*
// Archived
		$archives = News::IsArchived()->get();
		$archive_list = News::IsArchived()->get();
		$archive_list = $archive_list->toHierarchy();
//dd($archives);
*/
// 		$archives = News::all();
// 		$archive_list = News::all();
// 		$list = $archive_list->toHierarchy();
		$archives = News::IsPublished()->NotAlert()->get();
//		$archives = $this->news->with('sites')->IsPublished()->NotAlert()->get();
//dd($archives);
		$archive_list = News::IsPublished()->NotAlert()->get();
		$archive_list = $archive_list->toHierarchy();


		return Theme::View('modules.newsdesk.frontdesk.archives',
			compact(
				'archives',
				'archive_list',
				'lang',
				'locale_id'
			));
	}


	public function setSEO($article)
	{
//dd($article);
// 		SEOMeta::setTitle($article->meta_title);
// 		SEOMeta::setDescription($article->meta_description);
// 		SEOMeta::setKeywords($article->meta_keywords);
		Meta::setTitle($article->meta_title);
		Meta::setDescription($article->meta_description);
		Meta::setKeywords($article->meta_keywords);
	}


}
