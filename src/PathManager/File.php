<?php namespace PathManager;

class File extends Path {

	/**
	 * Copy this file to the given file.
	 *
	 * @throws PathException
	 * @param  File $file
	 * @return void
	 */
	public function copy( Path $file )
	{
		if(! is_file($this)) throw new PathException("File path is invalid.");

		copy($this, $file);

		return $this;
	}

	/**
	 * Move this file to the given file.
	 *
	 * @throws PathException
	 * @param  File $file
	 * @return void
	 */
	public function move( Path $file )
	{
		if(! is_file($this)) throw new PathException("File path is invalid.");

		rename($this, $file);

		return $this;
	}

	/**
	 * Delete this file.
	 *
	 * @throws PathException
	 * @return void
	 */
	public function delete()
	{
		if(! is_file($this)) throw new PathException("File path is invalid.");
		
		unlink($this);
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
		$this->getParentDirectory()->makeSureItExists();

		return $this;
	}

	/**
	 * Make this path unique by changing its name if it exists.
	 *
	 * @return void
	 */
	public function makeUnique()
	{
		if(file_exists($this->path))
		{
            // First get path info
            $info = pathinfo($this->path);

            // Use the directory and file name to form the full path without the extension
            $name = $info['dirname'] . '\\' . $info['filename'];

            // Get the extension
            $ext = $info['extension'];

            // If file exsists then prepend a random number.
            while(file_exists($name . '.' . $ext))
            {
                $name .= rand(0, 10);
            }

            $this->path = $name . '.' . $ext;
		}
	}
}