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
}