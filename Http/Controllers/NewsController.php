<?php

namespace App\Modules\NewsDesk\Http\Controllers;

use App\Modules\Core\Http\Repositories\LocaleRepository;

use App\Modules\NewsDesk\Http\Models\News;
use App\Modules\NewsDesk\Http\Repositories\NewsRepository;

use Illuminate\Http\Request;
use App\Modules\NewsDesk\Http\Requests\DeleteRequest;
use App\Modules\NewsDesk\Http\Requests\NewsCreateRequest;
use App\Modules\NewsDesk\Http\Requests\NewsUpdateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

use Cache;
//use Datatables;
use Flash;
use Route;
use Session;
use Theme;


class NewsController extends NewsDeskController {

	/**
	 * News Repository
	 *
	 * @var News
	 */
	protected $news;

	public function __construct(
			LocaleRepository $locale_repo,
			NewsRepository $news
		)
	{
		$this->locale_repo = $locale_repo;
		$this->news = $news;
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

		$news = $this->news->all();
//		$news = News::getNestedList('title', 'id', '>> ');
//dd($news);

		$list = News::all();
		$list = $list->toHierarchy();
//dd($list);


		return Theme::View('modules.newsdesk.news.index',
			compact(
				'news',
				'list',
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
		return Theme::View('modules.newsdesk.news.create',  $this->news->create());
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

		$this->news->store($request->all());
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
// 		$news = $this->news->findOrFail($id);
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
		$modal_title = trans('kotoba::general.command.delete');
		$modal_body = trans('kotoba::general.ask.delete');
		$modal_route = 'admin.news.destroy';
		$modal_id = $id;
//		$model = '$news';
		$model = 'news';
//dd($model);

		return Theme::View('modules.newsdesk.news.edit',
//		return Theme::View('news.edit',
			$this->news->edit($id),
				compact(
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

		$this->news->update($request->all(), $id);
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
	public function destroy($id)
	{
//dd($id);
		News::find($id)->delete();

		Flash::success( trans('kotoba::cms.success.news_delete') );
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
