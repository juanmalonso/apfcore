<?php

namespace Nubesys\Data\DataSource\DataSourceAdapters;

use Nubesys\Data\DataSource\DataSourceAdapters\DataSourceAdapter;

//NUBESYS DATA ENGINE
use Nubesys\Data\Objects\DataEngine;

class Objects extends DataSourceAdapter {

    protected $dataEngine;
    protected $options;

    protected $modelData;
    protected $modelDataDefinitions;
    protected $modelDataObjects;

    public function __construct($p_di, $p_options)
    {
        parent::__construct($p_di, $p_options);

        $this->options      = $p_options;

        $this->dataEngine   = new DataEngine($this->getDI());

        $this->setModelData();
        $this->setModelDataDefinitions();
    }

    public function getDataDefinitions(){

        return $this->modelDataDefinitions;
    }

    public function getData($p_query){
        
        $result             = array();
        $result['page']     = 1;
        $result['rows']     = 10;
        $result['totals']   = 3;
        
        $result['objects']  = array();

        //TODO : PREPARAR EL QUERY Y LOS FILTROS (engine get Search)
        $this->setModelDataObjects();

        foreach($this->modelDataObjects as $row){

            $rowTmp                     = array();
            
            foreach($row as $key=>$value){

                if($key == "objData"){

                    foreach($value as $dataKey=>$dataValue){

                        $rowTmp[$dataKey] = $dataValue;
                    }
                }else{

                    $rowTmp[$key] = $value;
                }
            }

            $result['objects'][] = $rowTmp;
        }

        return $result;
    }

    private function setModelData(){

        //LOGICA DE RECUPERACION DE MODEL DATA

        $this->modelData                     = array();
        $this->modelData['id']               = "affiliates";
        $this->modelData['parent']           = "root";
        $this->modelData["type"]             = "OBJECT";
        $this->modelData["idStrategy"]       = "AUTOINCREMENT";
        $this->modelData["partitionMode"]    = "NONE";

        $this->modelData["uiOptions"]                            = new \stdClass();
        $this->modelData["uiOptions"]->help                      = "Texto de ayuda del Modelo";
        $this->modelData["uiOptions"]->icon                      = "users";
        $this->modelData["uiOptions"]->name                      = "Afiliado";
        $this->modelData["uiOptions"]->pluralName                = "Afiliados";
        $this->modelData["uiOptions"]->manageAs                  = "LIST";
        $this->modelData["uiOptions"]->description               = "Texto de Descripcion";

        $this->modelData["indexOptions"]                         = new \stdClass();
        $this->modelData["indexOptions"]->indexable              = true;
        $this->modelData["indexOptions"]->index                  = "affiliates";
        $this->modelData["indexOptions"]->analysis               = new \stdClass();
        $this->modelData["indexOptions"]->basemapping            = new \stdClass();

        $this->modelData["cacheOptions"]                         = new \stdClass();
        $this->modelData["cacheOptions"]->cacheable              = true;
        $this->modelData["cacheOptions"]->adapter                = "MEMORY";
        $this->modelData["cacheOptions"]->cacheLife              = 3600;

        $this->modelData["versionsOptions"]                      = new \stdClass();
        $this->modelData["statesOptions"]                        = new \stdClass();

    }

    private function setModelDataDefinitions(){

        $this->modelDataDefinitions             = array();

        $definitionsRow                         = array();
        $definitionsRow["id"]                   = "name";
        $definitionsRow["type"]                 = "text";
        $definitionsRow["group"]                = "data";
        $definitionsRow["defaultValue"]         = "";
        $definitionsRow["order"]                = 1;
        $definitionsRow["isName"]               = 1;
        $definitionsRow["isImage"]              = 0;

        $definitionsRow["uiOptions"]                        = new \stdClass();
        $definitionsRow["uiOptions"]->help                  = "Texto de Ayuda del campo Nombre";
        $definitionsRow["uiOptions"]->icon                  = "caret right";
        $definitionsRow["uiOptions"]->label                 = "Nombre";
        $definitionsRow["uiOptions"]->hidden                = false;
        $definitionsRow["uiOptions"]->listable              = true;
        $definitionsRow["uiOptions"]->readOnly              = true;
        $definitionsRow["uiOptions"]->required              = true;
        $definitionsRow["uiOptions"]->sortable              = true;
        $definitionsRow["uiOptions"]->filterable            = true;
        $definitionsRow["uiOptions"]->searchable            = true;

        $definitionsRow["indexOptions"]                     = new \stdClass();
        $definitionsRow["indexOptions"]->indexable          = true;
        $definitionsRow["indexOptions"]->mapping            = new \stdClass();

        $definitionsRow['typeOptions']                      = new \stdClass();
        $definitionsRow['validationOptions']                = new \stdClass();
        $definitionsRow['attachFileOptions']                = new \stdClass();

        $this->modelDataDefinitions[$definitionsRow["id"]]  = $definitionsRow;

        $definitionsRow                         = array();
        $definitionsRow["id"]                   = "description";
        $definitionsRow["type"]                 = "text";
        $definitionsRow["group"]                = "data";
        $definitionsRow["defaultValue"]         = "";
        $definitionsRow["order"]                = 1;
        $definitionsRow["isName"]               = 0;
        $definitionsRow["isImage"]              = 0;
        $definitionsRow["isImage"]              = 0;

        $definitionsRow["uiOptions"]                        = new \stdClass();
        $definitionsRow["uiOptions"]->help                  = "Texto de Ayuda del campo Descripción";
        $definitionsRow["uiOptions"]->icon                  = "caret right";
        $definitionsRow["uiOptions"]->label                 = "Descripcion";
        $definitionsRow["uiOptions"]->hidden                = false;
        $definitionsRow["uiOptions"]->listable              = true;
        $definitionsRow["uiOptions"]->readOnly              = true;
        $definitionsRow["uiOptions"]->required              = true;
        $definitionsRow["uiOptions"]->sortable              = true;
        $definitionsRow["uiOptions"]->filterable            = true;
        $definitionsRow["uiOptions"]->searchable            = true;

        $definitionsRow["indexOptions"]                     = new \stdClass();
        $definitionsRow["indexOptions"]->indexable          = true;
        $definitionsRow["indexOptions"]->mapping            = new \stdClass();

        $definitionsRow['typeOptions']                      = new \stdClass();
        $definitionsRow['validationOptions']                = new \stdClass();
        $definitionsRow['attachFileOptions']                = new \stdClass();

        $this->modelDataDefinitions[$definitionsRow["id"]]  = $definitionsRow;
    }

