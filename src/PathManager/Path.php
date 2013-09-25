<?php namespace PathManager;

abstract class Path {

	/**
	 * Base path.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Base url.
	 *
	 * @var string
	 */
	protected static $baseUrl;

	/**
	 * Base server path.
	 *
	 * @var string
	 */
	protected static $basePath;

	/** 
	 * Constructor.
	 *
	 * @param  string $path
	 * @return void
	 */
	public function __construct( $path )
	{
		$this->path = $this->prepare($path);
	}

	/**
	 * Initialize path class.
	 *
	 * @param  string $baseUrl
	 * @param  string $basePath
	 * @return void
	 */
	public static function init( $baseUrl, $basePath )
	{
		static::$baseUrl  = rtrim($baseUrl, '\\/');
		static::$basePath = static::prepare($basePath);
	}

	/**
	 * Get base path.
	 * 
	 * @throws PathException
	 * @return string
	 */
	public static function getBasePath()
	{
		if(! static::$basePath) throw new PathException("Path class not initialized.");

		return static::$basePath;
	}

	/**
	 * Get base url.
	 *
	 * @throws PathException
	 * @return string
	 */
	public static function getBaseUrl()
	{
		if(! static::$baseUrl) throw new PathException("Path class not initialized.");

		return static::$baseUrl;
	}

    /**
     * @return Path
     */
    public static function makeFromBase()
    {
        return static::make(static::getBasePath());
    }

	/**
	 * Make from given path.
	 *
	 * @throws PathException
	 * @param  string $path
	 * @return Path
	 */
	public static function make( $path )
	{
		// Check if path is local url
		if(static::isLocalUrl($path)) return static::make(static::convertToPath($path));

		// Get path info to determine if it's a file or directory.
		$pathinfo = pathinfo($path);

		// If there's no extension or the directory does exists then return new Directory object
		if(! isset($pathinfo['extension']) || is_dir($path)) return new Directory( $path );
		
		// If there's an extension or the file does exists the return new File object
		if( isset($pathinfo['extension']) || is_file($path)) return new File( $path );

		throw new PathException('Given path is invalid.');
	}

	/**
	 * Determine if the url is local.
	 *
	 * @param  string $url
	 * @return boolean
	 */
	public static function isLocalUrl( $url )
	{
		return strpos($url, static::getBaseUrl()) > -1;
	}

	/**
	 * Convert to path from url.
	 *
	 * @param  string $url
	 * @return string
	 */
	public static function convertToPath( $url )
	{
		return static::prepare(str_replace(static::getBaseUrl(), static::getBasePath(), $url));
	}

	/**
	 * Convert to url from path.
	 *
	 * @param  string $path
	 * @return string
	 */
	public static function convertToUrl( $path )
	{
		return str_replace('\\', '/', str_replace(static::getBasePath(), static::getBaseUrl(), static::prepare($path)));
	}

	/**
	 * Get server directory separator.
	 *
	 * @return string
	 */
	public static function ds()
	{
		return DIRECTORY_SEPARATOR;
	}

    /**
     * Prepare path.
     *
     * @param $path
     * @return string
     */
	protected static function prepare( $path )
	{
		$path = str_replace('\\', static::ds(), $path);
		$path = str_replace('/', static::ds(), $path);

		return rtrim($path, '\\/');
	}

	/**
	 * Convert current path to url.
	 *
	 * @return string
	 */
	public function toUrl()
	{
		return $this->convertToUrl($this);
	}

	/**
	 * Get parent directory for this path
	 *
	 * @return Directory
	 */
	public function getParentDirectory()
	{
		return new Directory($this->getPathInfo('dirname'));
	}

	/**
	 * Get path info with key.
	 *
	 * @param  string $key
	 * @return string
	 */ 
	public function getPathInfo( $key )
	{
		$pathinfo = pathinfo($this);

		return isset($pathinfo[$key]) ? $pathinfo[$key] : '';
	}

	/**
	 * String representation for this class.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->path;
	}

    /**
     * Copy this file or directory to the given directory.
     *
     * @param \PathManager\Path $path
     * @return void
     */
	public abstract function copy( Path $path );

    /**
     * Move this file or directory to the given directory.
     *
     * @param Path $path
     * @return void
     */
	public abstract function move( Path $path );

	/**
	 * Delete this file or directory.
	 *
	 * @return void
	 */
	public abstract function delete();

	/**
	 * Make this path unique by changing its name if it exists.
	 *
	 * @return void
	 */
	public abstract function makeUnique();

	/**
	 * Make sure this path exists.
	 * This method will create all directories along the way to 
	 * make sure this directory does exist.
	 * This method might take some time.. Don't use a lot!
	 *
	 * @throws PathException
	 * @param  int $permissions
	 * @return void
	 */
	public abstract function makeSureItExists( $permissions = 0755 );
}