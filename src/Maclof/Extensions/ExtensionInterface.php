<?php namespace Maclof\Extensions;

use Illuminate\Container\Container;

interface ExtensionInterface {

	/**
	 * Register the extension.
	 *
	 * @param  Container $container
	 * @return void
	 */
	public function register(Container $container);

	/**
	 * Boot the extension.
	 *
	 * @param  Container $container
	 * @return void
	 */
	public function boot(Container $container);

}