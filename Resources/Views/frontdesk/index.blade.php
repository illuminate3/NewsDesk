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

@stop
