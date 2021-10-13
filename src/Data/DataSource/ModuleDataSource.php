<?php

namespace Nubesys\Data\DataSource;

use Nubesys\Data\DataSource\DataSourceAdapters\DataSourceAdapter;
use Nubesys\Data\Objects\Adapters\Mysql;
use Nubesys\Core\Common;

//NUBESYS DATA ENGINE
use Nubesys\Data\Objects\DataEngine;

class ModuleDataSource extends Common {

    protected $dataEngine;
    protected $options;

    protected $modelData;
    protected $modelDataDefinitions;
    protected $modelDataObjects;

    public function __construct($p_di)
    {
        parent::__construct($p_di);
        
        $this->dataEngine   = new DataEngine($this->getDI());
    }

    public function getModelObjects($p_model, $p_query){
        
        $result                                         = array();

        $result["model"]                                = $this->getModelData($p_model);
        $result["fields"]                               = $this->getModelDefinitions($p_model, $result["model"]);
        $result["fieldsGroups"]                         = $this->getModelDefinitionsGroups();
        $result['page']                                 = (isset($p_query['page'])) ? $p_query['page'] : 1;
        $result['rows']                                 = (isset($p_query['rows'])) ? $p_query['rows'] : 15;
        
        $validFilters       = array();
        $validRanges        = array();
        $validAggregations  = array();
        //$validSearchFields      = array('_id');

        //HARD FILTERS (OPTIONS)
        if(isset($this->options['hardfilters'])){

            if(!isset($p_query['filters'])){

                $p_query['filters']                     = array();
            }

            foreach($this->options['hardfilters'] as $field=>$terms){

                $p_query['filters'][$field]             = $terms; 
            }
        }

        //HARD FILTERS (PARAMS)
        if(isset($p_query['hardfilters'])){
            
            if(!isset($p_query['filters'])){

                $p_query['filters']                     = array();
            }

            foreach($p_query['hardfilters'] as $field=>$terms){

                $p_query['filters'][$field]             = $terms; 
            }
        }
        
        //HARD RANGES
        if(isset($this->options['hardranges'])){

            if(!isset($p_query['ranges'])){

                $p_query['ranges']                     = array();
            }

            foreach($this->options['hardranges'] as $field=>$range){

                $p_query['ranges'][$field]             = $range; 
            }
        }

        //FILTERS
        if(isset($p_query['filters']) && \is_array($p_query['filters'])){
            
            foreach($p_query['filters'] as $filterName=>$filterData){

                //DEFAULT FILTERS
                if(!in_array($filterName, array("and", "or", "not"))){

                    if(in_array($filterName, array("_id", "objState", "objActive", "objErased", "objUserAdd", "objUserUpdated", "objUserErased"))){
                        
                        $validFilters[$filterName] = (array)$filterData;
                    }else if(isset($result["fields"][$filterName])){

                        if(property_exists($result["fields"][$filterName]['uiOptions'],'filterable')){

                            if($result["fields"][$filterName]['uiOptions']->filterable == true){

                                $validFilters['objData.' . $filterName]   = (array)$filterData;
                            }
                        }
                    }

                }else{

                    //AND
                    if($filterName == "and"){

                        foreach($filterData as $andFilterName=>$andFilterData){

                            if(!isset($validFilters['and'])){

                                $validFilters['and']                    = array();
                            }

                            if(in_array($andFilterName, array("_id", "objState", "objActive", "objErased", "objUserAdd", "objUserUpdated", "objUserErased"))){
                        
                                $validFilters['and'][$andFilterName] = (array)$andFilterData;
                            }else if(isset($result["fields"][$andFilterName])){
        
                                if(property_exists($result["fields"][$andFilterName]['uiOptions'],'filterable')){
        
                                    if($result["fields"][$andFilterName]['uiOptions']->filterable == true){
        
                                        $validFilters['and']['objData.' . $andFilterName]   = (array)$andFilterData;
                                    }
                                }
                            }
                        }
                    }

                    //AND
                    if($filterName == "or"){

                        foreach($filterData as $orFilterName=>$orFilterData){

                            if(!isset($validFilters['or'])){

                                $validFilters['or']                    = array();
                            }

                            if(in_array($orFilterName, array("_id", "objState", "objActive", "objErased", "objUserAdd", "objUserUpdated", "objUserErased"))){
                        
                                $validFilters['or'][$orFilterName] = (array)$orFilterData;
                            }else if(isset($result["fields"][$orFilterName])){
        
                                if(property_exists($result["fields"][$orFilterName]['uiOptions'],'filterable')){
        
                                    if($result["fields"][$orFilterName]['uiOptions']->filterable == true){
        
                                        $validFilters['or']['objData.' . $orFilterName]   = (array)$orFilterData;
                                    }
                                }
                            }
                        }
                    }

                    //NOT
                    if($filterName == "not"){

                        foreach($filterData as $notFilterName=>$notFilterData){

                            if(!isset($validFilters['not'])){

                                $validFilters['not']                    = array();
                            }

                            if(in_array($notFilterName, array("_id", "objState", "objActive", "objErased", "objUserAdd", "objUserUpdated", "objUserErased"))){
                        
                                $validFilters['not'][$notFilterName] = (array)$notFilterData;
                            }else if(isset($result["fields"][$notFilterName])){
        
                                if(property_exists($result["fields"][$notFilterName]['uiOptions'],'filterable')){
        
                                    if($result["fields"][$notFilterName]['uiOptions']->filterable == true){
        
                                        $validFilters['not']['objData.' . $notFilterName]   = (array)$notFilterData;
                                    }
                                }
                            }
                        }
                    }
                }
                
            }
        }

        foreach($result["fields"] as $definition){
            
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

            //RANGES
            if(isset($p_query['ranges']) && \is_array($p_query['ranges'])){

                if(isset($p_query['ranges'][$definition['id']])){

                    if(in_array($definition['id'], array('objTime', 'objDateAdd', 'objDateUpdated', 'objDateErased', 'objDateIndexed', 'objPage1000', 'objPage10000'))){

                        $validRanges[$definition['id']] = (array)$p_query['ranges'][$definition['id']];
                    }else{

                        $validRanges['objData.' . $definition['id']] = (array)$p_query['ranges'][$definition['id']];
                    }
                }
            }

            //AGGREGATIONS
            if(isset($p_query['aggregations']) && \is_array($p_query['aggregations'])){

                if(isset($p_query['aggregations'][$definition['id']])){

                    if(in_array($definition['id'], array('objActive', 'objUserAdd', 'objUserUpdated', 'objErased', 'objUserErased', 'objIndexed', 'objState'))){

                        $validAggregations[$definition['id']] = (array)$p_query['aggregations'][$definition['id']];
                    }else{

                        $validAggregations['objData.' . $definition['id']] = (array)$p_query['aggregations'][$definition['id']];
                    }
                }
            }
        }
        
        $searchQuery                = array();
        $searchQuery['rows']        = $result['rows'];
        $searchQuery['page']        = $result['page'];
        $searchQuery['filters']     = $validFilters;
        $searchQuery['ranges']      = $validRanges;
        $searchQuery['facets']      = $validAggregations;
        $searchQuery['keyword']     = (count($validSearchFields) > 0) ? ((isset($p_query['keyword'])) ? $p_query['keyword'] : '*') : '*';
        $searchQuery['fields']      = (count($validSearchFields) > 0) ? $validSearchFields : array('*');
        
        //ORDERS
        if(isset($p_query['orders']) && \is_array($p_query['orders']) && count($p_query['orders']) > 0){

            $searchQuery['orders']  = $p_query['orders'];
        }

        //HARD ORDERS (OPTIONS)
        if(isset($this->options['hardorders'])){

            if(!isset($searchQuery['orders'])){

                $searchQuery['orders']              = array();
            }

            foreach($this->options['hardorders'] as $field=>$order){

                $searchQuery['orders'][$field]      = $order; 
            }
        }

        //HARD ORDERS (PARAMS)
        if(isset($p_query['hardorders'])){

            if(!isset($searchQuery['orders'])){

                $searchQuery['orders']              = array();
            }

            foreach($p_query['hardorders'] as $field=>$order){

                $searchQuery['orders'][$field]      = $order; 
            }
        }
        
        $searchResult               = $this->dataEngine->searchObjects($result["model"]["id"], $searchQuery);
        
        if($searchResult != false){

            $result['totals']       = $searchResult['totals'];
            $result['pages']        = ($result['rows'] > 0) ? ceil($result['totals']/$result['rows']) : 0;
            $result['objects']      = array();
            $result['facets']       = (isset($searchResult['facets'])) ? $searchResult['facets'] : array();

            $rownum = ($result['page'] * $result['rows']) - ($result['rows'] - 1);

            if(isset($searchResult['objects']) && is_array($searchResult['objects'])){
                
                foreach($searchResult['objects'] as $row){

                    $rowTmp                         = $this->normalizeObjectData($row);
        
                    $rowTmp['num']                  = $rownum++;
        
                    $result['objects'][]            = $rowTmp;
                }
            }
        }

        return $result;
    }

