<?php namespace PathManager;

use RecursiveDirectoryIterator,RecursiveIteratorIterator;

class Directory extends Path {

	/**
	 * Copy this directory to the given directory.
	 *
	 * @throws PathException
	 * @param  Directory $directory
	 * @return void
	 */
	public function copy( Path $directory )
	{
		if(! is_dir($this)) throw new PathException("Directory path is invalid.");

		$this->recurseCopy( $this->path, $directory->path );

		return $this;
	}

	/**
	 * Copy folder and all it's contents.
	 *
	 * @param  string $src
	 * @param  string $dst
	 * @return void
	 */
	private function recurseCopy($src,$dst, $actualDst = '')
	{
		$actualDst = $actualDst ?: $dst;

	    $dir = opendir($src);
	    
	    @mkdir($dst);

	    while(false !== ( $file = readdir($dir)) )
	    {
	        if (( $file != '.' ) && ( $file != '..' ))
	        { 
	        	if($src . $this->ds() . $file == $actualDst) continue;
	            
	            if (is_dir($src . $this->ds() . $file))
	            { 
	                $this->recurseCopy($src . $this->ds() . $file,$dst . $this->ds() . $file, $dst); 
	            } 
	            else
	            { 
	                copy($src . $this->ds() . $file, $dst . $this->ds() . $file); 
	            } 
	        }
	    } 
	    closedir($dir); 
	} 

	/**
	 * Move this directory to the given directory.
	 *
	 * @throws PathException
	 * @param  Directory $directory
	 * @return void
	 */
	public function move( Path $directory )
	{
		if(! is_dir($this) || ! is_dir($directory)) throw new PathException("Directory path is invalid.");

		rename($this, $directory);

		return $this;
	}

	/**
	 * Delete this directory.
	 *
	 * @throws PathException
	 * @return void
	 */
	public function delete()
	{
		if(! is_dir($this)) throw new PathException("Directory path is invalid.");

		$dir = $this->path;
		
		$it = new RecursiveDirectoryIterator($dir);
		
		$files = new RecursiveIteratorIterator($it,
		             RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file) {
		
		    if ($file->getFilename() === '.' || $file->getFilename() === '..') {
		        continue;
		    }
		    if ($file->isDir()){
		        rmdir($file->getRealPath());
		    } else {
		        unlink($file->getRealPath());
		    }
		}
		
		rmdir($dir);
	}

	/**
	 * Make sure this path exists.
	 * This method will create all directories along the way to 
	 * make sure this directory does exist.
	 * This method might take some time.. Don't use alot!
	 *
	 * @param  int $permissions
	 * @return void
	 */
	public function makeSureItExists( $permissions = 0755 )
	{
		$directories = explode($this->ds(), $this->path);

		$path = '';
		
		foreach ($directories as $directory)
		{
			$path .= $directory . $this->ds();

			if(! file_exists($path))

				mkdir( $path, $permissions );
		}

		return $this;
	}

    /**
     * Make this path unique by changing its name if it exists.
     *
     * @return void
     */
    public function makeUnique()
    {
        while(file_exists($this->path))
        {
            $this->path = rtrim($this->path, '\\/') . rand(0,10);
        }
    }
}