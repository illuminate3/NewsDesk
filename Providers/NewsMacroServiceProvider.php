<?php

namespace App\Modules\NewsDesk\Providers;

use Illuminate\Support\ServiceProvider;

use Html;
use Lang;


class ContentMacroServiceProvider extends ServiceProvider
{


	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{


		function renderNode($node, $mode) {
//dd($mode);

			if($mode == 'plain') {
				$classLi = '';
				$classUl = '';
				$classSpan = '';
			}
			else{
				$classLi = 'list-group-item';
				$classUl = 'list-group';
				$classSpan = 'glyphicon text-primary';
			}

			if( empty($node['children']) ) {
				//glyphicon for closed entries
				if($mode != 'plain')
					$classSpan .= ' glyphicon-chevron-right';
				return '<li class="' . $classLi . '"> <a href="' . url('news/' . $node['id']) . '">' . '<span class="' . $classSpan . '"></span>' . $node['slug'] . '</a></li>';
			} else {
				//$html = "Anzahl Kinder von:". $node['name'] . ' -> ' . count($node['children']);
				//glyphicon for opened entries
				if($mode != 'plain')
					$classSpan .= ' glyphicon-chevron-down';
				$html = '<li class="' . $classLi . '"><a href="' . url('news/' . $node['id']) . '">' . '<span class="' . $classSpan . '"></span>' . $node['slug'] . '</a>';
				$html .= '<ul class="' . $classUl . '">';

				foreach($node['children'] as $child)
					$html .= renderNode($child, $mode);

				$html .= '</ul>';
				$html .= '</li>';
			}

			return $html;
	}


		function contentTable($node, $lang) {
//dd($node);

			$title = $node->translate($lang)->title;
			if ($node['depth'] > 0) {
				$title = str_repeat('>', $node['depth']) . ' ' . $title;
			}

			if( empty($node['children']) ) {

				return '<li> empty child <a href="' . url('news/' . $node['slug']) . '">' . $title . '</a></li>';

			} else {

				$html = '<tr>';

				$html .= '<td><a href="' . url($node['slug']) . '">' . $title . '</a></td>';

				$html .= '<td>' . $node->translate($lang)->summary . '</td>';

				$html .= '<td>' . $node['slug'] . '</td>';

				$html .= '<td>' . $node['order'] . '</td>';

				$html .= '<td>' . $node->present()->news_status($node->news_status_id) . '</td>';

				$html .= '<td>';
				$html .= '
					<a href="/admin/news/' . $node['id'] . '/edit" class="btn btn-success" title="' . trans("kotoba::button.edit") . '">
						<i class="fa fa-pencil fa-fw"></i>' . trans("kotoba::button.edit") . '
					</a>
						';
				 $html .= '</td>';

				$html .= '</tr>';

				foreach($node['children'] as $child) {
					$html .= contentTable($child, $lang);
				}

			}

			return $html;
	}


	Html::macro('newsNodes', function($nodes, $mode) {
		return renderNode($nodes, $mode);
	});

	Html::macro('contentNodes', function($nodes, $lang) {
		return contentTable($nodes, $lang);
	});


}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}


}
