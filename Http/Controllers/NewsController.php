<?php

namespace App\Modules\NewsDesk\Http\Controllers;

use App\Modules\Core\Http\Repositories\LocaleRepository;

use App\Modules\NewsDesk\Http\Models\Content;
use App\Modules\NewsDesk\Http\Repositories\ContentRepository;

use Illuminate\Http\Request;
use App\Modules\NewsDesk\Http\Requests\DeleteRequest;
use App\Modules\NewsDesk\Http\Requests\ContentCreateRequest;
use App\Modules\NewsDesk\Http\Requests\ContentUpdateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

use Cache;
//use Datatables;
use Flash;
use Session;
use Theme;


class ContentsController extends NewsDeskController {

	/**
	 * Content Repository
	 *
	 * @var Content
	 */
	protected $content;

	public function __construct(
			LocaleRepository $locale_repo,
			ContentRepository $content
		)
	{
		$this->locale_repo = $locale_repo;
		$this->content = $content;
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

		$contents = $this->content->all();
//		$contents = Content::getNestedList('title', 'id', '>> ');
//dd($contents);

		$list = Content::all();
		$list = $list->toHierarchy();
//dd($list);


		return Theme::View('modules.newsdesk.contents.index',
			compact(
				'contents',
				'list',
				'lang',
				'locales',
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
		return Theme::View('modules.newsdesk.contents.create',  $this->content->create());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(
		ContentCreateRequest $request
		)
	{
//dd($request);

		$this->content->store($request->all());
		Cache::flush();

		Flash::success( trans('kotoba::cms.success.content_create') );
		return redirect('admin/contents');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
// 		$content = $this->content->findOrFail($id);
//
// 		return View::make('HR::contents.show', compact('content'));
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
		$modal_route = 'admin.contents.destroy';
		$modal_id = $id;
//		$model = '$content';
		$model = 'content';
//dd($model);

		return Theme::View('modules.newsdesk.contents.edit',
//		return Theme::View('contents.edit',
			$this->content->edit($id),
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
		ContentUpdateRequest $request,
		$id
		)
	{
//dd($request);

		$this->content->update($request->all(), $id);
		Cache::flush();

		Flash::success( trans('kotoba::cms.success.content_update') );
		return redirect('admin/contents');
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
		Content::find($id)->delete();

		Flash::success( trans('kotoba::cms.success.content_delete') );
		return redirect('admin/contents');
	}


	/**
	* Datatables data
	*
	* @return Datatables JSON
	*/
	public function data()
	{
//		$query = Content::select(array('contents.id','contents.name','contents.description'))
//			->orderBy('contents.name', 'ASC');
//		$query = Content::select('id', 'name' 'description', 'updated_at');
//			->orderBy('name', 'ASC');
		$query = Content::select('id', 'name', 'description', 'updated_at');
//dd($query);

		return Datatables::of($query)
//			->remove_column('id')

			->addColumn(
				'actions',
				'
					<a href="{{ URL::to(\'admin/contents/\' . $id . \'/edit\' ) }}" class="btn btn-success btn-sm" >
						<span class="glyphicon glyphicon-pencil"></span>  {{ trans("kotoba::button.edit") }}
					</a>
				'
				)

			->make(true);
	}

}
