<?php
namespace Nubesys\Core\Cache\CacheAdapters;

use Nubesys\Core\Cache\CacheAdapters\CacheAdapter;

class Redis extends CacheAdapter {

    protected $cacher;

    public function __construct($p_di, $p_connection)
    {
        parent::__construct($p_di);

        if(property_exists($this->getDI()->get('config')->connections, $p_connection)){
            
            $connection     = $this->getDI()->get('config')->connections->$p_connection;

            $lifetime       = $this->getDI()->get('config')->connections->$p_connection->lifetime;
            $prefix         = $this->getDI()->get('config')->connections->$p_connection->prefix;
            $host           = $this->getDI()->get('config')->connections->$p_connection->host;
            $port           = $this->getDI()->get('config')->connections->$p_connection->port;
            $db             = $this->getDI()->get('config')->connections->$p_connection->db;

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
}