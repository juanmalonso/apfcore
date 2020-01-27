<?php

namespace Nubesys\Core\Response;

use Nubesys\Core\Common;
use Nubesys\Core\Response\ResponseAdapters\Data;
use Nubesys\Core\Response\ResponseAdapters\Web;
use Nubesys\Core\Register;

class ResponseManager extends Common {

    private     $adapterType;
    private     $adapter;

    public function __construct($p_di, $p_adapterType = 'web')
    {
        parent::__construct($p_di);

        $this->adapterType      = $p_adapterType;

        switch ($p_adapterType) {

            case 'web':
                $this->adapter = new Web($p_di);
                break;

            case 'data':
                $this->adapter = new Data($p_di);
                break;
        }

        $this->headers = new Register();
    }

    public function getType(){

        return $this->adapterType;
    }

    public function setHeader($p_key, $p_value){

        $this->$adapter->setHeader($p_key, $p_value);
    }

    public function getHeaders(){

        return $this->adapter->getHeaders();
    }

    public function getBody()
    {

        return $this->adapter->getBody();
    }

    public function setHtml($p_html){

        $this->adapter->setHtml($p_html);
    }

    public function setRedirect($p_url){

        $this->adapter->setRedirect($p_url);
    }

    public function getRedirect(){

        return $this->adapter->getRedirect();
    }

    public function hasRedirect(){

        return $this->adapter->hasRedirect();
    }

    public function setCookie($p_key, $p_value){
        
        $this->adapter->setCookie($p_key, $p_value);
    }

    public function getCookies(){

        return $this->adapter->getCookies();
    }

    public function setStatus($p_status){

        $this->adapter->setStatus($p_status);
    }

    public function setInfo($p_info){

        $this->adapter->setInfo($p_info);
    }

    public function setData($p_data){

        $this->adapter->setData($p_data);
    }

    public function setDebug($p_debug){

        $this->adapter->setDebug($p_debug);
    }

    public function setError($p_message){

        $this->adapter->setError($p_message);
    }

    public function setSuccess($p_data){

        $this->adapter->setSuccess($p_data);
    }
}