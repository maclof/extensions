<?php namespace Maclof\Extensions;

use Illuminate\Container\Container;

interface ExtensionInterface {

	/**
	 * Set the container.
	 * 
	 * @param Container $container
	 */
	public function setContainer(Container $container);

	/**
	 * Register the extension.
	 *
	 * @return void
	 */
	public function register();

	/**
	 * Boot the extension.
	 *
	 * @return void
	 */
	public function boot();

}