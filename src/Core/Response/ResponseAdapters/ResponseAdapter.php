<?php

namespace Nubesys\Core\Response\ResponseAdapters;

use Nubesys\Core\Common;
use Nubesys\Core\Register;

class ResponseAdapter extends Common {

    protected   $headers;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->headers = new Register();
    }

    public function setHeader($p_key, $p_value){

        $this->headers->set($p_key, $p_value);
    }

    public function getHeaders(){

        return $this->headers->all();
    }
}