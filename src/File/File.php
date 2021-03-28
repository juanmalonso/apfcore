<?php

namespace Nubesys\File;

use Nubesys\Core\Common;

class File extends Common {

    public function __construct($p_di)
    {
        parent::__construct($p_di);

    }

    //FILE COMMON METHODS
    protected function createFileDirectory($p_base, $p_relativePathPartes){

        $result = $p_base;
        $l = 0;

        foreach($p_relativePathPartes as $directory){

            if($l == 0){

                $result .= $directory;
            }else{

                $result .= "/".$directory;
            }

            if(!file_exists($result)){

                mkdir($result);
            }

            $l++;
        }

        return $result;
    }

    protected function getFileAbsolutePath($p_relativePath){

        $relativePathPartes = explode("/",$p_relativePath);

        return $this->createFileDirectory($this->getDI()->get('config')->files->path, $relativePathPartes);
    }

    public function getFileExtension($p_type){

        $result = false;

        $types  = $this->getDI()->get('config')->files->types;

        if(isset($types[$p_type])){

            $result = $types[$p_type];
        }

        return $result;
    }

    public function getFileTypeMIME($p_extension){
        
        $result = false;

        $types  = $this->getDI()->get('config')->files->types;

        foreach($types as $type=>$ext){

            if($p_extension == $ext){

                $result = $type;
                break;
            }
        }

        return $result;
    }

    public function getFileData($p_fileName, $p_relativePath, $p_fileExtension){
        $result         = null;

        $fullPath       = $this->getDI()->get('config')->files->path . $p_relativePath . "/" . $p_fileName . "." . $p_fileExtension;

        if(file_exists($fullPath)){

            $result     = file_get_contents($fullPath);
        };

        return $result;
    }

    /*
    protected function downloadFile($p_file, $p_type){

        $result = false;

        $contentType = $p_type;
        $contentName = basename($p_file);
        $contentSize = filesize($p_file);

        header("Content-type: " . $contentType);

        header("Content-Length: $contentSize");
        header('Content-Disposition: attachment; filename="'.$contentName.'"');

        $result = $p_file;

        echo file_get_contents($p_file);

        return $result;
    }

    protected function downloadFileData($p_data, $p_ext, $p_name){

        $result = false;

        $contentType = $this->getTypeMIME($p_ext);
        $contentName = $p_name . "." . $p_ext;
        $contentSize = strlen($p_data);

        header("Content-type: " . $contentType);

        header("Content-Length: $contentSize");
        header('Content-Disposition: attachment; filename="'.$contentName.'"');

        $result = $contentName;

        echo $p_data;

        return $result;
    }
    */
}