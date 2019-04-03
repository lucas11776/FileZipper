# FileZipper
Php class extension to make zipping file in php easy

@docs

require_once 'zipper.class.php';

$file          = 'name of file or list of files (required)';
$zip_file_name = 'name of zip file (optional) default: download.zip';
$path          = 'location where to store zip file (optional) default: ./';
$delete        = 'delete all files after zipping then (optional) default: false';

$zipper = new Zipper($file, $zip_name, $path, $delete);
$zipper->zip();
