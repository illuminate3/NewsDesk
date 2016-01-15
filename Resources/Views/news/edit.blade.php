@extends($theme_back)


{{-- Web site Title --}}
@section('title')
{{ Lang::choice('kotoba::cms.article', 2) }} :: @parent
@stop

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/multi-select_v0_9_12/css/multi-select.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-datepicker/css/datepicker3.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/chosen_v1.4.2/chosen.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/chosen_bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/summernote_0.6.16/summernote.css') }}">
@stop

@section('scripts')
	<script type="text/javascript" src="{{ asset('assets/vendors/multi-select_v0_9_12/js/jquery.multi-select.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-datepicker/js/datepicker-settings.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/chosen_v1.4.2/chosen.jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/summernote_0.6.16/summernote.min.js') }}"></script>
@stop

@section('inline-scripts')
	jQuery(document).ready(function($) {
		$('#my-select').multiSelect(
			{
				selectableHeader: "<div class='bg-primary padding-md'>{{ trans('kotoba::general.available') }}</div>",
				selectionHeader: "<div class='bg-primary padding-md'>{{ trans('kotoba::general.assigned') }}</div>"
			}
		)
		$(".chosen-select").chosen({
			width: "100%"
		});
		$('.summernote').summernote({
			height: 300,				// set minimum height of editor
			minHeight: null,			// set minimum height of editor
			maxHeight: null,			// set maximum height of editor
			focus: true,				// set focus to editable area after initializing summernote
		});
		$('.summersummary').summernote({
			height: 100,				// set minimum height of editor
			minHeight: null,			// set minimum height of editor
			maxHeight: null,			// set maximum height of editor
			focus: true,				// set focus to editable area after initializing summernote
		});
	});

function setImage(select){
	var image = document.getElementsByName("image-swap")[0];
	image.src = select.options[select.selectedIndex].label;
		if ( image.src == "" ) {
			$("#imagePreview").append("displays image here");
		}
}
@stop


{{-- News --}}
@section('content')


<div class="row margin-top-lg">
{!! Form::model(
	$news,
	[
		'route' => ['admin.news.update', $news->id],
		'method' => 'PATCH',
		'class' => 'form',
		'files' => true
	]
) !!}

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-justified" role="tablist">
	<li role="presentation" class="active"><a href="#content" aria-controls="content" role="tab" data-toggle="tab">{{ trans('kotoba::cms.content') }}</a></li>
	<li role="presentation"><a href="#meta" aria-controls="meta" role="tab" data-toggle="tab">{{ trans('kotoba::cms.meta') }}</a></li>
	<li role="presentation"><a href="#files" aria-controls="settings" role="tab" data-toggle="tab">{{ Lang::choice('kotoba::files.file', 2) }}</a></li>
	<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">{{ Lang::choice('kotoba::general.setting', 2) }}</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content padding">

	<div role="tabpanel" class="tab-pane active" id="content">
	<div class="tab-content">

	@if (count($languages))

	<ul class="nav nav-tabs">
		@foreach( $languages as $language)
			<li class="@if ($language->locale == $lang)active @endif">
				<a href="#{{ $language->id }}" data-target="#lang_{{ $language->id }}" data-toggle="tab">{{{ $language->name }}}</a>
			</li>
		@endforeach
	</ul>

	@foreach( $languages as $language)
	<div role="tabpanel" class="tab-pane padding fade @if ($language->locale == $lang)in active @endif" id="lang_{{{ $language->id }}}">

			<div class="form-group">
				<label for="title">{{ trans('kotoba::general.title') }}</label>
				<input type="text" class="form-control" name="{{ 'title_'. $language->id }}" id="{{ 'title_'. $language->id }}" value="{{  $news->translate($language->locale)->title }}">
			</div>
			<div class="form-group">
				<label for="content">{{ trans('kotoba::cms.content') }}</label>
				<textarea class="form-control summernote" name="{{ 'content_'. $language->id }}" id="{{ 'content_'. $language->id }}">{{ $news->translate($language->locale)->content }}</textarea>
			</div>

			<div class="form-group">
				<label for="summary">{{ trans('kotoba::cms.summary') }}</label>
				<textarea class="form-control summersummary" name="{{ 'summary_'. $language->id }}" id="{{ 'summary_'. $language->id }}">{{ $news->translate($language->locale)->summary }}</textarea>
			</div>

	</div><!-- ./ $lang panel -->
	@endforeach

	@endif

	</div>
	</div><!-- ./ content panel -->

	<div role="tabpanel" class="tab-pane" id="meta">
	<div class="tab-content">

	@if (count($languages))

	<ul class="nav nav-tabs">
		@foreach( $languages as $language)
			<li class="@if ($language->locale == $lang)active @endif">
				<a href="#{{ $language->id }}" data-target="#meta_{{ $language->id }}" data-toggle="tab">{{{ $language->name }}}</a>
			</li>
		@endforeach
	</ul>

	@foreach( $languages as $language)
	<div role="tabpanel" class="tab-pane padding fade @if ($language->locale == $lang)in active @endif" id="meta_{{{ $language->id }}}">

		<div class="form-group">
			<label for="title">{{ trans('kotoba::cms.meta_title') }}</label>
			<input type="text" class="form-control" name="{{ 'meta_title_'. $language->id }}" id="{{ 'meta_title_'. $language->id }}" value="{{  $news->translate($language->locale)->meta_title }}">
		</div>

		<div class="form-group">
			<label for="title">{{ trans('kotoba::cms.meta_keywords') }}</label>
			<input type="text" class="form-control" name="{{ 'meta_keywords_'. $language->id }}" id="{{ 'meta_keywords_'. $language->id }}" value="{{  $news->translate($language->locale)->meta_keywords }}">
		</div>

		<div class="form-group">
			<label for="title">{{ trans('kotoba::cms.meta_description') }}</label>
			<input type="text" class="form-control" name="{{ 'meta_description_'. $language->id }}" id="{{ 'meta_description_'. $language->id }}" value="{{  $news->translate($language->locale)->meta_description }}">
		</div>

	</div><!-- ./ $lang panel -->
	@endforeach

	@endif


	</div>
	</div><!-- ./ meta panel -->

	<div role="tabpanel" class="tab-pane" id="files">
	<div class="tab-content">


