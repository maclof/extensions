<?php namespace Maclof\Extensions;

use Closure,
	Illuminate\Container\Container,
	Composer\Autoload\ClassLoader;

class Extension implements ExtensionInterface {

	/**
	 * The path to the extension.
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * The extensions attributes.
	 * 
	 * @var array
	 */
	protected $attributes;

	/**
	 * The namespace.
	 * 
	 * @var string
	 */
	protected $namespace;

	/**
	 * The container.
	 * 
	 * @var Container
	 */
	protected $container;

	/**
	 * Share the dependencies.
	 *
	 * @param string $path
	 * @param array  $attributes
	 */
	public function __construct($path, array $attributes)
	{
		$this->path = $path;
		$this->attributes = $attributes;

		// Set or construct the namespace.
		$this->namespace = isset($attributes['namespace']) ? $attributes['namespace'] : $this->constructNamespace($attributes['slug']);
	}

	/**
	 * Construct a namespace from a slug.
	 * 
	 * @param  string $slug
	 * @return string 
	 */
	protected function constructNamespace($slug)
	{
		// Explode the slug.
		$parts = explode('/', $slug);

		// Map the parts and transform into studly case.
		$parts = array_map(function ($part)
		{
			return studly_case($part);
		}, $parts);

		return implode('\\', $parts);
	}

	/**
	 * Set the container.
	 * 
	 * @param Container $container
	 */
	public function setContainer(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Register the extension.
	 *
	 * @return void
	 */
	public function register()
	{
		// Register the autoloading.
		$this->registerAutoloading();

		// Check if a register method is available.
		if (isset($this->register) && $this->register instanceof Closure)
		{
			// Call the register method.
			call_user_func_array($this->register, array($this, $this->container));
		}
	}

	/**
	 * Register the class autoloading.
	 * 
	 * @return void
	 */
	protected function registerAutoloading()
	{
		// Instantiate the class loader.
		$loader = new ClassLoader;

		// Add the extension namespace and path.
		$loader->add($this->namespace, $this->path . '/src');

		// Register the loader.
		$loader->register();

		// Use the path for includes.
		$loader->setUseIncludePath(true);
	}

	/**
	 * Boot the extension.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Register the extension as a package.
		$this->registerPackage();

		// Check if a boot method is available.
		if (isset($this->boot) && $this->boot instanceof Closure)
		{
			// Call the boot method.
			call_user_func_array($this->boot, array($this, $this->container));
		}

		// Register the routes.
		$this->registerRoutes();
	}

	/**
	 * Register the extension as a package.
	 * 
	 * @return void
	 */
	protected function registerPackage()
	{
		// Construct the config path.
		$configPath = $this->path . '/config';

		// Check if the config path is a directory.
		if ($this->container['files']->isDirectory($configPath))
		{
			// Register the config(s).
			$this->container['config']->package($this->slug, $configPath, $this->slug);
		}

		// Construct the view path.
		$viewPath = $this->path . '/views';

		// Check fi the view path is a directory.
		if ($this->container['files']->isDirectory($viewPath))
		{
			// Add the namespace to the view finder.
			$this->container['view']->addNamespace($this->slug, $viewPath);
		}

		// Construct the lang path.
		$langPath = $this->path . '/lang';

		// Check if the lang path is a directory.
		if ($this->container['files']->isDirectory($langPath))
		{
			// Add the namespace to the translater.
			$this->container['translator']->addNamespace($this->slug, $langPath);
		}
	}

	/**
	 * Register the routes.
	 * 
	 * @return void
	 */
	protected function registerRoutes()
	{
		// Check if a routes method is available.
		if (isset($this->routes) && $this->routes instanceof Closure)
		{
			// Call the routes method.
			call_user_func_array($this->routes, array($this, $this->container));
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