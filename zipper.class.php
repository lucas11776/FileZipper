<?php

/**
 * Zipper Class
 * 
 * @description
 * Witter less code with zipper class when zipping files
 * 
 * @docs
 * 
 * require_once 'zipper.class.php';
 * 
 * $file          = 'name of file or list of files (required)';
 * $zip_file_name = 'name of zip file (optional) default: download.zip';
 * $path          = 'location where to store zip file (optional) default: ./';
 * $delete        = 'delete all files after zipping then (optional) default: false';
 *  
 * $zipper = new Zipper($file, $zip_name, $path, $delete);
 * $zipper->zip();
 * 
 * Version  : v1.0
 * Developer: Themba Lucas Ngubeni
 * copyright @themba.website
 * 
*/

class Zipper
{

    /**
     * zip archive
     * 
     * @var object
    */
    private $zip;

    /**
     * file/files to zip
     * 
     * @var mixed
    */
    private $file;

    /**
     * name of zip file
     * 
     * @var string
    */
    private $zip_name;

    /**
     * path where to store zip file
     * 
     * @var string
    */
    private $path;

    /**
     * flag to delete file/files
     * 
     * @var boolean
    */
    private $delete;

    /**
     * files add to zip
     * 
     * @var array
    */
    private $add_files = [];

    /**
     * default name zip name
     * 
     * @var string
    */
    private const DEFAULT_NAME = 'download';
    

    /**
     * zip file/files
     * 
     * @param mixed  file     - file/files name
     * @param string zip_name - name zip file
     * @param string path     - location sore zip file
     * @param string delete   - delete file/files
     * 
     * @return boolean
    */
    public function __construct($file_name, $zip_name = NULL, $path = NULL, $delete = false)
    {
        if(!extension_loaded('zip')){
            die('<h3 style="color:red">please install zip extension to user zipper class.</h3>');
            return false;
        }

        $this->zip      = new ZipArchive();
        $this->file     = $file_name;
        $this->zip_name = $zip_name;
        $this->path     = $path;
        $this->delete   = $delete;
        
        return true;
    }   

    /**
     * setup zip archieve
     * 
     * @return boolean
    */
    public function initialize()
    {
        $zip_file_name = '';

        // check  is path is passed
        if(empty($this->path)){
            $zip_file_name .= './';
        }else{
            is_dir($this->path) ? NULL : mkdir($this->path);
            $zip_file_name  .= $this->path . '/';
        }

        // check if zip name is passed
        if(empty($this->zip_name)){
            $zip_file_name .= $this::DEFAULT_NAME;
        }else{
            $zip_file_name .= $this->zip_name;
        }

        // add zip etexnsion
        $zip_file_name .= '.zip';
        
        return $this->zip->open($zip_file_name, ZipArchive::CREATE);
    }

    /**
     * startup zipper class
     * 
     * @return boolean
    */
    public function zip()
    {
        // initialize zip archive
        if(!$this->initialize()){
            return false;
        }

        if(is_string($this->file)){
            if($this->add_file($this->file)){
                $this->add_files[] = $this->file;
            }
        }
        
        if(is_array($this->file)){
            for($i = 0; $i < count($this->file); $i++){
                if($this->add_file($this->file[$i])){
                    $this->add_files[] = $this->file[$i];
                }
            }
        }

        // close zip archieve
        $this->close();

			 // delete zipped file
        if($this->delete){
            $this->delete();
        }

        return true;
    }

    /**
     * add file to zip
     * 
     * @param string file
     * @return void
    */
    private function add_file($file)
    {
        // check if file exist
        if(!is_file($file)){
            return false;
        }

        // get file name
        $file_name = $this->get_file_name($file);
        
        return $this->zip->addFile($file, $file_name);
    }

    /**
     * get file name
     * 
     * @param string file
     * @return string
    */
    public function get_file_name($file)
    {
        $file = explode('/', $file);
        $file = end($file);
        
        return $file;
    }

    /**
     * close zip archive
     * 
     * @return void
    */
    public function close()
    {
        $this->zip->close();
    }

    /**
     * delete all zipped files
     * 
     * @return void
    */
    public function delete()
    {
        for($i = 0; $i < count($this->add_files); $i++){
            if(file_exists($this->add_files[$i])){
                unlink($this->add_files[$i]);
            }
        }
    }

}

?>