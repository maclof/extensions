<?php namespace Maclof\Extensions;

use Illuminate\Filesystem\Filesystem;

class ExtensionFinder {

	/**
	 * The filesystem.
	 * 
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * Share the dependencies.
	 * 
	 * @param Filesystem $filesystem
	 * @param array      $paths
	 */
	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;
	}

	/**
	 * Find the extensions.
	 *
	 * @param  array $paths
	 * @return array
	 */
	public function findExtensions(array $paths)
	{
		$files = array();

		// Iterate over the paths.
		foreach ($paths as $path)
		{
			// Find the extension files.
			$found = $this->filesystem->glob($path . '/*/*/extension.php');

			// Check if we found some files.
			if (is_array($found))
			{
				// Merge the files.
				$files = array_merge($files, $found);
			}
		}

		$extensions = array();

		// Iterate over the files.
		foreach ($files as $file)
		{
			// Get the extension attributes.
			$attributes = $this->filesystem->getRequire($file);

			// Check if the attributes are valid.
			if (is_array($attributes) && isset($attributes['slug']))
			{
				// Initialise the extension class.
				$extensions[$attributes['slug']] = new Extension(dirname($file), $attributes);
			}
		}

		return $extensions;
	}

}