@extends($theme_back)


{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.article', 2) }} :: @parent
@stop

@section('styles')
	<link href="{{ asset('assets/vendors/DataTables-1.10.7/plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop

@section('scripts')
	<script src="{{ asset('assets/vendors/DataTables-1.10.7/media/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/vendors/DataTables-1.10.7/plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
@stop

@section('inline-scripts')
$(document).ready(function() {

oTable =
	$('#table-archive').DataTable({
	});
	$('#table-draft').DataTable({
	});
	$('#table-publish').DataTable({
	});

});
@stop


{{-- News --}}
@section('content')

<div class="row">
<h1>
	<p class="pull-right">
	<a href="/admin/news/create" class="btn btn-primary" title="{{ trans('kotoba::button.new') }}">
		<i class="fa fa-plus fa-fw"></i>
		{{ trans('kotoba::button.new') }}
	</a>
	<a href="/admin/news/repair" class="btn btn-danger" title="{{ trans('kotoba::button.repair') }}">
		<i class="fa fa-wrench fa-fw"></i>
		{{ trans('kotoba::button.repair') }}
	</a>
	</p>
	<i class="fa fa-paperclip fa-lg"></i>
		{{ Lang::choice('kotoba::cms.article', 2) }}
		{{ Session::get('siteId') }}
-
		{{ Cache::get('siteId') }}

	<hr>
</h1>
</div>


@if (count($archives))

<div class="row">
<table id="table-publish" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>{{ trans('kotoba::table.title') }}</th>
			<th>{{ trans('kotoba::table.summary') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($archive_list as $item)
			{!! Html::newsNodesArchives($item, $lang, $locale_id) !!}
		@endforeach
	</tbody>

</table>
</div>


@else
<div class="alert alert-info">
	{{ trans('kotoba::general.error.not_found') }}
</div>
@endif


@stop
