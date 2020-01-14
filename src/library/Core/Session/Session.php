<?php

namespace Nubesys\Core\Session;

use Nubesys\Core\Common;
use Nubesys\Core\Session\SessionAdapters\Redis;
use Nubesys\Core\Session\SessionAdapters\File;

class Session extends Common {

    private $adapter;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        switch ($this->getDI()->get('config')->session->adapter) {
            case 'redis':
                $this->adapter = new Redis($p_di, $this->getDI()->get('config')->session);
                break;
            
            case 'file':
                # code...
                break;
        }   
    }

    public function start($p_id){

        $this->adapter->start($p_id);
    }

    public function getId(){

        return $this->adapter->getId();
    }

    public function has($p_key){

        return $this->adapter->has($p_key);
    }

    public function set($p_key, $p_data){

        $this->adapter->set($p_key, $p_data);
    }

    public function get($p_key){

        return $this->adapter->get($p_key);
    }

    public function destroy(){

        $this->adapter->destroy();
    }

    public function end(){

        $this->adapter->save();


    }
}