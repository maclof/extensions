<?php namespace Maclof\Extensions;

use Closure,
	Illuminate\Container\Container;

class Extension implements ExtensionInterface {

	/**
	 * The extensions attributes.
	 * 
	 * @var array
	 */
	protected $attributes;

	/**
	 * Share the dependencies.
	 * 
	 * @param array $attributes
	 */
	public function __construct(array $attributes)
	{
		$this->attributes = $attributes;
	}

	/**
	 * Register the extension.
	 *
	 * @param  Container $container
	 * @return void
	 */
	public function register(Container $container)
	{
		if (isset($this->register) && $this->register instanceof Closure)
		{
			call_user_func_array($this->register, array($this, $container));
		}
	}

	/**
	 * Boot the extension.
	 *
	 * @param  Container $container
	 * @return void
	 */
	public function boot(Container $container)
	{
		if (isset($this->boot) && $this->boot instanceof Closure)
		{
			call_user_func_array($this->boot, array($this, $container));
		}
	}

	/**
	 * Get an attribute by key.
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
	}

	/**
	 * Determines whether an attribute is set.
	 * 
	 * @param  string $key
	 * @return boolean
	 */
	public function __isset($key)
	{
		return isset($this->attributes[$key]);
	}

}