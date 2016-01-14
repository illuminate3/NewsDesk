<?php
namespace App\Modules\Newsdesk\Providers;

use Illuminate\Support\ServiceProvider;

use App\Modules\Newsdesk\Http\Models\News;

use DB;
use Cache;
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
		$total_articles = $this->getAllArticles();
		View::share('total_articles', $total_articles);

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


}