<div class="row">
<div class="col-sm-6">
<div class="padding">

	<h3>
		{{ trans('kotoba::files.media') }}
	</h3>

	<hr>

	@if ( count($news->images) )

		<h4>
			{{ Lang::choice('kotoba::cms.image', 1) }}
		</h4>
		<hr>

		@foreach($news->images as $image)

		{!! Form::hidden('previous_image_id', $image->id) !!}
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

	@if ( count($news->documents) )

		<h4>
			{{ Lang::choice('kotoba::files.file', 2) }}
		</h4>
		<hr>

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
				@foreach($news->documents as $document)
				{!! Form::hidden('previous_document_id', $document->id) !!}
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


</div>
</div><!-- ./ col-6 -->
<div class="col-sm-6">
<div class="padding">



	<h3>
		{{ trans('kotoba::general.command.select_an') . '&nbsp;' . Lang::choice('kotoba::cms.image', 1) }}
	</h3>

	<hr>

	<select id="image_select" name="image_id" class="form-control chosen-select" onchange="setImage(this);">
		<option value="" label="">{{ trans('kotoba::general.command.select_an') . '&nbsp;' . Lang::choice('kotoba::cms.image', 1) }}</option>
		@foreach($get_images as $get_image)
			<option value="{{ $get_image->id }}" label="../../../system/files/images/{{ $get_image->id }}/preview/{{ $get_image->image_file_name }}">{{ $get_image->image_file_name }}</option>
		@endforeach
	</select>

	<h4 class="margin-top-xl">
		{{ Lang::choice('kotoba::cms.image', 1) }}
	</h4>

	<hr>

	<div id="imagePreview">
		<img src="" name="image-swap" />
	</div>

<br>


	<h3>
		<i class="fa fa-file-pdf-o fa-fw"></i>
		{{ Lang::choice('kotoba::files.file', 2) }}
	</h3>

	<select multiple="multiple" id="my-select" name="my-select[]">
	@foreach ($allDocuments as $key => $value)
		@if (isset($documents[$key]) )
			<option value='{{ $key }}' selected>{{ $value }}</option>
		@else
			<option value='{{ $key }}'>{{ $value }}</option>
		@endif
	@endforeach
	</select>



{{--
	<div class="form-group">
		{!! Form::label('featured_image', Lang::choice('kotoba::cms.image', 1), ['class' => 'control-label']) !!}
		<div class="imageTarget" rel="{{ $thumbnailPath }}"></div>
		{!! Form::hidden('featured_image', Input::old('featured_image'), ['id' => 'featured_image', 'class' => 'form-control hidden']) !!}
	</div>
	<div class="form-group">
		<a class="btn btn-default btn-rect btn-grad" id="changeFeaturedImage" data-toggle="modal" data-target="#featuredImageModal">{{ trans('kotoba::general.change') }}</a>
		<a class="btn btn-metis-3 btn-rect btn-grad" id="clearFeaturedImage">{{ trans('kotoba::general.clear') }}</a>
	</div>
--}}

</div>
</div><!-- ./ col-6 -->
</div><!-- ./ row -->

	</div>
	</div><!-- ./ images panel -->

	<div role="tabpanel" class="tab-pane" id="settings">
	<div class="tab-content padding">