    private function setModelDataObjects($p_query = array()){

        $this->modelDataObjects                             = array();

        $objectTmp                              = array();
        $objectTmp["_id"]                       = "10";
        $objectTmp["objTime"]                   = 15785800000000;
        $objectTmp["objOrder"]                  = 1;
        $objectTmp["objActive"]                 = 1;

        $objectTmp["objData"]                   = new \stdClass();
        $objectTmp["objData"]->name                 = "Affiliado1";
        $objectTmp["objData"]->description          = "Affiliado1 Desc";

        $objectTmp["objDateAdd"]                = "2020-01-09 14:12:43";
        $objectTmp["objUserAdd"]                = NULL;
        $objectTmp["objDateUpdated"]            = "2020-01-09 14:12:43";
        $objectTmp["objUserUpdated"]            = NULL;
        $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
        $objectTmp["objUserErased"]             = NULL;
        $objectTmp["objErased"]                 = 0;
        $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
        $objectTmp["objUserErased"]             = NULL;
        $objectTmp["objDateIndexed"]            = "2020-01-09 14:12:43";
        $objectTmp["objPartitionIndex"]         = 1;
        $objectTmp["objPage1000"]               = 1;
        $objectTmp["objPage10000"]              = 1;
        $objectTmp["objPage10000"]              = 1;

        $this->modelDataObjects[] = $objectTmp;

        $objectTmp                              = array();
        $objectTmp["_id"]                       = "11";
        $objectTmp["objTime"]                   = 15785800000000;
        $objectTmp["objOrder"]                  = 1;
        $objectTmp["objActive"]                 = 1;

        $objectTmp["objData"]                   = new \stdClass();
        $objectTmp["objData"]->name                 = "Affiliado2";
        $objectTmp["objData"]->description          = "Affiliado2 Desc";

        $objectTmp["objDateAdd"]                = "2020-01-09 14:12:43";
        $objectTmp["objUserAdd"]                = NULL;
        $objectTmp["objDateUpdated"]            = "2020-01-09 14:12:43";
        $objectTmp["objUserUpdated"]            = NULL;
        $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
        $objectTmp["objUserErased"]             = NULL;
        $objectTmp["objErased"]                 = 0;
        $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
        $objectTmp["objUserErased"]             = NULL;
        $objectTmp["objDateIndexed"]            = "2020-01-09 14:12:43";
        $objectTmp["objPartitionIndex"]         = 1;
        $objectTmp["objPage1000"]               = 1;
        $objectTmp["objPage10000"]              = 1;
        $objectTmp["objPage10000"]              = 1;

        $this->modelDataObjects[] = $objectTmp;

        $objectTmp                              = array();
        $objectTmp["_id"]                       = "12";
        $objectTmp["objTime"]                   = 15785800000000;
        $objectTmp["objOrder"]                  = 1;
        $objectTmp["objActive"]                 = 1;

        $objectTmp["objData"]                   = new \stdClass();
        $objectTmp["objData"]->name                 = "Affiliado3";
        $objectTmp["objData"]->description          = "Affiliado3 Desc";

        $objectTmp["objDateAdd"]                = "2020-01-09 14:12:43";
        $objectTmp["objUserAdd"]                = NULL;
        $objectTmp["objDateUpdated"]            = "2020-01-09 14:12:43";
        $objectTmp["objUserUpdated"]            = NULL;
        $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
        $objectTmp["objUserErased"]             = NULL;
        $objectTmp["objErased"]                 = 0;
        $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
        $objectTmp["objUserErased"]             = NULL;
        $objectTmp["objDateIndexed"]            = "2020-01-09 14:12:43";
        $objectTmp["objPartitionIndex"]         = 1;
        $objectTmp["objPage1000"]               = 1;
        $objectTmp["objPage10000"]              = 1;
        $objectTmp["objPage10000"]              = 1;

        $this->modelDataObjects[] = $objectTmp;
    }
}