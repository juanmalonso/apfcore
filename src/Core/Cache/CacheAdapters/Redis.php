<?php
namespace Nubesys\Core\Cache\CacheAdapters;

use Nubesys\Core\Cache\CacheAdapters\CacheAdapter;

class Redis extends CacheAdapter {

    protected $cacher;

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
            
        $this->cacher   = new \Phalcon\Cache\Backend\Redis($frontCache,
            [
            'host'          => $host,
            'port'          => $port,
            'persistent'    => false,
            'prefix'        => $prefix,
            'index'         => $db
            ]
        );
    }
}