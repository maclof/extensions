<?php namespace Maclof\Extensions;

use Illuminate\Support\ServiceProvider;

class ExtensionsServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerExtensionFinder();
		$this->registerExtensionBag();
	}

	/**
	 * Boot the service provider.
	 * 
	 * @return void
	 */
	public function boot()
	{
		// Ensure the package resources can be located correctly.
		$this->package('maclof/extensions', 'maclof/extensions', __DIR__ . '/../..');

		// Get the paths.
		$paths = $this->app['config']->get('maclof/extensions::paths');

		// Find and initialise the extensions.
		$this->app['extensions.bag']->findAndInitialiseExtensions($paths);
	}

	/**
	 * Register the extension finder.
	 * 
	 * @return void
	 */
	protected function registerExtensionFinder()
	{
		$this->app['extensions.finder'] = $this->app->share(function ($app)
		{
			return new ExtensionFinder($app['files']);
		});
	}

	/**
	 * Register the extension bag.
	 * 
	 * @return void
	 */
	protected function registerExtensionBag()
	{
		$this->app['extensions.bag'] = $this->app->share(function ($app)
		{
			return new ExtensionBag($app['extensions.finder'], $app);
		});
	}

}
