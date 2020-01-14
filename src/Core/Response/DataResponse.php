<?php

namespace Nubesys\Core\Response;

use Nubesys\Core\Response\Response;

class DataResponse extends Response {

    private $status;
    private $info;
    private $data;
    private $debug;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->type         = "data";

        $this->status       = "KO";
        $this->info         = "ERROR";
        $this->data         = new \stdClass();
        $this->debug        = new \stdClass();
    }

    public function setStatus($p_status){

        $this->status = $p_status;
    }

    public function setInfo($p_info){

        $this->info = $p_info;
    }

    public function setData($p_data){

        $this->data = $p_data;
    }

    public function setDebug($p_debug){

        $this->debug = $p_debug;
    }

    public function setError($p_message){

        $this->setStatus("KO");
        $this->setInfo($p_message);
    }

    public function setSuccess($p_data){

        $this->setStatus("OK");
        $this->setInfo("SUCCESS");
        $this->setData($p_data);
    }

    public function getBody(){

        $jsonObject             = new \stdClass();
        $jsonObject->status     = $this->status;
        $jsonObject->info       = $this->info;
        $jsonObject->data       = $this->data;
        $jsonObject->debug      = $this->debug;

        return json_encode($jsonObject);
    }
}