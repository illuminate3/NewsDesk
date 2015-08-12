<?php

namespace App\Modules\NewsDesk\Http\Controllers;

use App\Modules\NewsDesk\Http\Models\PrintStatus;
use App\Modules\NewsDesk\Http\Repositories\PrintStatusRepository;

use Illuminate\Http\Request;
use App\Modules\NewsDesk\Http\Requests\DeleteRequest;
use App\Modules\NewsDesk\Http\Requests\PrintStatusCreateRequest;
use App\Modules\NewsDesk\Http\Requests\PrintStatusUpdateRequest;

use Datatables;
use Flash;
use Session;
use Theme;


class PrintStatusesController extends NewsDeskController {

	/**
	 * Status Repository
	 *
	 * @var Status
	 */
	protected $status;

	public function __construct(
			PrintStatusRepository $status_repo
		)
	{
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
//		$locales = $this->status_repo->getLocales();
		$print_statuses = $this->status_repo->all();
//dd($lang);

		return Theme::View('modules.newsdesk.print_statuses.index',
			compact(
				'lang',
//				'locales',
				'print_statuses'
				));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Theme::View('modules.newsdesk.print_statuses.create',  $this->status_repo->create());
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(
		PrintStatusCreateRequest $request
		)
	{
		$this->status->store($request->all());

		Flash::success( trans('kotoba::general.success.status_create') );
		return redirect('admin/print_statuses');
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
		$modal_route = 'admin.print_statuses.destroy';
		$modal_id = $id;
		$model = '$status';

		return Theme::View('modules.newsdesk.print_statuses.edit',
			compact(
				'status',
				'lang',
				'modal_title',
				'modal_body',
				'modal_route',
				'modal_id',
				'model'
		));
//		return View('modules.newsdesk.print_statuses.edit',  $this->status->edit($id));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(
		PrintStatusUpdateRequest $request,
		$id
		)
	{
//dd("update");
		$this->status->update($request->all(), $id);

		Flash::success( trans('kotoba::general.success.status_update') );
		return redirect('admin/print_statuses');
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

		return Redirect::route('admin.print_statuses.index');
	}


	/**
	* Datatables data
	*
	* @return Datatables JSON
	*/
	public function data()
	{
//		$query = PrintStatus::select(array('statuses.id','statuses.name','statuses.description'))
//			->orderBy('statuses.name', 'ASC');
//		$query = PrintStatus::select('id', 'name' 'description', 'updated_at');
//			->orderBy('name', 'ASC');
		$query = PrintStatus::select('id', 'name', 'description', 'updated_at');
//dd($query);

		return Datatables::of($query)
//			->remove_column('id')

			->addColumn(
				'actions',
				'
					<a href="{{ URL::to(\'admin/print_statuses/\' . $id . \'/edit\' ) }}" class="btn btn-success btn-sm" >
						<span class="glyphicon glyphicon-pencil"></span>  {{ trans("kotoba::button.edit") }}
					</a>
				'
				)

			->make(true);
	}

}
