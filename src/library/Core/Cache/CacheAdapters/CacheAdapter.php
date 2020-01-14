<?php

namespace Nubesys\Core\Cache\CacheAdapters;

use Nubesys\Core\Common;

class CacheAdapter extends Common {

    public function __construct($p_di)
    {
        parent::__construct($p_di);
    }

    public function exists($p_key){

        return $this->cacher->exists($p_key);
    }

    public function get($p_key){

        return $this->cacher->get($p_key);
    }

    public function save($p_key, $p_data, $p_lifetime){
        
        $this->cacher->save($p_key, $p_data, $p_lifetime);
    }

    public function delete($p_key){

        $this->cacher->delete($p_key);
    }

    public function flush(){

        $this->cacher->flush();
    }
}