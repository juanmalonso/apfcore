<?php
namespace Nubesys\Core\Session\SessionAdapters;

use Nubesys\Core\Session\SessionAdapters\SessionAdapter;

class Redis extends SessionAdapter {

    protected $manager;

    public function __construct($p_di, $p_config)
    {
        parent::__construct($p_di);

        $lifetime       = $p_config->lifetime;
        $prefix         = $p_config->prefix;

        if(property_exists($p_config, 'connection')){

            $connection = $p_config->connection;

            $host           = $this->getDI()->get('config')->connections->$connection->host;
            $port           = $this->getDI()->get('config')->connections->$connection->port;
            $db             = $this->getDI()->get('config')->connections->$connection->db;

        }else{

            $host           = $p_config->host;
            $port           = $p_config->port;
            $db             = $p_config->db;
        }

        $frontCache     = new \Phalcon\Cache\Frontend\Data(array("lifetime" => $lifetime));
            
        $this->manager   = new \Phalcon\Cache\Backend\Redis($frontCache,
            [
            'host'          => $host,
            'port'          => $port,
            'persistent'    => false,
            'prefix'        => $prefix,
            'index'         => $db
            ]
        );
    }

    public function start($p_id){

        $this->id = $p_id;

        if($this->manager->exists($p_id)){

            $this->data->setAll($this->manager->get($p_id));
        }
    }

    public function destroy(){

        $this->manager->delete($this->id);
    }

    public function save(){

        $this->manager->save($this->id, $this->data->all());
    }
}

/*
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
*/