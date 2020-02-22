<?php

namespace Nubesys\Core\Cache;

use Nubesys\Core\Common;

class Cacher extends Common {

    private $adapter;
    private $enabled;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->enabled      = $this->getDI()->get('config')->main->cache->enabled;

        if($this->getDI()->get('config')->main->cache->adapter == "redis"){

            $this->adapter  = new \Nubesys\Core\Cache\CacheAdapters\Redis($p_di, $this->getDI()->get('config')->main->cache->connection);
        }

        if($this->getDI()->get('config')->main->cache->adapter == "file"){

            //TOTO : agregar file adapter
        }

    }

    public function exists($p_key){

        return $this->adapter->exists($p_key);
    }

    public function get($p_cacher, $p_key){

        return $this->adapter->get($p_key);
    }

    public function save($p_cacher, $p_key, $p_data, $p_lifetime = 3600){
        
        return $this->adapter->save($p_key, $p_data, $p_lifetime);
    }

    public function delete($p_cacher, $p_key){

        return $this->adapter->delete($p_key);
    }

    public function flush(){

        return $this->adapter->flush();
    }
}