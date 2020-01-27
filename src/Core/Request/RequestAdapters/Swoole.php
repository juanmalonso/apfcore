<?php

namespace Nubesys\Core\Request\RequestAdapters;

use Nubesys\Core\Common;

class Swoole extends Common {

    private $requestObject;

    public function __construct($p_requestObject)
    {
        parent::__construct($p_di);

        $this->requestObject = $p_requestObject;
    }

    public function getMethod(){

        
    }

    public function getHeaders(){

        
    }

    public function getGET(){

        
    }

    public function getPOST(){

        
    }

    public function getJSON(){

        
    }

    public function getFILES(){

        
    }
}