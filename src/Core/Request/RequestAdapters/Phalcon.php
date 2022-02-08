<?php

namespace Nubesys\Core\Request\RequestAdapters;

use Nubesys\Core\Common;
use UAParser\Parser;

class Phalcon extends Common {

    private $requestObject;
    private $userAgentData;

    public function __construct($p_di, $p_requestObject)
    {
        parent::__construct($p_di);

        $userAgentData          = null;
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

    public function getClientAddress(){

        return $this->requestObject->getClientAddress(true);
    }

    public function getReferer(){

        return $this->requestObject->getHTTPReferer();
    }

    public function getURI(){

        return $this->requestObject->getURI();
    }

    public function getUserAgent(){

        return $this->getUserAgentData("userAgent");
    }

    public function getUserBrowser(){

        return $this->getUserAgentData("browser");
    }

    public function getUserOs(){

        return $this->getUserAgentData("os");
    }

    public function getUserDevice(){

        return $this->getUserAgentData("device");
    }

    private function getUserAgentData($p_key){

        $result                     = null;

        if(is_null($this->userAgentData)){

            $this->userAgentData    = $this->parseUserAgent();
        }

        if(isset($this->userAgentData[$p_key])){

            $result                 = $this->userAgentData[$p_key];
        }

        return $result;
    }

    private function parseUserAgent(){

        $result                     = array();

        $parser                     = Parser::create();

        $userAgentData              = $parser->parse($this->requestObject->getUserAgent());

        if(property_exists($userAgentData, "originalUserAgent")){

            $result['userAgent']    = $userAgentData->originalUserAgent;
        }

        if(property_exists($userAgentData, "ua")){

            if(property_exists($userAgentData->ua, "family")){

                $result['browser']  = $userAgentData->ua->family;
            }
        }

        if(property_exists($userAgentData, "os")){

            if(property_exists($userAgentData->os, "family")){

                $result['os']       = $userAgentData->os->family;
            }
        }

        if(property_exists($userAgentData, "device")){

            if(property_exists($userAgentData->device, "family")){

                $result['device']   = $userAgentData->device->family;
            }
        }

        return $result;
    }
}