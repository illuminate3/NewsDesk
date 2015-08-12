@extends($theme_back)


{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.news_status', 2) }} :: @parent
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('inline-scripts')
@stop


{{-- News --}}
@section('content')

<div class="row">
<h1>
{{--
	<p class="pull-right">
	<a href="/admin/news_statuses/create" class="btn btn-primary" title="{{ trans('kotoba::button.new') }}">
		<i class="fa fa-plus fa-fw"></i>
		{{ trans('kotoba::button.new') }}
	</a>
	</p>
--}}
	<i class="fa fa-paperclip fa-lg"></i>
		{{ Lang::choice('kotoba::cms.news_status', 2) }}
	<hr>
</h1>
</div>

@if (count($news_statuses))

<div class="row">
<table id="table" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>{{ trans('kotoba::table.name') }}</th>
			<th>{{ trans('kotoba::table.description') }}</th>
			<th>{{ Lang::choice('kotoba::table.action', 2) }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($news_statuses as $news_status)
			<tr>
				<td>
					{{ $news_status->translate($lang)->name }}
				</td>
				<td>
					{{ $news_status->translate($lang)->description }}
				</td>
				<td>
					<a href="/admin/news_statuses/{{ $news_status->id }}/edit" class="btn btn-success" title="{{ trans('kotoba::button.edit') }}">
						<i class="fa fa-pencil fa-fw"></i>
						{{ trans('kotoba::button.edit') }}
					</a>
				</td>
			</tr>
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
