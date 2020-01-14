<?php

namespace Nubesys\Core\Response;

use Nubesys\Core\Common;
use Nubesys\Core\Register;

class Response extends Common {

    protected   $headers;
    protected   $type;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->headers = new Register();
    }

    public function getType(){

        return $this->type;
    }

    public function setHeader($p_key, $p_value){

        $this->headers->set($p_key, $p_value);
    }

    public function getHeaders(){

        return $this->headers->all();
    }
}