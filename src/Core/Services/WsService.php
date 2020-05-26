<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Services\Service;

class WsService extends Service {

    public function __construct($p_di)
    {
        parent::__construct($p_di);


    }

    protected function setServiceStatus($p_status){

        $this->getDI()->get('responseObject')->setStatus($p_status);
    }

    public function setServiceInfo($p_info){

        $this->getDI()->get('responseObject')->setInfo($p_info);
    }

    public function setServiceData($p_data){

        $this->getDI()->get('responseObject')->setData($p_data);
    }

    public function setServiceDebug($p_debug){

        $this->getDI()->get('responseObject')->setDebug($p_debug);
    }

    public function setServiceError($p_message){

        $this->getDI()->get('responseObject')->setError($p_message);
    }

    public function setServiceSuccess($p_data){

        $this->getDI()->get('responseObject')->setSuccess($p_data);
    }
}