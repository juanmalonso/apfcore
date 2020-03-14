<?php

namespace Nubesys\Data\DataSource;

use Nubesys\Core\Common;

class DataSource extends Common {

    protected $adapter;

    public function __construct($p_di, $p_adapter)
    {
        parent::__construct($p_di);

        $this->adapter          = $p_adapter;
    }

    public function getData($p_query = array()){

        return $this->adapter->getData($p_query);
    }

    public function getDataDefinitions(){

        return $this->adapter->getDataDefinitions();
    }
}