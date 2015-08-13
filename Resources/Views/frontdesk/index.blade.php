@extends($theme_front)

{{-- Web site Title --}}
@section('title')
{{{ $news->title }}} :: @parent
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
		{{ $news->title }}
	</h1>
</div>

<div class="row">
	<h2>
		{!! $news->summary !!}
	</h2>
</div>

<div class="row">
	{!! $news->content !!}
</div>

@stop
