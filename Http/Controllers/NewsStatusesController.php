<?php
namespace App\Modules\Newsdesk\Http\Controllers;

use App\Modules\Core\Http\Repositories\LocaleRepository;

use App\Modules\Newsdesk\Http\Models\NewsStatus;
use App\Modules\Newsdesk\Http\Repositories\NewsStatusRepository;

use Illuminate\Http\Request;
use App\Modules\Newsdesk\Http\Requests\DeleteRequest;
use App\Modules\Newsdesk\Http\Requests\NewsStatusCreateRequest;
use App\Modules\Newsdesk\Http\Requests\NewsStatusUpdateRequest;

use Datatables;
use Flash;
use Session;
use Theme;

class NewsStatusesController extends NewsdeskController {

	/**
	 * Status Repository
	 *
	 * @var Status
	 */
	protected $status;

	public function __construct(
			LocaleRepository $locale_repo,
			NewsStatus $status,
			NewsStatusRepository $status_repo
		)
	{
		$this->locale_repo = $locale_repo;
		$this->status = $status;
		$this->status_repo = $status_repo;
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

		$news_statuses = $this->status_repo->all();
//dd($lang);

		return Theme::View('modules.newsdesk.news_statuses.index',
			compact(
				'news_statuses',
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
		return Theme::View('modules.newsdesk.news_statuses.create',  $this->status_repo->create());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(
		NewsStatusCreateRequest $request
		)
	{
		$this->status_repo->store($request->all());

		Flash::success( trans('kotoba::general.success.status_create') );
		return redirect('admin/news_statuses');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
// 		$status = $this->status->findOrFail($id);
//
// 		return View::make('HR::statuses.show', compact('status'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$status = $this->status_repo->edit($id);
		$lang = Session::get('locale');

		$modal_title = trans('kotoba::general.command.delete');
		$modal_body = trans('kotoba::general.ask.delete');
		$modal_route = 'admin.news_statuses.destroy';
		$modal_id = $id;
		$model = '$status';

		return Theme::View('modules.newsdesk.news_statuses.edit',
			compact(
				'status',
				'lang',
				'modal_title',
				'modal_body',
				'modal_route',
				'modal_id',
				'model'
		));
//		return View('modules.newsdesk.news_statuses.edit',  $this->status->edit($id));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(
		NewsStatusUpdateRequest $request,
		$id
		)
	{
//dd("update");
		$this->status_repo->update($request->all(), $id);

		Flash::success( trans('kotoba::general.success.status_update') );
		return redirect('admin/news_statuses');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->status->find($id)->delete();

		return Redirect::route('admin.news_statuses.index');
	}


	/**
	* Datatables data
	*
	* @return Datatables JSON
	*/
	public function data()
	{
//		$query = NewsStatus::select(array('statuses.id','statuses.name','statuses.description'))
//			->orderBy('statuses.name', 'ASC');
//		$query = NewsStatus::select('id', 'name' 'description', 'updated_at');
//			->orderBy('name', 'ASC');
		$query = NewsStatus::select('id', 'name', 'description', 'updated_at');
//dd($query);

		return Datatables::of($query)
//			->remove_column('id')

			->addColumn(
				'actions',
				'
					<a href="{{ URL::to(\'admin/news_statuses/\' . $id . \'/edit\' ) }}" class="btn btn-success btn-sm" >
						<span class="glyphicon glyphicon-pencil"></span>  {{ trans("kotoba::button.edit") }}
					</a>
				'
				)

			->make(true);
	}

}
