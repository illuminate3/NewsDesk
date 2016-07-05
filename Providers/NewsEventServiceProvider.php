<?php

namespace App\Modules\Newsdesk\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Providers\EventServiceProvider;

use App\Modules\Newsdesk\Events\NewsWasCreated;
use App\Modules\Newsdesk\Handlers\Events\CreateNews;
use App\Modules\Newsdesk\Events\NewsWasUpdated;
use App\Modules\Newsdesk\Handlers\Events\UpdateNews;

use App\Modules\Newsdesk\Http\Models\News;

use App;
use Event;


class NewsEventServiceProvider extends EventServiceProvider {


	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [

		NewsWasCreated::class => [
			CreateNews::class,
		],

		NewsWasUpdated::class => [
			UpdateNews::class,
		],

	];


	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

// 		News::created(function ($content) {
// 			\Event::fire(new NewsWasCreated($content));
// 		});
//
// 		News::saved(function ($content) {
// 			\Event::fire(new NewsWasUpdated($content));
// 		});

	}


	public function register()
	{

		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		$loader->alias('NewsWasCreated', 'App\Modules\Newsdesk\Events\NewsWasCreated');

		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		$loader->alias('NewsWasUpdated', 'App\Modules\Newsdesk\Events\NewsWasUpdated');

	}


}
