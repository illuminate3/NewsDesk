<?php

namespace App\Modules\Newsdesk\Providers;

use Illuminate\Support\ServiceProvider;

use Auth;
use Html;
use Lang;

class NewsMacroServiceProvider extends ServiceProvider
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


		function newsTable($node, $lang, $locale_id) {
//dd($node);
//		$locale_id = $this->locale_repo->getLocaleID($lang);

			$title = $node->translate($lang)->title;
			if ($node['depth'] > 0) {
				$title = str_repeat('>', $node['depth']) . ' ' . $title;
			}

			if( empty($node['children']) ) {

				return '<li> empty child <a href="' . url('/news/' . $node['slug']) . '">' . $title . '</a></li>';

			} else {

				$html = '<tr>';

				$html .= '<td><a href="' . url('/news/' . $node['slug']) . '">' . $title . '</a></td>';

				$html .= '<td>' . $node->translate($lang)->summary . '</td>';

				$html .= '<td>' . $node['slug'] . '</td>';

				$html .= '<td>' . $node['order'] . '</td>';

				$html .= '<td>' . $node->present()->news_status($node->news_status_id, $locale_id) . '</td>';

				$html .= '<td>' . $node->present()->isAlert($node->is_alert) . '</td>';

				$html .= '<td>' . $node->present()->isBanner($node->is_banner) . '</td>';

				$html .= '<td>' . $node->present()->isFeatured($node->is_featured) . '</td>';

				$html .= '<td>' . $node->present()->isZone($node->is_zone) . '</td>';

				$html .= '<td>';
					if ( (Auth::user()->id == $node['user_id']) || (Auth::user()->is('super_admin')) ) {
						$html .= '
							<a href="/admin/news/' . $node['id'] . '/edit" class="btn btn-success" title="' . trans("kotoba::button.edit") . '">
								<i class="fa fa-pencil fa-fw"></i>' . trans("kotoba::button.edit") . '
							</a>
							';
					} else {
						$html .= '
							<a href="/news/' . $node['slug'] . '" class="btn btn-primary" title="' . trans("kotoba::button.view") . '">
								<i class="fa fa-search fa-fw"></i>' . trans("kotoba::button.view") . '
							</a>
							';
					}
				 $html .= '</td>';

				$html .= '</tr>';

				foreach($node['children'] as $child) {
					$html .= newsTable($child, $lang, $locale_id);
				}

			}

			return $html;
	}


		function newsTableArchives($node, $lang, $locale_id) {
//dd($node);
//		$locale_id = $this->locale_repo->getLocaleID($lang);

			$title = $node->translate($lang)->title;
			if ($node['depth'] > 0) {
				$title = str_repeat('>', $node['depth']) . ' ' . $title;
			}

			if( empty($node['children']) ) {

				return '<li> empty child <a href="' . url('/news/' . $node['slug']) . '">' . $title . '</a></li>';

			} else {

				$html = '<tr>';

				$html .= '<td><a href="' . url('/news/' . $node['slug']) . '">' . $title . '</a></td>';

				$html .= '<td><a href="' . url('/news/' . $node['slug']) . '">' . $node->translate($lang)->summary . '</a></td>';

//				$html .= '<td>' . $node->translate($lang)->summary . '</td>';

				$html .= '</tr>';

				foreach($node['children'] as $child) {
					$html .= newsTable($child, $lang, $locale_id);
				}

			}

			return $html;
	}


// 	Html::macro('newsNodes', function($nodes, $mode) {
// 		return renderNode($nodes, $mode);
// 	});

	Html::macro('newsNodes', function($nodes, $lang, $locale_id) {
		return newsTable($nodes, $lang, $locale_id);
	});

	Html::macro('newsNodesArchives', function($nodes, $lang, $locale_id) {
		return newsTableArchives($nodes, $lang, $locale_id);
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
