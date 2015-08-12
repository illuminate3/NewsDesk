@extends($theme_back)


{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.content', 2) }} :: @parent
@stop

@section('styles')
	<link href="{{ asset('assets/vendors/DataTables-1.10.5/plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop

@section('scripts')
	<script src="{{ asset('assets/vendors/DataTables-1.10.5/media/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/vendors/DataTables-1.10.5/plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
@stop

@section('inline-scripts')
$(document).ready(function() {
oTable =
	$('#table').DataTable({
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
	</p>
	<i class="fa fa-paperclip fa-lg"></i>
		{{ Lang::choice('kotoba::cms.content', 2) }}
	<hr>
</h1>
</div>

@if (count($news))

<div class="row">
<table id="table" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>{{ trans('kotoba::table.title') }}</th>
			<th>{{ trans('kotoba::table.summary') }}</th>
			<th>{{ trans('kotoba::table.slug') }}</th>
			<th>{{ trans('kotoba::table.position') }}</th>
			<th>{{ trans('kotoba::table.online') }}</th>
{{--
			<th>{{ trans('kotoba::table.deleted') }}</th>
--}}
			<th>{{ Lang::choice('kotoba::table.action', 2) }}</th>
		</tr>
	</thead>
	<tbody>
{{--
		@foreach ($news as $news)
			<tr>
				<td>{{ $news->title }}</td>
				<td>{!! $news->summary !!}</td>
				<td>{{ $news->slug }}</td>
				<td>{{ $news->order }}</td>
				<td>{{ $news->present()->news_status($news->news_status_id) }}</td>
				<td>
					<a href="/admin/news/{{ $news->id }}/edit" class="btn btn-success" title="{{ trans('kotoba::button.edit') }}">
						<i class="fa fa-pencil fa-fw"></i>
						{{ trans('kotoba::button.edit') }}
					</a>
				</td>
			</tr>
		@endforeach
--}}
		@foreach($list as $item)
			{!! Html::contentNodes($item, $lang) !!}
		@endforeach
	</tbody>

</table>
</div>


@else
<div class="alert alert-info">
	{{ trans('kotoba::general.error.not_found') }}
</div>
@endif

</div>


@stop
