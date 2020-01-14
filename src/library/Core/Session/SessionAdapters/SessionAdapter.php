<?php

namespace Nubesys\Core\Session\SessionAdapters;

use Nubesys\Core\Common;
use Nubesys\Core\Register;

class SessionAdapter extends Common {

    protected $data;
    protected $id;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->data = new Register();
    }

    public function getId(){

        return $this->id;
    }

    public function has($p_key){

        return $this->data->has($p_key);
    }

    public function get($p_key){

        return $this->data->get($p_key);
    }

    public function set($p_key, $p_data){
        
        $this->data->set($p_key, $p_data);
    }
}