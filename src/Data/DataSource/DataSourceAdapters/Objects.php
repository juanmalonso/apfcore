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
        
        if(is_array($p_query)){
            $result             = array();
            $result['page']     = (isset($p_query['page'])) ? $p_query['page'] : 1;
            $result['rows']     = (isset($p_query['rows'])) ? $p_query['rows'] : 10;

            $validFilters           = array();
            //$validSearchFields      = array('_id');

            foreach($this->getDataDefinitions() as $definition){
                
                //FILTERS
                if(isset($p_query['filters']) && \is_array($p_query['filters'])){

                    if(property_exists($definition['uiOptions'],'filterable')){

                        if($definition['uiOptions']->filterable == true){

                            if(isset($p_query['filters'][$definition['id']])){

                                $validFilters['objData.' . $definition['id']] = (array)$p_query['filters'][$definition['id']];
                            }
                        }
                    }
                }

                //SEARCH FIELDS
                if(property_exists($definition['uiOptions'],'searchable')){

                    if($definition['uiOptions']->searchable == true){

                        $validSearchFields[] = 'objData.' . $definition['id'];

                        if($definition['type'] == 'objectr'){

                            $validSearchFields[] = 'objData.' . $definition['id'] . '_flltxt';
                        }

                        if($definition['type'] == 'objectsr'){

                            $validSearchFields[] = 'objData.' . $definition['id'] . '_flltxt';
                        }
                    }
                }
            }

            if(isset($this->options['hardfilters'])){

                foreach($this->options['hardfilters'] as $field=>$terms){

                    $validFilters["objData." . $field]       = $terms; 
                }
            }
            
            $searchQuery                = array();
            $searchQuery['rows']        = $result['rows'];
            $searchQuery['page']        = $result['page'];
            $searchQuery['filters']     = $validFilters;
            $searchQuery['keyword']     = (count($validSearchFields) > 0) ? ((isset($p_query['keyword'])) ? $p_query['keyword'] : '*') : '*';
            $searchQuery['fields']      = (count($validSearchFields) > 0) ? $validSearchFields : array('*');
            
            if(isset($p_query['orders']) && \is_array($p_query['orders']) && count($p_query['orders']) > 0){

                $searchQuery['orders']  = $p_query['orders'];
            }
            
            $searchResult               = $this->dataEngine->searchObjects($this->options['model'], $searchQuery);
            
            if($searchResult != false){

                $result['totals']       = $searchResult['totals'];
                $result['pages']        = ceil($result['totals']/$result['rows']);
                $result['objects']      = array();

                $rownum = ($result['page'] * $result['rows']) - ($result['rows'] - 1);

                if(isset($searchResult['objects']) && is_array($searchResult['objects'])){
                    
                    foreach($searchResult['objects'] as $row){

                        $rowTmp                         = $this->normalizeObjectData($row);
            
                        $rowTmp['num']                  = $rownum++;
            
                        $result['objects'][]            = $rowTmp;
                    }
                }
            }

        }else{
            
            $result = $this->normalizeObjectData($this->dataEngine->getObject($this->options['model'], $p_query));
            
        }
        
        return $result;
    }

    public function getDataIdNames($p_query){
        $result                 = false;
        
        $nameField              = $this->getNameField();
        $imageField             = $this->getImageField();

        if($this->options['model'] == "image" || $this->options['model'] == "avatar"){

            $imageField         = "_id";
        }

        if(\is_array($p_query)){
            
            $queryResult = $this->getData($p_query);
            
            $result             = array();
            $result['page']     = (isset($queryResult['page'])) ? $queryResult['page'] : 1;
            $result['rows']     = (isset($queryResult['rows'])) ? $queryResult['rows'] : 100;
            $result['totals']   = (isset($queryResult['totals'])) ? $queryResult['totals'] : 1;
            $result['pages']    = (isset($queryResult['pages'])) ? $queryResult['pages'] : 1;
            $result['objects']  = array();

            if(isset($queryResult['objects'])){
                
                foreach($queryResult['objects'] as $object){
                    
                    $objectTmp              = array();
                    $objectTmp['id']        = $object['_id'];
                    $objectTmp['name']      = (isset($object[$nameField])) ? $object[$nameField] : "";
                    $objectTmp['image']     = (isset($object[$imageField])) ? $object[$imageField]: "";
                    $objectTmp['icon']      = (isset($object['icon'])) ? $object['icon'] : "";

                    $result['objects'][]           = $objectTmp;
                }
            }

        }else{
            
            $queryResult = $this->getData($p_query);
            
            $result             = array();
            $result['id']       = $queryResult['_id'];
            $result['name']     = (isset($queryResult[$nameField])) ? $queryResult[$nameField]: "";
            $result['image']    = (isset($queryResult[$imageField])) ? $queryResult[$imageField]: "";
            $result['icon']     = (isset($queryResult['icon'])) ? $queryResult['icon'] : "";
        }
        
        return $result;
    }

    public function setObjectState($p_id, $p_state){

        //TODO: VALIDACION DE ESTADOS

        return $this->dataEngine->setState($this->options['model'], $p_id, $p_state);
    }

    public function editObjectData($p_id, $p_data){

        if(isset($this->options['toSaveDefaultValues'])){

            foreach($this->options['toSaveDefaultValues'] as $field=>$value){

                $p_data[$field]       = $value; 
            }
        }

        return $this->dataEngine->editObject($this->options['model'], $p_id, $p_data);
    }

    public function addObjectData($p_data){
        
        if(isset($this->options['toSaveDefaultValues'])){

            foreach($this->options['toSaveDefaultValues'] as $field=>$value){

                $p_data[$field]       = $value; 
            }
        }

        if(isset($this->options['toImportDefaultValues'])){

            foreach($this->options['toImportDefaultValues'] as $field=>$value){

                $p_data[$field]       = $value; 
            }
        }

        if(isset($this->options['customIdField'])){

            $p_data['_id'] = $p_data[$this->options['customIdField']];
        }

        return $this->dataEngine->addObject($this->options['model'], $p_data);
    }

    public function importObjectsData($p_data){

        $result             = array();

        foreach($p_data as $objectData){
            
            $result[]       = $this->addObjectData($objectData);

            usleep('50');
        }
        
        return $result;
    }

    public function getModelData(){

        return $this->modelData;
    }

    private function normalizeObjectData($p_data){
        
        $result             = array();
        
        foreach($p_data as $key=>$value){

            if($key == "objData"){

                foreach($value as $dataKey=>$dataValue){

                    $result[$dataKey]   = $dataValue;
                }
            }else{

                $result[$key]           = $value;
            }
        }

        return $result;
    }

    private function getNameField(){

        $result = "_id";

        foreach($this->modelDataDefinitions as $definition){

            if($definition['isName'] == "1"){

                $result = $definition['id'];
                break;
            }
        }

        return $result;
    }

    private function getImageField(){

        $result = "image";

        foreach($this->modelDataDefinitions as $definition){

            if($definition['isImage'] == "1"){

                $result = $definition['id'];
                break;
            }
        }

        return $result;
    }

    private function setModelData(){

        //LOGICA DE RECUPERACION DE MODEL DATA
        $modelDataTmp                       = $this->dataEngine->getModel($this->options['model']);
        
        $this->modelData                    = array();
        $this->modelData['id']              = $modelDataTmp['modId'];
        $this->modelData['parent']          = $modelDataTmp['modParent'];
        $this->modelData["type"]            = $modelDataTmp['modType'];
        $this->modelData["idStrategy"]      = $modelDataTmp['modIdStrategy'];;
        $this->modelData["partitionMode"]   = $modelDataTmp['modPartitionMode'];;
        $this->modelData["uiOptions"]       = $modelDataTmp['modUiOptions'];
        $this->modelData["indexOptions"]    = $modelDataTmp['modIndexOptions'];
        $this->modelData["cacheOptions"]    = $modelDataTmp['modCacheOptions'];
        $this->modelData["versionsOptions"] = $modelDataTmp['modVersionsOptions'];
        $this->modelData["statesOptions"]   = $modelDataTmp['modStatesOptions'];
    }

    private function setModelDataDefinitions(){
        
        $this->modelDataDefinitions             = array();

        /*
        
        TODO : COLOCAR AQUI LOS CAMPOS BASE ASI!!

        $definitionsRow                                     = array();
        $definitionsRow["id"]                               = "modId";
        $definitionsRow["type"]                             = "text";
        $definitionsRow["group"]                            = "data";
        $definitionsRow["defaultValue"]                     = "";
        $definitionsRow["order"]                            = 1;
        $definitionsRow["isName"]                           = true;
        $definitionsRow["isImage"]                          = false;
        $definitionsRow["uiOptions"]                        = new \stdClass();
        $definitionsRow["uiOptions"]->help                      = "";
        $definitionsRow["uiOptions"]->icon                      = "caret right";
        $definitionsRow["uiOptions"]->info                      = "";
        $definitionsRow["uiOptions"]->label                     = "Model Name";
        $definitionsRow["uiOptions"]->hidden                    = false;
        $definitionsRow["uiOptions"]->readOnly                  = false;
        $definitionsRow["uiOptions"]->listable                  = true;
        $definitionsRow["uiOptions"]->required                  = true;
        $definitionsRow["uiOptions"]->sortable                  = false;
        $definitionsRow["uiOptions"]->filterable                = false;
        $definitionsRow["uiOptions"]->searchable                = false;
        $definitionsRow["indexOptions"]                     = new \stdClass();
        $definitionsRow["typeOptions"]                      = new \stdClass();
        $definitionsRow['validationOptions']                = new \stdClass();
        $definitionsRow['attachFileOptions']                = new \stdClass();
        
        */
        
        $notRenderableFields    = array();
        if(isset($this->options['notRenderableFields'])){

            $notRenderableFields = $this->options['notRenderableFields'];
        }

        //OBJECTS FIELDS
        $definitions            = $this->dataEngine->getDefinition($this->options['model'], null);
        $definitionsLastOrder   = 0;
        if(\is_array($definitions)){

            foreach($definitions as $definition){

                $fieldId                                                = $definition['dafId'];
                
                if(!in_array($fieldId, $notRenderableFields)){

                    $definitionsRow                                     = array();
                    $definitionsRow["id"]                               = $definition['dafId'];
                    $definitionsRow["type"]                             = $definition['typId'];
                    $definitionsRow["group"]                            = $definition['flgId'];
                    $definitionsRow["defaultValue"]                     = $definition['defDafDefaultValue'];

                    if($definition['typId'] == "json" && ($definition['defDafDefaultValue'] == "" || $definition['defDafDefaultValue'] == "{}")){

                        $definitionsRow["defaultValue"]                 = new \stdClass();

                    }elseif(($definition['typId'] == "objectsr" || $definition['typId'] == "tags" || $definition['typId'] == "options") && ($definition['defDafDefaultValue'] == "" || $definition['defDafDefaultValue'] == "[]")){

                        $definitionsRow["defaultValue"]                 = array();
                    }

                    $definitionsRow["order"]                            = $definition['defOrder'];
                    $definitionsRow["isName"]                           = $definition['defIsName'];
                    $definitionsRow["isImage"]                          = $definition['defIsImage'];
                    $definitionsRow["isRelation"]                       = false;
                    $definitionsRow["isState"]                          = false;
                    $definitionsRow["uiOptions"]                        = $definition['defDafUiOptions'];
                    $definitionsRow["indexOptions"]                     = $definition['defDafIndexOptions'];
                    $definitionsRow['typeOptions']                      = $definition['defDafTypOptions'];
                    $definitionsRow['validationOptions']                = $definition['defDafTypValidationOptions'];
                    $definitionsRow['attachFileOptions']                = $definition['defDafAttachFileOptions'];

                    $definitionsLastOrder                               = (int)$definitionsRow["order"];

                    $this->modelDataDefinitions[$definitionsRow["id"]]  = $definitionsRow;
                }
            }
        }
        
        //RELATIONS
        $relations          = $this->dataEngine->getModelRelations($this->options['model'], "IN");

        if(\is_array($relations)){

            foreach($relations as $relation){

                $fieldId                                                = "rel_" . $relation['modId'];

                if(!in_array($fieldId, $notRenderableFields)){

                    $relationRow                                        = array();
                    $relationRow["id"]                                  = $fieldId;
                    $relationRow["type"]                                = "options";

                    if(property_exists($relation['relUiOptions'], 'editGroup')){

                        $relationRow["group"]                           = $relation['relUiOptions']->editGroup;
                    }else{

                        $relationRow["group"]                           = "data";
                    }
                    
                    $relationRow["defaultValue"]                        = "";
                    $relationRow["order"]                               = $definitionsLastOrder++;
                    $relationRow["isName"]                              = false;
                    $relationRow["isImage"]                             = false;
                    $relationRow["isRelation"]                          = true;
                    $relationRow["isState"]                             = false;
                    $relationRow["uiOptions"]                           = $relation['relUiOptions'];
                    $relationRow["indexOptions"]                        = $relation['relIndexOptions'];
                    $relationRow['typeOptions']                         = new \stdClass();
                    $relationRow['validationOptions']                   = new \stdClass();
                    $relationRow['attachFileOptions']                   = new \stdClass();
                    $relationRow["leftModId"]                           = $relation['relLeftModId'];
                    $relationRow["leftDirection"]                       = $relation['relLeftDirection'];
                    $relationRow["rightModId"]                          = $relation['relRightModId'];
                    $relationRow["rightDirection"]                      = $relation['relRightDirection'];
                    $relationRow["cardinality"]                         = $relation['relCardinality'];
                    
                    $this->modelDataDefinitions[$relationRow["id"]]     = $relationRow;
                }
            }
        }

        //STATE
        if(property_exists($this->modelData['statesOptions'], 'stateable') && $this->modelData['statesOptions']->stateable == true){
            
            //STATE
            $fieldId                                            = "objState";
            
            if(!in_array($fieldId, $notRenderableFields)){

                
                $stateRow                                       = array();
                $stateRow["id"]                                 = $fieldId;
                $stateRow["type"]                               = "text";
                $stateRow["group"]                              = "data";
                $stateRow["defaultValue"]                       = $definition['defDafDefaultValue'];

                if(property_exists($this->modelData['statesOptions'], 'defaultState')){

                    $stateRow["defaultValue"]                   = $this->modelData['statesOptions']->defaultState;

                }

                $stateRow["order"]                              = 0;
                $stateRow["isName"]                             = false;
                $stateRow["isImage"]                            = false;
                $stateRow["isRelation"]                         = false;
                $stateRow["isState"]                            = true;
                $stateRow["uiOptions"]                          = new \stdClass();
                $stateRow["uiOptions"]->help                    = '';
                $stateRow["uiOptions"]->icon                    = 'caret right';
                $stateRow["uiOptions"]->info                    = '';
                $stateRow["uiOptions"]->label                   = 'Estado';
                $stateRow["uiOptions"]->hidden                  = false;
                $stateRow["uiOptions"]->readOnly                = true;
                $stateRow["uiOptions"]->listable                = true;
                $stateRow["uiOptions"]->required                = true;
                $stateRow["uiOptions"]->sortable                = true;
                $stateRow["uiOptions"]->filterable              = true;
                $stateRow["uiOptions"]->searchable              = false;
                $stateRow["indexOptions"]                       = new \stdClass();
                $stateRow['typeOptions']                        = new \stdClass();
                $stateRow['validationOptions']                  = new \stdClass();
                $stateRow['attachFileOptions']                  = new \stdClass();

                $this->modelDataDefinitions[$stateRow["id"]]    = $stateRow;
            }


            //STATELOGS
            
            $fieldId                                            = "objStatesLog";
            
            if(!in_array($fieldId, $notRenderableFields)){
                
                $stateRow                                       = array();
                $stateRow["id"]                                 = $fieldId;
                $stateRow["type"]                               = "json";
                $stateRow["group"]                              = "data";
                $stateRow["defaultValue"]                       = array();
                $stateRow["order"]                              = 0;
                $stateRow["isName"]                             = false;
                $stateRow["isImage"]                            = false;
                $stateRow["isRelation"]                         = false;
                $stateRow["isState"]                            = true;
                $stateRow["uiOptions"]                          = new \stdClass();
                $stateRow["uiOptions"]->help                    = '';
                $stateRow["uiOptions"]->icon                    = 'caret right';
                $stateRow["uiOptions"]->info                    = '';
                $stateRow["uiOptions"]->label                   = 'Estado Historico';
                $stateRow["uiOptions"]->hidden                  = true;
                $stateRow["uiOptions"]->readOnly                = true;
                $stateRow["uiOptions"]->listable                = false;
                $stateRow["uiOptions"]->required                = false;
                $stateRow["uiOptions"]->sortable                = true;
                $stateRow["uiOptions"]->filterable              = true;
                $stateRow["uiOptions"]->searchable              = false;
                $stateRow["indexOptions"]                       = new \stdClass();
                $stateRow['typeOptions']                        = new \stdClass();
                $stateRow['validationOptions']                  = new \stdClass();
                $stateRow['attachFileOptions']                  = new \stdClass();

                $this->modelDataDefinitions[$stateRow["id"]]    = $stateRow;
            }
        }
    }

    public function getDataFieldDefinitions($p_field){

        return                  $this->dataEngine->getField($p_field);
    }

    public function reindexData(){
        $result                     = 0;

        $modelData                  = $this->dataEngine->getModel($this->options['model']);
        
        if($this->dataEngine->isIndexableModel($modelData['modId'])){

            $indexName              = $this->dataEngine->getModelIndexName($modelData['modId']);

            $deleteIndexResult  = $this->dataEngine->deleteIndex($indexName);

            //$deleteIndexResult      = true;
            
            if($deleteIndexResult){

                $options                    = array();
                $options['rows']            = 10000;
                $options['page']            = 1;
                
                $objects = $this->dataEngine->getObjects($modelData['modId'], $options);
                
                if($objects !== false){

                    $reindexResult = $this->dataEngine->reindexObjects($modelData, $objects);
                    
                    if($reindexResult === false){

                        //TODO ERROR
                    }
                
                    $result += count($objects);

                }else{

                    //TODO ERROR
                }
            }
        }
        
        return $result;
    }
}