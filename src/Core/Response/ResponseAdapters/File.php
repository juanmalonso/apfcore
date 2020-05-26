<?php

namespace Nubesys\Core\Response\ResponseAdapters;

use Nubesys\Core\Response\ResponseAdapters\ResponseAdapter;

class File extends ResponseAdapter {

    protected $data;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->data         = "";
    }

    public function getBody()
    {
        return $this->data;
    }

    public function setData($p_data){

        $this->data     = $p_data;
    }
}