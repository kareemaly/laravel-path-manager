# PathManager
These set of classes will help you modify files and directories easily and enable you to use urls instead 
of server paths which is very helpful in some cases.

## Usage example

```php
use PathManager\Path;
use PathManager\PathException;

// Initialize Path class only one time
// The base server path should be the path for which the base url points to.
// e.g. example.com domain points to the public folder in the root directory.
Path::init('http://www.example.com', $_SERVER['DOCUMENT_ROOT'] . '/public');



// Example 1.
// Create a path from the url
$file = Path::make('http://www.example.com/albums/users/1/kareem.jpg');

// Move it to another url
$file->move(Path::make('http://www.example.com/albums/deleted/users/1/kareem.jpg'));
//-------------------------------------------------------------------------------------//



// Example 2.
// Create directory from server path.
$directory = Path::make(__DIR__ . '/folder1/folder2/folder3/folder4');

// Make sure this path exists by making sure all folders are created.
$direcotry->makeSureItExists();
//-------------------------------------------------------------------------------------//




// Example 3.
// Move uploaded file to a given url after making sure this path does exists
move_uploaded_file(
	$image, 
	Path::make('http://www.example.com/albums/users/1/profile.jpg')->makeSureItExists()
);
//-------------------------------------------------------------------------------------//



// Example 4.
// Copy all directory contents to another directory
$directory = Path::make('http://www.example.com/albums');

try{
	$direcotry->copy(Path::make('http://www.example.com/new-destination/albums')->makeSureItExists());
}catch(PathException $e){
	echo $e->getMessage();
}
//-------------------------------------------------------------------------------------//
```