<?php

namespace App\Modules\Newsdesk\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use App;
use Config;
use Lang;
use Menu;
use Theme;
use View;

class NewsdeskServiceProvider extends ServiceProvider
{

	/**
	 * Register the Newsdesk module service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// This service provider is a convenient place to register your modules
		// services in the IoC container. If you wish, you may make additional
		// methods or service providers to keep the code more focused and granular.

		$this->registerNamespaces();
		$this->registerProviders();

	}


	/**
	 * Register the Newsdesk module resource namespaces.
	 *
	 * @return void
	 */
	protected function registerNamespaces()
	{
		View::addNamespace('newsdesk', __DIR__.'/../Resources/Views/');
	}


	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__.'/../Config/newsdesk.php' => config_path('newsdesk.php'),
			__DIR__ . '/../Resources/Assets/Images' => base_path('public/assets/images/'),
			__DIR__ . '/../Resources/Views/' => public_path('themes/') . Theme::getActive() . '/views/modules/newsdesk/',
			__DIR__ . '/../Resources/Assets/Views/Widgets' => public_path('themes/') . Theme::getActive() . '/views/widgets/',
		]);

		$this->publishes([
			__DIR__.'/../Config/newsdesk.php' => config_path('newsdesk.php'),
		], 'configs');

		$this->publishes([
			__DIR__ . '/../Resources/Assets/Images' => base_path('public/assets/images/'),
		], 'images');

		$this->publishes([
			__DIR__ . '/../Resources/Views/' => public_path('themes/') . Theme::getActive() . '/views/modules/newsdesk/',
			__DIR__ . '/../Resources/Assets/Views/Widgets' => public_path('themes/') . Theme::getActive() . '/views/widgets/',
		], 'views');

		$app = $this->app;

		$app->register('App\Modules\Newsdesk\Providers\ViewComposerServiceProvider');
		$app->register('App\Modules\Newsdesk\Providers\NewsEventServiceProvider');
		$app->register('Baum\Providers\BaumServiceProvider');
		$app->register('App\Modules\Newsdesk\Providers\NewsMacroServiceProvider');
		$app->register('App\Modules\Newsdesk\Providers\WidgetServiceProvider');

	}


	/**
	* add Prvoiders
	*
	* @return void
	*/
	private function registerProviders()
	{
		$app = $this->app;

		$app->register('App\Modules\Newsdesk\Providers\RouteServiceProvider');
	}

}
