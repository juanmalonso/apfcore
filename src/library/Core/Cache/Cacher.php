<?php

namespace Nubesys\Core\Cache;

use Nubesys\Core\Common;
use Nubesys\Core\Register;

class Cacher extends Common {

    private $enabled;
    private $cachers;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->cachers      = new Register();
        $this->enabled      = $this->getDI()->get('config')->cache->enabled;

        foreach($this->getDI()->get('config')->cache->cachers as $key=>$value){

            
            if(property_exists($value, "adapter")){

                if($value->adapter == "redis"){

                    $this->cachers->set($key, new \Nubesys\Core\Cache\CacheAdapters\Redis($p_di, $value));
                }

                if($value->adapter == "file"){

                    //TOTO : agregar file adapter
                }

                if($value->adapter == "db"){

                    //TODO : agregar db adapter, con tablename, lifetime etc.
                }
            }
        }
    }

    public function exists($p_cacher, $p_key){

        if($this->cachers->has($p_cacher)){
            
            return $this->cachers->get($p_cacher)->exists($p_key);
        }else{

            //TODO : THOW cacher exceptions 
        }
    }

    public function get($p_cacher, $p_key){

        if($this->cachers->has($p_cacher)){
            
            return $this->cachers->get($p_cacher)->get($p_key);
        }else{

            //TODO : THOW cacher exceptions 
        }
    }

    public function save($p_cacher, $p_key, $p_data, $p_lifetime = 3600){
        
        if($this->cachers->has($p_cacher)){
            
            return $this->cachers->get($p_cacher)->save($p_key, $p_data, $p_lifetime);
        }else{

            //TODO : THOW cacher exceptions 
        }
    }

    public function delete($p_cacher, $p_key){

        if($this->cachers->has($p_cacher)){
            
            return $this->cachers->get($p_cacher)->delete($p_key);
        }else{

            //TODO : THOW cacher exceptions 
        }
    }

    public function flush(){

        if($this->cachers->has($p_cacher)){
            
            return $this->cachers->get($p_cacher)->flush();
        }else{

            //TODO : THOW cacher exceptions 
        }
    }
}