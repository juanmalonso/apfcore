<?php

namespace Nubesys\Core\Request\RequestAdapters;

use Nubesys\Core\Common;

class Phalcon extends Common {

    private $requestObject;

    public function __construct($p_di, $p_requestObject)
    {
        parent::__construct($p_di);

        $this->requestObject = $p_requestObject;
    }

    public function getMethod(){

        return $this->requestObject->getMethod();
    }

    public function getHeaders(){

        return $this->requestObject->getHeaders();
    }

    public function getGET(){

        return $this->requestObject->getQuery();
    }

    public function getPOST(){

        return $this->requestObject->getPost();
    }

    public function getJSON(){

        return json_decode($this->requestObject->getRawBody(), true);
    }

    public function getFILES(){

        return $this->requestObject->getUploadedFiles();
    }
}