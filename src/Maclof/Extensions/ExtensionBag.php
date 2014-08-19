<?php namespace Maclof\Extensions;

use Illuminate\Container\Container,
	Illuminate\Support\Collection;

class ExtensionBag {

	/**
	 * The extension finder.
	 * 
	 * @var ExtensionFinder
	 */
	protected $finder;

	/**
	 * The container.
	 * 
	 * @var Container
	 */
	protected $container;

	/**
	 * The extension collection.
	 * 
	 * @var Collection
	 */
	protected $collection;

	/**
	 * Share the dependencies.
	 * 
	 * @param ExtensionFinder $finder
	 * @param Container       $container
	 */
	public function __construct(ExtensionFinder $finder, Container $container)
	{
		$this->finder = $finder;
		$this->container = $container;
	}

	/**
	 * Find and initialise the extensions.
	 *
	 * @param  array $paths
	 * @return array
	 */
	public function findAndInitialiseExtensions(array $paths)
	{
		// Find the extensions.
		$extensions = $this->finder->findExtensions($paths);

		// Create the collection of extensions.
		$this->collection = Collection::make($extensions);

		return $extensions;
	}

	/**
	 * Enable extensions by their slugs.
	 * 
	 * @param  array $slugs
	 * @return void
	 */
	public function enableExtensions(array $slugs)
	{
		// Register the extensions.
		foreach ($this->collection as $slug => $extension)
		{
			// Check if the slug isn't in the slugs array.
			if (!in_array($slug, $slugs))
			{
				continue;
			}

			// Set the container.
			$extension->setContainer($this->container);

			// Register the extension.
			$extension->register();
		}

		// Boot the extensions.
		foreach ($this->collection as $slug => $extension)
		{
			// Check if the slug isn't in the slugs array.
			if (!in_array($slug, $slugs))
			{
				continue;
			}

			// Boot the extension.
			$extension->boot();
		}
	}

}