    public function getModelObject($p_model, $p_id){

        $result                                         = array();

        $result["model"]                                = $this->getModelData($p_model);
        $result["fields"]                               = $this->getModelDefinitions($p_model, $result["model"]);
        $result["fieldsGroups"]                         = $this->getModelDefinitionsGroups();
        $result['objects']                              = array();
        
        $objectResult                                   = $this->dataEngine->getObject($result["model"]["id"], $p_id);
        
        if($objectResult != false){

            
            $result['objects'][]                         = $this->normalizeObjectData($objectResult);
        }

        return $result;
    }

    public function setModelObjectState($p_model, $p_id, $p_state, $p_data){

        //TODO: VALIDACION DE ESTADOS

        return $this->dataEngine->setState($p_model, $p_id, $p_state, $p_data);
    }

    public function editModelObjectData($p_model, $p_id, $p_data){
        
        return $this->dataEngine->editObject($p_model, $p_id, $p_data);
    }

    public function addModelObjectData($p_model, $p_data){

        return $this->dataEngine->addObject($p_model, $p_data);
    }

    public function getFirstNameField($p_model){

        $result                         = "_id";
        $modelData                      = $this->getModelData($p_model);
        $modelDataDefinitions           = $this->getModelDefinitions($p_model, $modelData);

        foreach($modelDataDefinitions as $definition){

            if($definition['isName'] == "1"){

                $result = $definition['id'];
                break;
            }
        }

        return $result;
    }

