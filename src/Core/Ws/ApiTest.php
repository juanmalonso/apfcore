<?php

namespace Nubesys\Core\Ws;

use Nubesys\Core\Services\WsService;

class ApiTest extends WsService {

    public function testMethod($p_params){

        $result = new \stdClass();
        $result->method = "TEST";
        $result->time = date("H:i:s");

        $this->setServiceSuccess($result);
    }

    public function getMethod($p_params){

        $result = new \stdClass();
        $result->method = "GET";
        $result->time = date("H:i:s");

        $this->setServiceSuccess($result);
    }
}