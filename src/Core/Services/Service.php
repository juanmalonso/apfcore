<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Common;
use Nubesys\Core\Register;

class Service extends Common {

    //SERVICE LEVEL SCOPE
    protected $scope;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        //SERVICE LEVEL SCOPE
        $this->scope = new Register();
    }

    protected function setScopeVar($p_key, $p_value){

        $this->scope->set($p_key, $p_value);
    }

    protected function getScopeVar($p_key){

        return $this->scope->get($p_key);
    }

    protected function getScopeAll(){

        return $this->scope->all();
    }

    protected function hasScopeVar($p_key){

        return $this->scope->has($p_key);
    }

    protected function setScopeData($p_category, $p_data){

        $flattenedData = \Nubesys\Core\Utils\Struct::flatten($p_data, $p_category);

        foreach($flattenedData as $key=>$value){

            $this->scope->set($key, $value);
        }
    }
}