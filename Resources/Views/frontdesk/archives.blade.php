@extends($theme_front)


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
	<i class="fa fa-newspaper-o fa-lg"></i>
		{{ Lang::choice('kotoba::cms.article', 2) }}
{{--
		{{ Session::get('siteId') }}
-
		{{ Cache::get('siteId') }}
--}}
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
