<?php

namespace App\Modules\Newsdesk\Http\Controllers;

use App\Modules\Core\Http\Repositories\LocaleRepository;

use App\Modules\Newsdesk\Http\Models\News;
use App\Modules\Newsdesk\Http\Repositories\NewsRepository;

use Illuminate\Http\Request;
use App\Modules\Newsdesk\Http\Requests\DeleteRequest;
use App\Modules\Newsdesk\Http\Requests\NewsCreateRequest;
use App\Modules\Newsdesk\Http\Requests\NewsUpdateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

use Cache;
use Config;
use Flash;
use Lang;
use Route;
use Session;
//use TenantScope;
use Theme;

class NewsController extends NewsdeskController {

	/**
	 * News Repository
	 *
	 * @var News
	 */
	protected $news;

	public function __construct(
			LocaleRepository $locale_repo,
			News $news,
			NewsRepository $news_repo
		)
	{
		$this->locale_repo = $locale_repo;
		$this->news = $news;
		$this->news_repo = $news_repo;
// middleware
		parent::__construct();
// middleware
// 		$this->middleware('auth');
// 		$this->middleware('admin');
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$lang = Session::get('locale');
		$locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);

// Alerts
		$alerts = News::IsAlert()->get();
// 		$alert_list = News::IsAlert()->get();
// 		$alert_list = $alert_list->toHierarchy();
		$alert_list = $alerts->toHierarchy();
//dd($alerts);

// Archived
		$archives = News::IsArchived()->get();
// 		$archive_list = News::IsArchived()->get();
// 		$archive_list = $archive_list->toHierarchy();
		$archive_list = $archives->toHierarchy();
//dd($archives);


// Draft
		$drafts = News::IsDraft()->get();
// 		$draft_list = News::IsDraft()->get();
// 		$draft_list = $draft_list->toHierarchy();
		$draft_list = $drafts->toHierarchy();
//dd($drafts);


// Published
		$published = News::IsPublished()->NotAlert()->get();
// 		$publish_list = News::IsPublished()->NotAlert()->get();
// 		$publish_list = $publish_list->toHierarchy();
		$publish_list = $published->toHierarchy();
//dd($published);


/*
		$news = News::all();
//		$news = News::getNestedList('title', 'id', '>> ');
		$list = News::all();
		$list = $list->toHierarchy();
//dd($list);
*/
//		$sites = $news->sites->lists('name', 'id');
		$cache_site_id = Cache::get('siteId');
		$site_name = $this->news_repo->getSiteName($cache_site_id);
//dd($site_name);

