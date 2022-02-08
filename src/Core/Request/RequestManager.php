<?php

namespace Nubesys\Core\Request;

use Nubesys\Core\Common;
use Nubesys\Core\Request\RequestAdapters\Phalcon;
use Nubesys\Core\Request\RequestAdapters\Swoole;

class RequestManager extends Common {

    private $adapter;

    public function __construct($p_di, $p_requestObject, $p_adapterType = 'phalcon')
    {
        parent::__construct($p_di);

        switch ($p_adapterType) {

            case 'phalcon':
                $this->adapter = new Phalcon($p_di, $p_requestObject);
                break;

            case 'swoole':
                $this->adapter = new Swoole($p_di, $p_requestObject);
                break;
        }
    }

    public function getMethod(){

        return $this->adapter->getMethod();
    }

    public function getHeaders(){

        return $this->adapter->getHeaders();
    }

    public function getGET(){

        return $this->adapter->getGET();
    }

    public function getPOST(){

        return $this->adapter->getPOST();
    }

    public function getJSON(){

        return $this->adapter->getJSON();
    }

    public function getFILES(){

        return $this->adapter->getFILES();
    }

    public function hasCookie($p_key){

        return $this->adapter->hasCookie($p_key);
    }

    public function getCookie($p_key){

        return $this->adapter->getCookie($p_key);
    }

    public function getClientAddress(){

        return $this->adapter->getClientAddress();
    }

    public function getReferer(){

        return $this->adapter->getReferer();
    }

    public function getURI(){

        return $this->adapter->getURI();
    }

    public function getUserAgent(){
        
        return $this->adapter->getUserAgent();
    }

    public function getUserBrowser(){

        return $this->adapter->getUserBrowser();
    }

    public function getUserOs(){

        return $this->adapter->getUserOs();
    }

    public function getUserDevice(){

        return $this->adapter->getUserDevice();
    }
}