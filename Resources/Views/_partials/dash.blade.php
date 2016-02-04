<h2>
	<i class="fa fa-newspaper-o fa-lg"></i>
	{{ Lang::choice('kotoba::cms.article', 2) }}
	<hr>
</h2>

<dl class="dl-horizontal">
	<dt>
		{{ trans('kotoba::general.all') }}
	</dt>
	<dd>
		<a href="{{ URL::to('/admin/news') }}">{{ $total_articles }}</a>
	</dd>
	<dt>
		{{ Lang::choice('kotoba::cms.draft', 2) }}
	</dt>
	<dd>
		<a href="{{ URL::to('/admin/news') }}">{{ $news_drafts }}</a>
	</dd>
</dl>
