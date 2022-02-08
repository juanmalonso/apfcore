<?php

namespace Nubesys\Core\Ws;

use Nubesys\Core\Services\WsService;

class ApiTest extends WsService {

    public function rawQueryMethod(){

        $result = new \stdClass();
        $result->method = "TEST";
        $result->time = date("H:i:s");

        $this->setServiceSuccess($result);
    }

    public function testMethod(){

        $result = new \stdClass();
        $result->method = "TEST";
        $result->time = date("H:i:s");

        $this->setServiceSuccess($result);
    }

    public function getMethod(){

        $result = new \stdClass();
        $result->method = "GET";
        $result->time = date("H:i:s");

        $this->setServiceSuccess($result);
    }
}