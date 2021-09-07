<?php

namespace Nubesys\Core\Request\RequestAdapters;

use Nubesys\Core\Common;

class Phalcon extends Common {

    private $requestObject;

    public function __construct($p_di, $p_requestObject)
    {
        parent::__construct($p_di);

        $this->requestObject    = $p_requestObject;
    }

    public function getMethod(){

        return $this->requestObject->getMethod();
    }

    public function getHeaders(){
        
        return getallheaders();
    }

    public function getGET(){

        return $this->requestObject->getQuery();
    }

    public function getPOST(){

        return $this->requestObject->getPost();
    }

    public function getJSON(){
        $result = NULL;
        
        if($this->requestObject->getRawBody() !== ""){

            if(\Nubesys\Core\Utils\Struct::isValidJson($this->requestObject->getRawBody())){

                $result = json_decode($this->requestObject->getRawBody(), true);
            }
        }
        return $result;
    }

    public function getFILES(){
        
        $files      = array();

        foreach($this->requestObject->getUploadedFiles() as $file){
            
            $fileTmp                        = array();

            $fileTmp['key']                 = $file->getKey();
            $fileTmp['name']                = $file->getName();
            $fileTmp['extension']           = $file->getExtension();
            $fileTmp['type']                = $file->getType();
            $fileTmp['realType']            = $file->getRealType();
            $fileTmp['size']                = $file->getSize();
            $fileTmp['tmpPath']             = $file->getTempName();
            $fileTmp['error']               = $file->getError();

            $files[$fileTmp['key']]         = $fileTmp;
        }

        return $files;
    }

    public function hasCookie($p_key){

        return isset($_COOKIE[$p_key]);
    }

    public function getCookie($p_key){

        return $_COOKIE[$p_key];
    }
}