		return Theme::View('modules.newsdesk.news.index',
			compact(
				'alerts',
				'alert_list',
				'archives',
				'archive_list',
				'drafts',
				'draft_list',
				'published',
				'publish_list',
				'site_name',
//				'cache_site_id',
				'lang',
				'locale_id'
			));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Theme::View('modules.newsdesk.news.create',  $this->news_repo->create());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(
		NewsCreateRequest $request
		)
	{
//dd($request);

		$this->news_repo->store($request->all());
		Cache::flush();

		Flash::success( trans('kotoba::cms.success.news_create') );
		return redirect('admin/news');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
// 		$news = $this->news_repo->findOrFail($id);
//
// 		return View::make('HR::news.show', compact('content'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$news = $this->news->with('images', 'documents')->find($id);
//		$news = $this->news->find($id)->images->documents;
//dd($news->images->image_file_name);

		$lang = Session::get('locale');
		$locale_id = $this->locale_repo->getLocaleID($lang);
//dd($locale_id);

//		$articlelist = $this->getParents( $exceptId = $this->id, $locales );

// 		$articlelist = $this->getParents($locale_id, $id);
// 		$articlelist = array('' => trans('kotoba::cms.no_parent')) + $articlelist;
//dd($articlelist);
		$all_articlelist = $this->news_repo->getParents($locale_id, null);
		$articlelist = array('' => trans('kotoba::cms.no_parent'));
		$articlelist = new Collection($articlelist);
		$articlelist = $articlelist->merge($all_articlelist);

		$users = $this->news_repo->getUsers();
//		$users = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::account.user', 1) ) + $users;
		$user_select = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::account.user', 1) );
		$user_select = new Collection($user_select);
		$users = $user_select->merge($users);

		$news_statuses = $this->news_repo->getNewsStatuses($locale_id);
		$news_statuses = array('' => trans('kotoba::general.command.select_a') . '&nbsp;' . Lang::choice('kotoba::cms.news_status', 1) ) + $news_statuses;

// 		$list_images = $this->getListImages();
// 		$list_images = array('' => trans('kotoba::general.command.select_an') . '&nbsp;' . Lang::choice('kotoba::cms.image', 1) ) + $list_images;

		$get_images = $this->news_repo->getImages();
//		$images = $this->news_repo->getListImages();
//dd($images);

		$get_documents = $this->news_repo->getDocuments();
		$documents = $news->documents->lists('document_file_name', 'id');
		$allDocuments = $this->news_repo->getListDocuments();
//dd($allDocuments);
//		$get_sites = $this->news_repo->getSites();
		$sites = $news->sites->lists('name', 'id');
		$allSites = $this->news_repo->getListSites();

//		$user_id = Auth::user()->id;

		$default_publish_status = Config::get('news.default_publish_status', '1');

		$modal_title = trans('kotoba::general.command.delete');
		$modal_body = trans('kotoba::general.ask.delete');
		$modal_route = 'admin.news.destroy';
		$modal_id = $id;
//		$model = '$news';
		$model = 'news';
//dd($modal_title);

		return Theme::View('modules.newsdesk.news.edit',
			compact(
				'articlelist',
				'default_publish_status',
				'documents',
				'allDocuments',
				'get_documents',
				'get_images',
//				'images',
				'sites',
				'allSites',
//				'get_sites',
				'lang',
				'news',
				'news_statuses',
				'users',
				'modal_title',
				'modal_body',
				'modal_route',
				'modal_id',
				'model'
			));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(
		NewsUpdateRequest $request,
		$id
		)
	{
//dd($request);

		$this->news_repo->update($request->all(), $id);
		Cache::flush();

		Flash::success( trans('kotoba::cms.success.news_update') );
		return redirect('admin/news');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
// 	public function destroy($id)
// 	{
// //dd($id);
// 		News::find($id)->delete();
//
// 		Flash::success( trans('kotoba::cms.success.news_delete') );
// 		return redirect('admin/news');
// 	}
	public function destroy($id)
	{
		$node = News::find($id);
		$parent = $node->parent()->get();
		$children = $node->children()->get();
//dd($parent);

		foreach($node->getImmediateDescendants() as $descendant) {
//			print_r($descendant->title . '<br>');
			$descendant->makeSiblingOf($node);
		}

		News::find($id)->delete();

		if ( News::isValidNestedSet() == false ) {
			News::rebuild();
		}

		Flash::success( trans('kotoba::cms.success.category_delete') );
		return redirect('admin/news');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function repairTree()
	{

		if ( News::isValidNestedSet() == false ) {
			News::rebuild();
			Flash::success( trans('kotoba::cms.success.repaired') );
			return redirect('admin/news');
		}

			Flash::info( trans('kotoba::cms.error.repair') );
			return redirect('admin/news');

	}


	/**
	* Datatables data
	*
	* @return Datatables JSON
	*/
	public function data()
	{
//		$query = News::select(array('news.id','news.name','news.description'))
//			->orderBy('news.name', 'ASC');
//		$query = News::select('id', 'name' 'description', 'updated_at');
//			->orderBy('name', 'ASC');
		$query = News::select('id', 'name', 'description', 'updated_at');
//dd($query);

		return Datatables::of($query)
//			->remove_column('id')

			->addColumn(
				'actions',
				'
					<a href="{{ URL::to(\'admin/news/\' . $id . \'/edit\' ) }}" class="btn btn-success btn-sm" >
						<span class="glyphicon glyphicon-pencil"></span>  {{ trans("kotoba::button.edit") }}
					</a>
				'
				)

			->make(true);
	}

}
