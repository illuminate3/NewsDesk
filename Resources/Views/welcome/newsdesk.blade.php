@extends('module_info')

{{-- Web site Title --}}
@section('title')
{{ Config::get('core.title') }} :: @parent
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('inline-scripts')
@stop

{{-- News --}}
@section('content')

	<div class="container">
		<div class="content">
			<a href="/">
				<img src="/assets/images/newsdesk.png" class="img-responsive">
			</a>
			<div class="title">
				<a href="/">
					Newsdesk
				</a>
			</div>
			<div class="quote">
				Newsdesk is a news system module for Rakko
			</div>
		</div>
	</div>

@stop
