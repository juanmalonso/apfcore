<?php

namespace Nubesys\Core\Response\ResponseAdapters;

use Nubesys\Core\Response\ResponseAdapters;

class Data extends ResponseAdapter {

    private $customBody;
    private $status;
    private $info;
    private $data;
    private $debug;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->customBody   = null;

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

    public function setError($p_message, $p_httpCode){

        $this->setStatus("KO");
        $this->setInfo($p_message);
        $this->setHttpCode($p_httpCode);
    }

    public function setCustomBody($p_object){

        $this->customBody = $p_object;
    }

    public function setSuccess($p_data){

        $this->setStatus("OK");
        $this->setInfo("SUCCESS");
        $this->setData($p_data);
    }

    public function getBody(){
        $result                     = "{}";

        if($this->customBody !== null){

            $result                 = json_encode($this->customBody);
        }else{

            $jsonObject             = new \stdClass();
            $jsonObject->status     = $this->status;
            $jsonObject->info       = $this->info;
            $jsonObject->data       = $this->data;
            $jsonObject->debug      = $this->debug;

            $result                 = json_encode($jsonObject);
        }

        return $result;
    }
}