<div class="row">
<div class="col-sm-6">
<div class="padding">

	<div class="form-group">
		{!! Form::label('parent_id', trans('kotoba::cms.parent'), ['class' => 'control-label']) !!}
		{!!
			Form::select(
				'parent_id',
				$articlelist,
				$news->parent_id,
				array(
					'class' => 'form-control chosen-select',
					'id' => 'parent_id'
				)
			)
		!!}
	</div>

	<div class="form-group">
		{!! Form::label('is_online', Lang::choice('kotoba::account.user', 1), ['class' => 'control-label']) !!}
		{!!
			Form::select(
				'user_id',
				$users,
				$news->user_id,
				array(
					'class' => 'form-control chosen-select'
				)
			)
		!!}
	</div>

	<div class="form-group {{ $errors->first('link') ? 'has-error' : '' }}">
		{!! Form::label('class', trans('kotoba::cms.class'), $errors->first('link'), ['class' => 'control-label']) !!}
		{!! Form::text('class', Input::old('class'), ['id' => 'class', 'class' => 'form-control', 'placeholder' => trans('kotoba::cms.class')]) !!}
	</div>

</div>
</div><!-- ./ col-6 -->
<div class="col-sm-6">
<div class="padding">

	@if (Auth::user()->can('manage_admin'))
		<div class="form-group">
			{!! Form::label('is_online', Lang::choice('kotoba::general.status', 1), ['class' => 'control-label']) !!}
			{!!
				Form::select(
					'news_status_id',
					$news_statuses,
					$news->news_status_id,
					array(
						'class' => 'form-control chosen-select'
					)
				)
			!!}
		</div>
	@else
		<div class="form-group">
			{!! Form::label('is_online', Lang::choice('kotoba::general.status', 1), ['class' => 'control-label']) !!}
			{!! Form::hidden('news_status_id', 1) !!}
			{{ Lang::choice('kotoba::cms.draft', 1) }}
		</div>
	@endif

	<div class="form-group {{ $errors->first('order') ? 'has-error' : '' }}">
		{!! Form::label('order', trans('kotoba::cms.position'), $errors->first('order'), ['class' => 'control-label']) !!}
		{!! Form::text('order', $news->order, ['id' => 'order', 'class' => 'form-control']) !!}
	</div>


<hr>


	<div class="form-group">
		<label for="is_timed" class="col-sm-3 control-label">{{ trans('kotoba::cms.is_timed') }}</label>
		<div class="col-sm-9">
			<div class="checkbox">
				<label>
					<input type="checkbox" id="is_timed" name="is_timed" value="1" {{ $news->present()->timed }}>
				</label>
			</div>
		</div>
	</div>

	<div class="form-group {{ $errors->first('order') ? 'has-error' : '' }}">
		{!! Form::label('order', trans('kotoba::cms.publish_start'), $errors->first('order'), ['class' => 'control-label']) !!}
		<div id="datepicker-container">
			<div class="input-group date">
				<input type="text" id="publish_start" name="publish_start" class="form-control" value="{{ $news->publish_start }}">
				<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
			</div>
		</div>
	</div>

	<div class="form-group {{ $errors->first('order') ? 'has-error' : '' }}">
		{!! Form::label('order', trans('kotoba::cms.publish_end'), $errors->first('order'), ['class' => 'control-label']) !!}
		<div id="datepicker-container">
			<div class="input-group date">
				<input type="text" id="publish_end" name="publish_end" class="form-control" value="{{ $news->publish_end }}">
				<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
			</div>
		</div>
	</div>


<hr>


	<div class="form-group">
		<label for="is_banner" class="col-sm-3 control-label">{{ trans('kotoba::cms.is_banner') }}</label>
		<div class="col-sm-9">
			<div class="checkbox">
				<label>
					<input type="checkbox" id="is_banner" name="is_banner" value="1" {{ $news->present()->banner }}>
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="is_featured" class="col-sm-3 control-label">{{ trans('kotoba::cms.is_featured') }}</label>
		<div class="col-sm-9">
			<div class="checkbox">
				<label>
					<input type="checkbox" id="is_featured" name="is_featured" value="1" {{ $news->present()->featured }}>
				</label>
			</div>
		</div>
	</div>


</div>
</div><!-- ./ col-6 -->
</div><!-- ./ row -->


	</div>
	</div><!-- ./ settings panel -->

</div><!-- ./ tab panes -->

<hr>

<div class="row">
<div class="col-sm-12">
	<input class="btn btn-success btn-block" type="submit" value="{{ trans('kotoba::button.save') }}">
</div>
</div>

<br>

<div class="row">

<div class="col-sm-4">
	<a href="/admin/news" class="btn btn-default btn-block" title="{{ trans('kotoba::button.cancel') }}">
		<i class="fa fa-times fa-fw"></i>
		{{ trans('kotoba::button.cancel') }}
	</a>
</div>

<div class="col-sm-4">
	<input class="btn btn-default btn-block" type="reset" value="{{ trans('kotoba::button.reset') }}">
</div>

<div class="col-sm-4">
<!-- Button trigger modal -->
	<a data-toggle="modal" data-target="#myModal" class="btn btn-default btn-block" title="{{ trans('kotoba::button.delete') }}">
		<i class="fa fa-trash-o fa-fw"></i>
		{{ trans('kotoba::general.command.delete') }}
	</a>
</div>

</div>


{!! Form::close() !!}


</div> <!-- ./ row -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	@include($activeTheme . '::' . '_partials.modal')
</div>


@stop
