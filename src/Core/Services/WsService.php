<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Services\Service;

class WsService extends Service {

    public function __construct($p_di)
    {
        parent::__construct($p_di);
    }

    protected function generateId(){

        $pathPartes     = explode("\\", $this->getClassPath());

        $className      = strtolower(array_pop($pathPartes));

        $this->id       = implode("-", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes)) . "-" . $className;
    }
}