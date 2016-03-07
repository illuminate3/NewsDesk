<?php

namespace App\Modules\Newsdesk\Providers;

use Illuminate\Support\ServiceProvider;

use App\Modules\Newsdesk\Http\Models\News;

use DB;
use Cache;
use Config;
use Schema;
use View;


class ViewComposerServiceProvider extends ServiceProvider
{


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
//		$total_articles = $this->getAllArticles();
		$news_drafts = $this->getNewsDrafts();
//		$total_sites = $this->getAllSites();

//		View::share('total_articles', $total_articles);
		View::share('news_drafts', $news_drafts);
//		View::share('total_sites', $total_sites);

	}


	public function register()
	{
		//
	}


	public function getAllArticles()
	{

		if (Schema::hasTable('news')) {
			$count = count(News::all());
			if ( $count == null ) {
				$count = 0;
			}
			return $count;
		}

	}


	public function getNewsDrafts()
	{

		if (Schema::hasTable('news')) {
			$drafts = DB::table('news')
				->where('news_status_id', '=', Config::get('news.default_publish_status'))
				->get();
			$count = count($drafts);
			if ( $count == null ) {
				$count = 0;
			}
			return $count;
		}

	}


	public function getAllSites()
	{
//		Cache::forget('all_sites');
		$allSites = Cache::get('all_sites');
//dd($sites);

		if ($allSites == null) {
			$allSites = Cache::rememberForever('all_sites', function() {
				return DB::table('sites')
					->where('status_id', '=', 1)
					->get();
			});
		}
//dd($allSites);

	return $allSites;

	}


}
