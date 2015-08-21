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
</div>

<div class="row">
	<h2>
		{!! $article->summary !!}
	</h2>
</div>

<div class="row">
	{!! $article->content !!}
</div>


	<h3>
		{{ Lang::choice('kotoba::cms.image', 2) }}
	</h3>

	<hr>

	@if ( count($article->images) )
		@foreach($article->images as $image)

		<div class="thumbnail">
			<img src="<?= $image->image->url('preview') ?>" class="img-rounded">
			<div class="caption">
				<h3>
					{{ $image->image_file_name }}
				</h3>
				<p>
					{{ $image->image_file_size }}
					<br>
					{{ $image->image_content_type }}
					<br>
					{{ $image->image_updated_at }}
				</p>
			</div>
		</div>

		@endforeach
	@endif


	<h3>
		{{ Lang::choice('kotoba::files.file', 2) }}
	</h3>

	<hr>

	@if ( count($article->documents) )

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
