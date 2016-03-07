@extends($theme_back)


{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.article', 2) }} :: @parent
@stop

@section('styles')
	<link href="{{ asset('assets/vendors/DataTables-1.10.10/DataTables-1.10.10/css/dataTables.bootstrap.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/chosen_v1.4.2/chosen.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/chosen_bootstrap.css') }}">
@stop

@section('scripts')
	<script src="{{ asset('assets/vendors/DataTables-1.10.10/dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/chosen_v1.4.2/chosen.jquery.min.js') }}"></script>
@stop

@section('inline-scripts')
$(document).ready(function() {

	$(".chosen-select").chosen({
		width: "100%"
	});

	$('#table-publish').DataTable({
		'pageLength': 25
		});
	$('#table-draft').DataTable( {
		'pageLength': 25
		});
	$('#table-archive').DataTable( {
		'pageLength': 25
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
	@if ( Auth::user()->is('super_admin') )
		<a href="/admin/news/repair" class="btn btn-danger" title="{{ trans('kotoba::button.repair') }}">
			<i class="fa fa-wrench fa-fw"></i>
			{{ trans('kotoba::button.repair') }}
		</a>
	@endif
{{--
	<div class="dropdown pull-right padding-right">
		<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			{{ Lang::choice('kotoba::cms.site', 2) }}
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			@foreach( $total_sites as $site)
				<li>
					<a rel="alternate" hreflang="{{ $site->id }}" href="/admin/change-site/{{ $site->id }}">
						{{{ $site->slug }}} {{ $site->id }}
					</a>
				</li>
			@endforeach
		</ul>
	</div>
--}}
	</p>
	<i class="fa fa-tv fa-lg"></i>
		{{ Lang::choice('kotoba::cms.article', 2) }}
	<hr>
</h1>
</div>


<!-- Nav tabs -->
<ul class="nav nav-tabs nav-justified" role="tablist">
	<li role="presentation" class="active">
		<a href="#published" aria-controls="published" role="tab" data-toggle="tab">
		<i class="fa fa-newspaper-o fa-lg"></i>
		{{ trans('kotoba::cms.published') }}
		</a>
	</li>
	<li role="presentation">
		<a href="#draft" aria-controls="draft" role="tab" data-toggle="tab">
		<i class="fa fa-pencil fa-lg"></i>
		{{ Lang::choice('kotoba::cms.draft', 2) }}
		</a>
	</li>
	<li role="presentation">
		<a href="#alert" aria-controls="alert" role="tab" data-toggle="tab">
		<i class="fa fa-bullhorn fa-lg"></i>
		{{ Lang::choice('kotoba::general.alert', 2) }}
		</a>
	</li>
	<li role="presentation">
		<a href="#archive" aria-controls="archive" role="tab" data-toggle="tab">
		<i class="fa fa-archive fa-lg"></i>
		{{ Lang::choice('kotoba::cms.archive', 2) }}
		</a>
	</li>
</ul>

<!-- Tab panes -->
<div class="tab-content padding">

	<div role="tabpanel" class="tab-pane active" id="published">
	<div class="tab-content padding">

		@if (count($published))

		<div class="row">
		<table id="table-publish" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ trans('kotoba::table.title') }}</th>
					<th>{{ trans('kotoba::table.summary') }}</th>
					<th>{{ trans('kotoba::table.slug') }}</th>
					<th>{{ trans('kotoba::table.position') }}</th>
					<th>{{ trans('kotoba::table.online') }}</th>
					<th>{{ Lang::choice('kotoba::table.alert', 1) }}</th>
					<th>{{ trans('kotoba::table.banner') }}</th>
					<th>{{ trans('kotoba::table.featured') }}</th>
					<th>{{ Lang::choice('kotoba::table.zone', 1) }}</th>
					<th>{{ Lang::choice('kotoba::table.action', 2) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($publish_list as $item)
					{!! Html::newsNodes($item, $lang, $locale_id) !!}
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
	</div><!-- ./ published panel -->

	<div role="tabpanel" class="tab-pane" id="draft">
	<div class="tab-content padding">

		@if (count($drafts))

		<div class="row">
		<table id="table-draft" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ trans('kotoba::table.title') }}</th>
					<th>{{ trans('kotoba::table.summary') }}</th>
					<th>{{ trans('kotoba::table.slug') }}</th>
					<th>{{ trans('kotoba::table.position') }}</th>
					<th>{{ trans('kotoba::table.online') }}</th>
					<th>{{ Lang::choice('kotoba::table.alert', 1) }}</th>
					<th>{{ trans('kotoba::table.banner') }}</th>
					<th>{{ trans('kotoba::table.featured') }}</th>
					<th>{{ Lang::choice('kotoba::table.zone', 1) }}</th>
					<th>{{ Lang::choice('kotoba::table.action', 2) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($draft_list as $item)
					{!! Html::newsNodes($item, $lang, $locale_id) !!}
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
	</div><!-- ./ draft panel -->

	<div role="tabpanel" class="tab-pane" id="alert">
	<div class="tab-content padding">

		@if (count($alerts))

		<div class="row">
		<table id="table-archive" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ trans('kotoba::table.title') }}</th>
					<th>{{ trans('kotoba::table.summary') }}</th>
					<th>{{ trans('kotoba::table.slug') }}</th>
					<th>{{ trans('kotoba::table.position') }}</th>
					<th>{{ trans('kotoba::table.online') }}</th>
					<th>{{ Lang::choice('kotoba::table.alert', 1) }}</th>
					<th>{{ trans('kotoba::table.banner') }}</th>
					<th>{{ trans('kotoba::table.featured') }}</th>
					<th>{{ Lang::choice('kotoba::table.zone', 1) }}</th>
					<th>{{ Lang::choice('kotoba::table.action', 2) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($alert_list as $item)
					{!! Html::newsNodes($item, $lang, $locale_id) !!}
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
	</div><!-- ./ alert panel -->

	<div role="tabpanel" class="tab-pane" id="archive">
	<div class="tab-content padding">

		@if (count($archives))

		<div class="row">
		<table id="table-archive" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ trans('kotoba::table.title') }}</th>
					<th>{{ trans('kotoba::table.summary') }}</th>
					<th>{{ trans('kotoba::table.slug') }}</th>
					<th>{{ trans('kotoba::table.position') }}</th>
					<th>{{ trans('kotoba::table.online') }}</th>
					<th>{{ Lang::choice('kotoba::table.alert', 1) }}</th>
					<th>{{ trans('kotoba::table.banner') }}</th>
					<th>{{ trans('kotoba::table.featured') }}</th>
					<th>{{ Lang::choice('kotoba::table.zone', 1) }}</th>
					<th>{{ Lang::choice('kotoba::table.action', 2) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($archive_list as $item)
					{!! Html::newsNodes($item, $lang, $locale_id) !!}
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
	</div><!-- ./ archive panel -->

</div><!-- ./ tab panes -->


@stop