    public function getNameFields($p_model){

        $result                         = array("_id");
        $modelData                      = $this->getModelData($p_model);
        $modelDataDefinitions           = $this->getModelDefinitions($p_model, $modelData);

        $index                          = 0;
        foreach($modelDataDefinitions as $definition){

            if($definition['isName'] == "1"){


                $result[$index]         = $definition['id'];
                
                $index                  += 1;
            }
        }

        return $result;
    }

    public function getImageField($p_model){

        $result                         = "image";
        $modelData                      = $this->getModelData($p_model);
        $modelDataDefinitions           = $this->getModelDefinitions($p_model, $modelData);

        foreach($modelDataDefinitions as $definition){

            if($definition['isImage'] == "1"){

                $result = $definition['id'];
                break;
            }
        }

        return $result;
    }

    public function getIconField($p_model){

        $result                         = "icon";

        return $result;
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

    public function getModelData($p_model){

        //LOGICA DE RECUPERACION DE MODEL DATA
        $modelDataTmp                       = $this->dataEngine->getModel($p_model);
        
        $result                             = array();
        $result['id']                       = $modelDataTmp['modId'];
        $result['parent']                   = $modelDataTmp['modParent'];
        $result["type"]                     = $modelDataTmp['modType'];
        $result["idStrategy"]               = $modelDataTmp['modIdStrategy'];;
        $result["partitionMode"]            = $modelDataTmp['modPartitionMode'];;
        $result["uiOptions"]                = $modelDataTmp['modUiOptions'];
        $result["indexOptions"]             = $modelDataTmp['modIndexOptions'];
        $result["cacheOptions"]             = $modelDataTmp['modCacheOptions'];
        $result["versionsOptions"]          = $modelDataTmp['modVersionsOptions'];
        $result["statesOptions"]            = $modelDataTmp['modStatesOptions'];

        return $result;
    }

    public function getModelDefinitionsGroups(){
        $result                             = array();

        $database                           = new Mysql($this->getDI());

        $selectOptions                      = array();
        $selectOptions['rows']              = 1000;
        $selectOptions['offset']            = 0;

        $selectResult                       = $database->select("fields_groups",$selectOptions);

        if(is_array($selectResult)){

            foreach($selectResult as $fieldGroup){

                $result[$fieldGroup['flgId']] = $fieldGroup['flgName'];
            }
        }
        
        return $result;
    }

    public function getDataFieldDefinitions($p_field){

        $result                                             = array();

        $definition                                         = $this->dataEngine->getField($p_field);
        
        $result["id"]                                       = $definition['dafId'];
        $result["type"]                                     = $definition['typId'];
        $result["group"]                                    = "tostatedata";
        $result["defaultValue"]                             = $definition['dafDefaultValue'];

        if($definition['typId'] == "json" && ($definition['dafDefaultValue'] == "" || $definition['dafDefaultValue'] == "{}")){

            $result["defaultValue"]                         = new \stdClass();

        }elseif(($definition['typId'] == "objectsr" || $definition['typId'] == "tags" || $definition['typId'] == "options") && ($definition['dafDefaultValue'] == "" || $definition['dafDefaultValue'] == "[]")){

            $result["defaultValue"]                         = array();
        }

        $result["order"]                                    = 1;
        $result["isName"]                                   = false;
        $result["isImage"]                                  = false;
        $result["isRelation"]                               = false;
        $result["isState"]                                  = false;
        $result["uiOptions"]                                = $definition['dafUiOptions'];
        $result["indexOptions"]                             = $definition['dafIndexOptions'];
        $result['typeOptions']                              = $definition['dafTypOptions'];
        $result['validationOptions']                        = $definition['dafTypValidationOptions'];
        $result['attachFileOptions']                        = $definition['dafAttachFileOptions'];

        return $result;
    }

    public function getModelDefinitions($p_model, $p_modelData){
        
        $result             = array();

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
        
        ****/
        
        $notRenderableFields    = array();
        if(isset($this->options['notRenderableFields'])){

            $notRenderableFields = $this->options['notRenderableFields'];
        }

        //OBJECTS FIELDS
        $definitions            = $this->dataEngine->getDefinition($p_model, null);

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

                    $result[$definitionsRow["id"]]  = $definitionsRow;
                }
            }
        }
        
        //RELATIONS
        $relations          = $this->dataEngine->getModelRelations($p_model, "IN");

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
                    
                    $result[$relationRow["id"]]     = $relationRow;
                }
            }
        }
        
        //STATE
        if(property_exists($p_modelData['statesOptions'], 'stateable') && $p_modelData['statesOptions']->stateable == true){
            
            //STATE
            $fieldId                                            = "objState";
            
            if(!in_array($fieldId, $notRenderableFields)){

                
                $stateRow                                       = array();
                $stateRow["id"]                                 = $fieldId;
                $stateRow["type"]                               = "text";
                $stateRow["group"]                              = "data";
                $stateRow["defaultValue"]                       = $definition['defDafDefaultValue'];

                if(property_exists($p_modelData['statesOptions'], 'defaultState')){

                    $stateRow["defaultValue"]                   = $p_modelData['statesOptions']->defaultState;

                }

                $stateRow["order"]                              = 200;
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

                $result[$stateRow["id"]]    = $stateRow;
            }


            //STATELOGS
            
            $fieldId                                            = "objStatesLog";
            
            if(!in_array($fieldId, $notRenderableFields)){
                
                $stateRow                                       = array();
                $stateRow["id"]                                 = $fieldId;
                $stateRow["type"]                               = "json";
                $stateRow["group"]                              = "data";
                $stateRow["defaultValue"]                       = array();
                $stateRow["order"]                              = 201;
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

                $result[$stateRow["id"]]    = $stateRow;
            }
        }
        
        usort($result, function ($a, $b){

            $aOrder = (int)$a["order"];
            $bOrder = (int)$b["order"];
            
            if($aOrder == $bOrder){

                return 0;
            }

            return ($aOrder < $bOrder) ? -1 : 1;
        });
        
        $orderedResult = array();

        foreach($result as $definition){

            $orderedResult[$definition["id"]] = $definition;
        }

        return $orderedResult;
    }

    /*public function getDataFields(){

        return $this->modelDataDefinitions;
    }

    public function getData($p_query){
        
        if(is_array($p_query)){
            $result             = array();
            $result['page']     = (isset($p_query['page'])) ? $p_query['page'] : 1;
            $result['rows']     = (isset($p_query['rows'])) ? $p_query['rows'] : 10;

            $validFilters               = array();
            //$validSearchFields        = array('_id');

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
    */
}