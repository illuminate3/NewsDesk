@extends($theme_front)

{{-- Web site Title --}}
@section('title')
{{{ $article->title }}} :: @parent
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
		{{ $article->title }}
	</h1>
	<hr>
</div>


<div class="row">
<div class="col-sm-8 col-sm-offset-2">
	@if ( count($article->images) )
		@foreach($article->images as $image)
			<img src="<?= $image->image->url('landscape') ?>" class="mg-responsive img-rounded">
		@endforeach
	@endif
</div>
</div>

{{--
<div class="row">
	<h2>
		{!! $article->summary !!}
	</h2>
</div>
--}}

<div class="row padding-xl">
<div class="col-sm-12">
	{!! $article->content !!}
</div>
</div>


@if ( count($article->documents) )
	<div class="row">
		<h3>
			{{ Lang::choice('kotoba::files.file', 2) }}
		</h3>
		<hr>
	</div>

	<div class="row">
	<table id="table" class="table table-striped table-hover">
		<thead>
			<tr>
				<th>{{ Lang::choice('kotoba::table.user', 1) }}</th>
				<th>{{ Lang::choice('kotoba::table.document', 1) }}</th>
				<th>{{ trans('kotoba::table.size') }}</th>
				<th>{{ Lang::choice('kotoba::table.type', 1) }}</th>
				<th>{{ trans('kotoba::table.updated') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($article->documents as $document)
			<tr>
				<td>{{ $document->user_id }}</td>
				<td>{{ $document->document_file_name }}</td>
				<td>{{ $document->document_file_size }}</td>
				<td>{{ $document->document_content_type }}</td>
				<td>{{ $document->document_updated_at }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	</div>

@endif


@stop
