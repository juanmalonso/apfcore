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

    public function editData($p_id, $p_data){

        return $this->adapter->editObjectData($p_id, $p_data);
    }

    public function addData($p_data){

        return $this->adapter->addObjectData($p_data);
    }

    public function importData($p_data){

        return $this->adapter->importObjectsData($p_data);
    }

    public function reindexData(){

        return $this->adapter->reindexData();
    }

    public function setState($p_id, $p_state){

        return $this->adapter->setObjectState($p_id, $p_state);
    }

    //getData(ID) || getData(QueryArray)
    public function getData($p_query = array()){
        
        return $this->adapter->getData($p_query);
    }

    public function getDataIdNames($p_query = array()){

        return $this->adapter->getDataIdNames($p_query);
    }

    public function getModelData(){

        return $this->adapter->getModelData();
    }

    public function getDataDefinitions(){

        return $this->adapter->getDataDefinitions();
    }

    public function getDataRenderableDefinitions(){

        return $this->adapter->getDataRenderableDefinitions();
    }

    public function getDataFieldDefinitions($p_field){

        return $this->adapter->getDataFieldDefinitions($p_field);
    }
}