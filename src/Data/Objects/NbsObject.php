<?php
/**
 * Created by PhpStorm.
 * UserMenu: juanma
 * Date: 27/07/16
 * Time: 03:23 PM
 */

namespace Nubesys\Data\Objects;

use Nubesys\Core\Common;
use Nubesys\Data\Objects\Adapters\Mysql;
use Nubesys\Data\Objects\Adapters\Elastic;
use Nubesys\Data\Objects\Model;
use Nubesys\Data\Objects\Definition;

class NbsObject extends Common
{

    protected $database;
    protected $elastic;

    protected $model;
    protected $definition;

    public function __construct($p_di, Mysql $p_database, Elastic $p_elastic, Model $p_model, Definition $p_definition)
    {
        parent::__construct($p_di);

        $this->database = $p_database;
        $this->elastic  = $p_elastic;

        $this->model        = $p_model;
        $this->definition   = $p_definition;
    }

    private function getRelationId($p_model, $p_leftId, $p_rightId){

        return $p_model . '_' . $p_leftId . '_' . $p_rightId;
    }

    private function getRelationsObjects($p_model, $p_id){

        $result = array();

        $modelData              = $this->model->get($p_model);

        $tableName = $this->model->getModelObjectsTableName($p_model, 'RELATION');

        //TODO : Validacion si la tabla existe

        $selectOptions                  = array();
        $selectOptions['conditions']    = "modId = '" . $p_model . "' AND relLeftObjId = '" . $p_id . "'";

        $selectResult = $this->database->select($tableName,$selectOptions);

        if(is_array($selectResult)){

            foreach($selectResult as $object){

                $result[$object['_id']] = $object;
            }

        }

        return $result;
    }

    private function hasRelation($p_model, $p_id){

        $result = false;

        $tableName = $this->model->getModelObjectsTableName($p_model, 'RELATION');

        //TODO : Validacion si la tabla existe

        $selectOptions                  = array();
        $selectOptions['conditions']    = "_id = '" . $p_id . "'";

        $selectResult = $this->database->selectOne($tableName,$selectOptions);

        if(is_array($selectResult)){

            $result = true;
        }

        return $result;
    }

    private function addRelation($p_model, $p_leftId, $p_rightId, $p_data){

        $result                 = false;

        $insertData             = array();

        $modelData              = $this->model->get($p_model);
        $modelDefinition        = array();
        //TODO ver definicion de MOdelos
        //$modelDefinition        = $this->definition->get($p_model, null);
        
        $id                     = $this->getRelationId($p_model, $p_leftId, $p_rightId);

        $insertData['_id']              = $id;
        $insertData['modId']            = $p_model;
        $insertData['relLeftObjId']     = $p_leftId;
        $insertData['relRightObjId']    = $p_rightId;
        $insertData['objTime']          = \Nubesys\Core\Utils\Utils::getTimeStamp($this->getDI());
        $insertData['objDateAdd']       = \Nubesys\Core\Utils\Utils::getDateTime($this->getDI());
        $insertData['objDateUpdated']   = $insertData['objDateAdd'];

        //TODO: UserMenu Add, UserMenu Update, Function getActualUser

        //TODO: Agregar Version Strategy en modelos, none | secuencial | store

        //TODO: Agregar Order Strategy en modelos, none | time | manual | ambos
        if (property_exists($p_data, 'objOrder')) {

            $insertData['objOrder'] = $p_data->objOrder;
        } else {

            $insertData['objOrder'] = \Nubesys\Core\Utils\Utils::getSequenceNextValue($this->getDI(), $p_model . '_objects_orders');
        }

        $insertData['objPage1000'] = \Nubesys\Core\Utils\Utils::getPageSequence($this->getDI(), $p_model . '_page1000', 1000);
        $insertData['objPage10000'] = \Nubesys\Core\Utils\Utils::getPageSequence($this->getDI(), $p_model . '_page10000', 10000);

        /*
        //TODO : Parche para Trackaff
        if($p_model == 'tkfunnels'){
            
            $insertData['objPartitionIndex'] = \Nubesys\Platform\Util\Utils::getWeekOfYearKey($this->getDI());
        }else{
            
            $insertData['objPartitionIndex'] = \Nubesys\Platform\Util\Utils::getYearWeekKey($this->getDI());
        }
        */

        $insertData['objPartitionIndex'] = $this->getPartitionIndex($modelData);

        if (property_exists($p_data, 'objActive')) {

            $insertData['objActive'] = $p_data->objActive;
        } else {

            $insertData['objActive'] = true;
        }

        if (count($modelDefinition) > 0) {

            $_dataTemp = array();

            foreach ($modelDefinition as $modelField) {

                $fieldId = $modelField['dafId'];

                if (property_exists($p_data, $fieldId)) {

                    $_dataTemp[$fieldId] = $p_data->$fieldId;
                } else {

                    $_dataTemp[$fieldId] = $modelField['defDafDefaultValue'];
                }
            }

            $insertData['objData'] = json_encode($this->encodeUtf8($_dataTemp));

        } else {

            $insertData['objData'] = json_encode($this->encodeUtf8(new \stdClass()));
        }
        
        $tableName = $this->model->getModelObjectsTableName($p_model, 'RELATION');
        
        $insertObjectResult = $this->database->insert($tableName, $insertData);
        
        if ($insertObjectResult) {

            $result = $id;
            
        } else {

            //TODO : No se pudo insertar
        }
        
        return $result;
    }

    public function editRelation($p_model, $p_id, $p_data){
        
        $result = false;

        $updateData = array();
        $indexOldData = array();
        $indexNewData = array();
        $indexData = array();

        $modelData              = $this->model->get($p_model);
        $modelDefinition        = $this->definition->get($p_model, null);
        
        $objectData             = $this->get($p_model, $p_id);

        if($objectData){

            $indexOldData                   = \Nubesys\Core\Utils\Struct::toArray($objectData);

            $updateData['objDateUpdated']   = \Nubesys\Core\Utils\Utils::getDateTime($this->getDI());

            //TODO: UserMenu Add, UserMenu Update, Function getActualUser

            //TODO: Agregar Version Strategy en modelos, none | secuencial | store

            //TODO: Agregar Order Strategy en modelos, none | time | secuencial | manual | ambos
            if(property_exists($p_data,'objOrder')){

                $updateData['objOrder']   = $p_data->objOrder;
            }

            if(property_exists($p_data,'objActive')){

                $updateData['objActive']   = $p_data->objActive;
            }

            foreach($updateData as $key=>$value){

                $indexNewData[$key] = $value;
            }

            $indexNewData['objData'] = array();

            if($modelDefinition && count($modelDefinition) > 0){

                $_dataTemp = array();

                foreach ($modelDefinition as $modelField) {

                    $fieldId = $modelField['dafId'];

                    if (property_exists($p_data, $fieldId)) {

                        $_dataTemp[$fieldId] = $p_data->$fieldId;

                        $indexNewData['objData'][$fieldId] = $p_data->$fieldId;
                    }
                }

                $updateData['objData'] = json_encode((object)array_replace_recursive((array)$objectData['objData'],(array)$this->encodeUtf8($_dataTemp)));

            }else{

                $updateData['objData'] = json_encode(new \stdClass());
            }

            $tableName = $this->model->getModelObjectsTableName($p_model, 'RELATION');

            $updateConditions = "_id = '" . $p_id . "'";

            $updateResult = $this->database->update($tableName, $updateData, $updateConditions);

            if($updateResult){

                $result = $p_id;
            }else{

                //TODO : NOT Updated
            }

        }else{

            //TODO : No se pudo encontrar el objeto para editarlo
        }

        return $result;
    }

    private function removeObjectRelations($p_model, $p_leftId){

        $result = false;

        $tableName = $this->model->getModelObjectsTableName($p_model, 'RELATION');

        $deleteConditions = "modId = '" . $p_model . "' AND relLeftObjId = '" . $p_leftId . "'";

        $deleteResult = $this->database->delete($tableName, $deleteConditions);

        if($deleteResult){

            $result = $p_id;
        }else{

            //TODO : NOT Updated
        }

        return $result;
    }

    private function removeRelation($p_model, $p_id){

        $result = false;

        $tableName = $this->model->getModelObjectsTableName($p_model, 'RELATION');

        $deleteConditions = "_id = '" . $p_id . "'";

        $deleteResult = $this->database->delete($tableName, $deleteConditions);

        if($deleteResult){

            $result = $p_id;
        }else{

            //TODO : NOT Updated
        }

        return $result;
    }

    private function setRelations($p_id, $p_relationsData){
        
        $relLeftId = $p_id;

        $addRelations = array();
        $editRelations = array();
        $removeRelations = array();

        foreach($p_relationsData as $relModelId => $relobjects){

            $currentRelationsObjects = $this->getRelationsObjects($relModelId, $relLeftId);

            $currentRelationsObjectsIds = (is_array($currentRelationsObjects)) ? array_keys($currentRelationsObjects) : array();

            foreach($relobjects as $data){

                $relObjectData = new \stdClass();
                $relRightId = $data;
                if(is_object($data)){

                    //TODO
                    //procesar los datos
                    //$relRightId seria la clave
                }

                $relObjectId = $this->getRelationId($relModelId, $relLeftId, $relRightId);

                $_relTmp = array();
                $_relTmp['relModelId']      = $relModelId;
                $_relTmp['relLeftId']       = $relLeftId;
                $_relTmp['relRightId']      = $relRightId;
                $_relTmp['relObjectData']   = $relObjectData;

                //EDIT OR ADD RELATIONS
                if(in_array($relObjectId, $currentRelationsObjectsIds)){

                    $editRelations[$relObjectId] = $_relTmp;
                }else{

                    $addRelations[$relObjectId] = $_relTmp;
                }
            }
            
            //REMOVE RELATIONS
            foreach($currentRelationsObjectsIds as $relCurrentObjectId){

                if(!in_array($relCurrentObjectId, array_keys($editRelations))){

                    $_relTmp = array();
                    $_relTmp['relModelId']     = $relModelId;
                    
                    $removeRelations[$relCurrentObjectId] = $_relTmp;
                }
            }
        }

        //REMOVE
        foreach($removeRelations as $relObjectId => $relData){

            $this->removeRelation($relData['relModelId'], $relObjectId);
        }

        //EDIT
        foreach($editRelations as $relObjectId => $relData){

            $this->editRelation($relData['relModelId'], $relObjectId, $relData['relObjectData']);
        }

        //ADD
        foreach($addRelations as $relObjectId => $relData){

            $this->addRelation($relData['relModelId'], $relData['relLeftId'], $relData['relRightId'], $relData['relObjectData']);
        }
    }

    public function add($p_model, $p_data){
        
        $result = false;

        //TODO : Validacion IsTable Exsist

        $insertData                 = array();
        $indexData                  = array();
        $relationsData              = array();

        $modelData                  = $this->model->get($p_model);
        $modelDefinition            = $this->definition->get($p_model, null);
        $modelRelations             = $this->model->getRelations($p_model, 'IN');
        
        //ID DEL OBJETO
        $id                         = $this->getIdValue($p_data, $modelData, $modelDefinition);
        
        if($id !== false){

            if(!$this->has($p_model, $id)) {
                
                $insertData['_id']              = $id;
                $insertData['modId']            = $p_model;
                $insertData['objTime']          = \Nubesys\Core\Utils\Utils::getTimeStamp($this->getDI());
                $insertData['objDateAdd']       = \Nubesys\Core\Utils\Utils::getDateTime($this->getDI());
                $insertData['objDateUpdated']   = $insertData['objDateAdd'];

                //TODO: UserMenu Add, UserMenu Update, Function getActualUser

                //TODO: Agregar Version Strategy en modelos, none | secuencial | store

                //TODO: Agregar Order Strategy en modelos, none | time | manual | ambos
                if (isset($p_data['objOrder'])){

                    $insertData['objOrder']     = $p_data['objOrder'];
                } else {

                    $insertData['objOrder']     = \Nubesys\Core\Utils\Utils::getSequenceNextValue($this->getDI(), $p_model . '_objects_orders');
                }

                $insertData['objPage1000']      = \Nubesys\Core\Utils\Utils::getPageSequence($this->getDI(), $p_model . '_page1000', 1000);
                $insertData['objPage10000']     = \Nubesys\Core\Utils\Utils::getPageSequence($this->getDI(), $p_model . '_page10000', 10000);

                //STATES
                if(is_object($modelData['modStatesOptions']) && property_exists($modelData['modStatesOptions'], "stateable")){

                    if($modelData['modStatesOptions']->stateable == true){

                        $state                  = "new";

                        if(isset($p_data['objState'])){

                            $insertData['objState'] = $p_data['objState'];
                            $state                  = $p_data['objState'];
                        }else{

                            if(property_exists($modelData['modStatesOptions'], "defaultState")){

                                $insertData['objState'] = $modelData['modStatesOptions']->defaultState;
                                $state                  = $insertData['objState'];
                            }
                        }

                        $stateDataTmp                   = array();
                        $stateDataTmp['state']          = $insertData['objState'];
                        $stateDataTmp['date']           = $insertData['objDateAdd'];
                        $stateDataTmp['user']           = "";
                        $stateDataTmp['data']           = (isset($p_data['stateData']) && is_object($p_data['stateData'])) ? $p_data['stateData'] : new \stdClass();

                        $stateDataLogs                  = array();
                        $stateDataLogs[]                = $stateDataTmp;

                        $insertData['objStatesLog']     = $stateDataLogs;
                        
                    }
                }

                $insertData['objPartitionIndex'] = $this->getPartitionIndex($modelData);

                if (isset($p_data['objActive'])) {

                    $insertData['objActive'] = $p_data['objActive'];
                } else {

                    $insertData['objActive'] = true;
                }
                
                $indexData = $insertData;

                //INSERT IN OBJECT TABLE
                $indexData['objData'] = array();
                
                if (count($modelDefinition) > 0) {

                    $_dataTemp = array();

                    foreach ($modelDefinition as $modelField) {
                        
                        $fieldId = $modelField['dafId'];
                        
                        if (isset($p_data[$fieldId])) {

                            if($modelField['typSaveAs'] == "JSON"){
                                
                                if(\Nubesys\Core\Utils\Struct::isValidJson($p_data[$fieldId])){
    
                                    $_dataTemp[$fieldId] = json_decode($p_data[$fieldId]);
                                }else{
    
                                    $_dataTemp[$fieldId] = $p_data[$fieldId];
                                }
                                
                            }else{
    
                                $_dataTemp[$fieldId] = $p_data[$fieldId];
                            }

                            $indexData['objData'][$fieldId] = $_dataTemp[$fieldId];
                        } else {

                            if($modelField['typId'] == "json"){

                                $_dataTemp[$fieldId] = json_decode("{}");
                                
                            }else if($modelField['typId'] == "objectsr" || $modelField['typId'] == "tags" || $modelField['typId'] == "options"){

                                $_dataTemp[$fieldId] = json_decode("[]");

                            }else{

                                $_dataTemp[$fieldId] = $modelField['defDafDefaultValue'];
                            }
                        }
                    }

                    foreach ($modelDefinition as $modelField) {
                    
                        $fieldId = $modelField['dafId'];
    
                        if (isset($p_data[$fieldId])) {
    
                            $indexNewData['objData'][$fieldId] = $p_data[$fieldId];
                        }
                    }
                    
                    $insertData['objData'] = json_encode($_dataTemp, JSON_UNESCAPED_UNICODE);
                    
                } else {

                    $insertData['objData'] = json_encode(new \stdClass());
                }

                if(isset($insertData['objStatesLog'])){

                    $insertData['objStatesLog'] = json_encode($insertData['objStatesLog'], JSON_UNESCAPED_UNICODE);
                }
                
                $tableName = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);
                
                $insertObjectResult = $this->database->insert($tableName, $insertData);              
                
                if ($insertObjectResult) {

                    //SET RELATIONS
                    $indexData['relData'] = array();
                    
                    if (count($modelRelations) > 0) {

                        foreach ($modelRelations as $modelRelation) {
                            
                            $_relationsDataTmp = array();

                            $fieldId = 'rel_' . $modelRelation['modId'];

                            $relationsData[$modelRelation['modId']] = array();

                            if (isset($p_data[$fieldId])) {
                                //TODO ojo cuando halla datos de relacion
                                $indexData['relData'][$fieldId] = $p_data[$fieldId];

                                $relationsData[$modelRelation['modId']] = $p_data[$fieldId];
                            }
                        }
                        
                        $this->setRelations($id, $relationsData);  
                    }

                    $result = $id;

                    $this->indexAdd($modelData, $modelDefinition, $modelRelations, $id, $indexData);
                    
                } else {

                    //TODO : No se pudo insertar
                }

            }else{

                //TODO : ID Repetido

                $result = $id;
            }

        }else{

            //TODO : No se pudo definir el ID
        }

        return $result;
    }

    public function addMultiple($p_model, $objects){
        
        $result = false;

        $modelData              = $this->model->get($p_model);
        $modelDefinition        = $this->definition->get($p_model, null);
        $modelRelations         = $this->model->getRelations($p_model, 'IN');

        $objDateAdd             = \Nubesys\Core\Utils\Utils::getDateTime($this->getDI());
        $objTime                = \Nubesys\Core\Utils\Utils::getTimeStamp($this->getDI());
        $objDateUpdated         = $objDateAdd;

        $objPage1000            = \Nubesys\Core\Utils\Utils::getPageSequence($this->getDI(), $p_model . '_page1000', 1000);
        $objPage10000           = \Nubesys\Core\Utils\Utils::getPageSequence($this->getDI(), $p_model . '_page10000', 10000);

        $objPartitionIndex      = $this->getPartitionIndex($modelData);

        $insertData             = array();
        $indexData              = array();
        $relationsData          = array();

        foreach($objects as $data){

            $insertDataTmp          = array();
            $indexDataTmp           = array();
            $relationsDataTmp       = array();

            $id                     = $this->getIdValue($data, $modelData, $modelDefinition);

            if($id !== false){

                $insertDataTmp['_id']               = $id;
                $insertDataTmp['modId']             = $p_model;
                $insertDataTmp['objTime']           = $objTime;
                $insertDataTmp['objDateAdd']        = $objDateAdd;
                $insertDataTmp['objDateUpdated']    = $objDateUpdated;

                if (property_exists($data, 'objOrder')) {

                    $insertDataTmp['objOrder']      = $data->objOrder;
                } else {

                    $insertDataTmp['objOrder']      = 0;
                }

                $insertDataTmp['objPage1000']       = $objPage1000;
                $insertDataTmp['objPage10000']      = $objPage10000;

                $insertDataTmp['objPartitionIndex'] = $objPartitionIndex;

                if (property_exists($data, 'objActive')) {

                    $insertDataTmp['objActive'] = $data->objActive;
                } else {

                    $insertDataTmp['objActive'] = true;
                }

                $indexDataTmp                   = $insertDataTmp;

                $indexDataTmp['objData']        = array();

                if (count($modelDefinition) > 0) {

                    $_dataTemp = array();

                    foreach ($modelDefinition as $modelField) {

                        $fieldId = $modelField['dafId'];
                        
                        if (property_exists($data, $fieldId)) {

                            $_dataTemp[$fieldId] = $data->$fieldId;
                        } else {

                            $_dataTemp[$fieldId] = $modelField['defDafDefaultValue'];
                        }

                        $indexDataTmp['objData'][$fieldId] = $_dataTemp[$fieldId];
                    }


                    $insertDataTmp['objData'] = json_encode($this->encodeUtf8($_dataTemp));

                } else {

                    $insertDataTmp['objData'] = json_encode($this->encodeUtf8(new \stdClass()));
                }

                $insertData[]               = $insertDataTmp;

                $indexDataTmp['relData']    = array();

                if (count($modelRelations) > 0) {

                    foreach ($modelRelations as $modelRelation) {
                        
                        $_relationsDataTmp = array();

                        $fieldId = 'rel_' . $modelRelation['modId'];

                        $relationsData[$modelRelation['modId']] = array();

                        if (property_exists($data, $fieldId)) {
                            //TODO ojo cuando halla datos de relacion
                            $indexDataTmp['relData'][$fieldId] = $data->$fieldId;

                            $relationsDataTmp[$modelRelation['modId']] = $data->$fieldId;
                        }
                    }
                    
                    $relationsData[$id]     = $relationsDataTmp;  
                }

                $indexData[]                = $indexDataTmp;

            }else{

                //TODO : No se pudo definir el ID
            }
        }

        if(count($insertData) > 0){

            $tableName = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);

            $insertObjectsResult = $this->database->insertBulk($tableName, $insertData);

            if($insertObjectsResult && count($relationsData)>0){


                //realtion add

                if(count($indexData)>0){

                    //bulk
                }
            }
        }

        $insertData         = array();
        $indexData          = array();
        $relationsData      = array();
        
        $id                     = $this->getIdValue($p_data, $modelData, $modelDefinition);
        
        if($id !== false){

            if(!$this->has($p_model, $id)) {
                
                $insertObjectResult = $this->database->insert($tableName, $insertData);              
                
                if ($insertObjectResult) {

                    //SET RELATIONS
                    $indexData['relData'] = array();
                    
                    if (count($modelRelations) > 0) {

                        foreach ($modelRelations as $modelRelation) {
                            
                            $_relationsDataTmp = array();

                            $fieldId = 'rel_' . $modelRelation['modId'];

                            $relationsData[$modelRelation['modId']] = array();

                            if (property_exists($p_data, $fieldId)) {
                                //TODO ojo cuando halla datos de relacion
                                $indexData['relData'][$fieldId] = $p_data->$fieldId;

                                $relationsData[$modelRelation['modId']] = $p_data->$fieldId;
                            }
                        }
                        
                        $this->setRelations($id, $relationsData);  
                    }

                    $result = $id;

                    $this->indexAdd($modelData, $modelDefinition, $modelRelations, $id, $indexData);
                    
                } else {

                    //TODO : No se pudo insertar
                }

            }else{

                //TODO : ID Repetido

                $result = $id;
            }

        }else{

            //TODO : No se pudo definir el ID
        }

        return $result;
    }

    public function state($p_model, $p_id, $p_state, $p_data){

        $stateData                      = array();
        $stateData['objState']          = $p_state;
        $stateData['objStateData']      = $p_data;
        
        return $this->edit($p_model, $p_id, $stateData);
    }

    public function edit($p_model, $p_id, $p_data){
        
        $result = false;

        $cacheKey       = 'data_object_' . $p_id;

        //TODO : Validacion IsTable Exsist

        $updateData             = array();
        $relationsData          = array();
        $indexOldData           = array();
        $indexNewData           = array();
        $indexData              = array();

        $modelData              = $this->model->get($p_model);
        $modelDefinition        = $this->definition->get($p_model, null);
        $modelRelations         = $this->model->getRelations($p_model, 'IN');

        $objectData             = $this->get($p_model, $p_id);
        
        if($objectData){
            
            $indexOldData                   = \Nubesys\Core\Utils\Struct::toArray($objectData);

            $updateData['objDateUpdated']   = \Nubesys\Core\Utils\Utils::getDateTime($this->getDI());

            //TODO: UserMenu Add, UserMenu Update, Function getActualUser

            //TODO: Agregar Version Strategy en modelos, none | secuencial | store

            //TODO: Agregar Order Strategy en modelos, none | time | secuencial | manual | ambos

            if(isset($p_data['objOrder'])){

                $updateData['objOrder']   = $p_data['objOrder'];
            }

            //STATES
            if(is_object($modelData['modStatesOptions']) && property_exists($modelData['modStatesOptions'], "stateable")){

                if($modelData['modStatesOptions']->stateable == true){

                    $state                  = "new";

                    if(isset($p_data['objState'])){

                        $updateData['objState'] = $p_data['objState'];
                        $state                  = $p_data['objState'];
                    }else{

                        if(property_exists($modelData['modStatesOptions'], "defaultState")){

                            $updateData['objState'] = $modelData['modStatesOptions']->defaultState;
                            $state                  = $updateData['objState'];
                        }
                    }

                    $stateDataTmp                       = array();
                    $stateDataTmp['state']              = $updateData['objState'];
                    $stateDataTmp['date']               = $updateData['objDateUpdated'];
                    $stateDataTmp['user']               = "";

                    if(isset($p_data['objStateData'])){

                        $updateData['objStateData']     = $p_data['objStateData'];
                    
                        if(is_object($p_data['objStateData'])){

                            $stateDataTmp['data']           = json_decode($p_data['objStateData']);
                        }

                        if(is_string($p_data['objStateData'])){

                            $stateDataTmp['data']           = json_decode($p_data['objStateData']);
                        }

                    }else{

                        $stateDataTmp['data']           = new \stdClass();
                    }
                    
                    
                    if(isset($objectData['objStatesLog'])){

                        $objectData['objStatesLog'][]   = $stateDataTmp;

                        $updateData['objStatesLog']     = $objectData['objStatesLog'];

                    }else{

                        $updateData['objStatesLog']     = array($stateDataTmp);
                    }
                }
            }

            if(isset($p_data['objActive'])){

                $updateData['objActive']   = $p_data['objActive'];
            }

            foreach($updateData as $key=>$value){

                $indexNewData[$key] = $value;
            }

            $indexNewData['objData'] = array();

            if(count($modelDefinition) > 0){

                $_dataTemp = array();

                foreach ($modelDefinition as $modelField) {
                    
                    $fieldId = $modelField['dafId'];
                    
                    if (isset($p_data[$fieldId])) {
                        
                        if($modelField['typSaveAs'] == "JSON"){
                            
                            if(\Nubesys\Core\Utils\Struct::isValidJson($p_data[$fieldId])){
                                
                                $_dataTemp[$fieldId] = json_decode($p_data[$fieldId]);
                            }else{

                                $_dataTemp[$fieldId] = $p_data[$fieldId];
                            }
                            
                        }else{

                            $_dataTemp[$fieldId] = $p_data[$fieldId];
                        }

                        $indexNewData['objData'][$fieldId] = $_dataTemp[$fieldId];

                    }else{
                        /*
                        if($modelField['typId'] == "json"){

                            $_dataTemp[$fieldId] = json_decode("{}");
                            
                        }else if($modelField['typId'] == "objectsr" || $modelField['typId'] == "tags" || $modelField['typId'] == "options"){

                            $_dataTemp[$fieldId] = json_decode("[]");

                        }else{

                            $_dataTemp[$fieldId] = $modelField['defDafDefaultValue'];
                        }

                        $indexNewData['objData'][$fieldId] = $_dataTemp[$fieldId];
                        */
                    }

                    
                }

                if(isset($updateData['objStatesLog'])){

                    $updateData['objStatesLog'] = json_encode($updateData['objStatesLog'], JSON_UNESCAPED_UNICODE);
                }

                if(isset($updateData['objStateData'])){

                    $updateData['objStateData'] = json_encode($updateData['objStateData'], JSON_UNESCAPED_UNICODE);
                }
                
                $updateData['objData'] = json_encode(array_replace((array)$objectData['objData'],(array)$_dataTemp),JSON_UNESCAPED_UNICODE);
                //$updateData['objData'] = json_encode((object)array_replace_recursive((array)$objectData['objData'],(array)$this->encodeUtf8($_dataTemp)));

            }else{

                $updateData['objData'] = json_encode(new \stdClass());
            }
            
            $tableName = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);

            $updateConditions = "_id = '" . $p_id . "'";
            
            $updateResult = $this->database->update($tableName, $updateData, $updateConditions);

            if($updateResult){

                //SET RELATIONS
                $indexNewData['relData'] = array();
                
                if (count($modelRelations) > 0) {

                    foreach ($modelRelations as $modelRelation) {
                        
                        $_relationsDataTmp = array();

                        $fieldId = 'rel_' . $modelRelation['modId'];

                        $relationsData[$modelRelation['modId']] = array();

                        if (isset($p_data[$fieldId])) {
                            //TODO: ojo cuando halla datos de objeto de relacion
                            $indexNewData['relData'][$fieldId] = $p_data[$fieldId];

                            $relationsData[$modelRelation['modId']] = $p_data[$fieldId];
                        }
                    }
                    
                    $this->setRelations($p_id, $relationsData);  
                }
                
                $result = $p_id;

                $indexData = array_replace_recursive($indexOldData,$indexNewData);

                $this->indexUpdate($modelData, $modelDefinition, $modelRelations, $p_id, $indexData);

                $this->deleteObjectCache($p_id);
            }else{

                //TODO : NOT Updated
            }

            //TODO : Relaciones
            //TODO : Collections
            //TODO : SubObjetos

            //INDEXACION DE OBJETO

            //TODO : funcion Indexed para el campo _indexed y _user_indexed

        }else{

            //TODO : No se pudo encontrar el objeto para editarlo
        }

        return $result;
    }

    public function remove($p_model, $p_id){

        $result = false;

        //TODO : Validacion IsTable Exsist

        $modelData              = $this->model->get($p_model);
        $modelRelations         = $this->model->getRelations($p_model, 'IN');

        $objectData             = $this->get($p_model, $p_id);

        if($objectData){

            $tableName = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);

            $deleteConditions = "_id = '" . $p_id . "'";

            $deleteResult = $this->database->delete($tableName, $deleteConditions);

            if($deleteResult){

                //RELATIONS
                if (count($modelRelations) > 0) {

                    foreach ($modelRelations as $modelRelation) {
                        
                        $this->removeObjectRelations($modelRelation['modId'], $p_id);
                    }
                }

                $result = $p_id;

                $this->indexRemove($modelData, $p_id);

                $this->deleteObjectCache($p_id);
            }else{

                //TODO : NOT Updated
            }

            //TODO : Relaciones
            //TODO : Collections
            //TODO : SubObjetos

            //INDEXACION DE OBJETO

            //TODO : funcion Indexed para el campo _indexed y _user_indexed

        }else{

            //TODO : No se pudo encontrar el objeto para editarlo
        }

        return $result;
    }

    protected function deleteObjectCache($p_id){

        $keys   = array();
        $keys[] = 'data_object_' . $p_id;
        $keys[] = 'data_object_all';

        $this->deleteMultipleCache($keys);
    }

    private function getPartitionIndex($p_modelData){

        $result = 0;

        if(isset($p_modelData['modPartitionMode'])){

            $dayofyear      = (int)date('z')+1;
            $partitions     = 4;
            $yearpercent    = ($dayofyear*100)/365;

            switch($p_modelData['modPartitionMode']){

                case 'Y4' :
                    $partitions = 4;
                    break;

                case 'Y12' :
                    $partitions = 12;
                    break;

                case 'Y53' :
                    $partitions = 53;
                    break;

                case 'Y122' :
                    $partitions = 122;
                    break;
            }

            $partition      = ($partitions * $yearpercent) / 100;

            $result = ceil($partition);
        }

        return $result;
    }

    public function reindex($p_modelData, $p_id, $p_data){

        $modelDefinition        = $this->definition->get($p_modelData['modId'], null);
        $modelRelations         = $this->model->getRelations($p_modelData['modId'], 'IN');

        return $this->indexAdd($p_modelData, $modelDefinition, $modelRelations, $p_id, \Nubesys\Core\Utils\Struct::toArray($p_data));
    }

    public function multiReindex($p_modelData, $p_data){

        $modelDefinition        = $this->definition->get($p_modelData['modId'], null);
        $modelRelations         = $this->model->getRelations($p_modelData['modId'], 'IN');

        return $this->indexBulkAdd($p_modelData, $modelDefinition, $modelRelations, \Nubesys\Core\Utils\Struct::toArray($p_data));
    }

    public function search($p_model, $p_query){
        
        $result = false;

        $modelData              = $this->model->get($p_model);
        $modelDefinition        = $this->definition->get($p_model, null);
        $modelRelations         = $this->model->getRelations($p_model, 'IN');
        
        if (isset($modelData['modIndexOptions']) && is_object($modelData['modIndexOptions'])) {

            if (property_exists($modelData['modIndexOptions'], 'indexable') && $modelData['modIndexOptions']->indexable == true) {

                $idsResults = $this->indexSearchIds($modelData, $modelDefinition, $modelRelations, $p_query);
                
                if ($idsResults != false && isset($idsResults['ids']) && isset($idsResults['totals']) && isset($idsResults['facets'])) {

                    $result = array();
                    $result['totals'] = $idsResults['totals'];
                    $result['facets'] = $idsResults['facets'];
                    $result['objects'] = array();

                    foreach ($idsResults['ids'] as $id) {

                        $result['objects'][] = $this->get($p_model, $id);
                    }
                }
            }else{

                $result = array();
                $result['totals'] = $this->count($p_model);
                $result['objects']  = $this->list($p_model, $p_query);
            }
        }
        
        return $result;
    }

    public function searchNames($p_model, $p_query){

        $result = false;

        $modelData              = $this->model->get($p_model);
        $modelDefinition        = $this->definition->get($p_model, null);
        $modelRelations        = $this->model->getRelations($p_model, 'IN');

        if (isset($modelData['modIndexOptions']) && is_object($modelData['modIndexOptions'])) {

            if (property_exists($modelData['modIndexOptions'], 'indexable') && $modelData['modIndexOptions']->indexable == true) {

                $idsResults = $this->indexSearchIds($modelData, $modelDefinition, $modelRelations, $p_query);

                if ($idsResults != false && isset($idsResults['ids']) && isset($idsResults['totals']) && isset($idsResults['facets'])) {

                    $result = array();
                    $result['totals'] = $idsResults['totals'];
                    $result['facets'] = $idsResults['facets'];
                    $result['objects'] = array();

                    foreach ($idsResults['ids'] as $id) {

                        $objectTemp = new \stdClass();
                        $objectTemp->_id   = $id;
                        $objectTemp->name  = $this->name($p_model, $id);

                        $result['objects'][] = $objectTemp;
                    }
                }
            }else{

                $result = array();
                $result['totals'] = $this->count($p_model);
                $result['objects']  = $this->listNames($p_model, $p_query);
            }
        }

        return $result;
    }

    private function indexSearchIds($p_modelData, $p_modelDefinition, $p_modelRelations, $p_query){
        
        $result = false;

        if($this->elastic->setClient()) {

            if (isset($p_modelData['modIndexOptions']) && is_object($p_modelData['modIndexOptions'])) {

                if (property_exists($p_modelData['modIndexOptions'], 'indexable') && $p_modelData['modIndexOptions']->indexable == true) {

                    $indexName  = $this->getIndexName($p_modelData);

                    $typeName   = $this->getTypeName($p_modelData);

                    $index = $this->getIndex($indexName, $p_modelData);
                    
                    if($index !== false){

                        $mapping = $this->getMapping($p_modelData, $p_modelDefinition, $p_modelRelations);
                        
                        $type = $this->getType($index, $typeName, $mapping);

                        if($type !== false) {

                            $page                       = 1;
                            if(isset($p_query['page'])){

                                $page                   = $p_query['page'];
                            }

                            $rows                       = 5;
                            if(isset($p_query['rows'])){

                                $rows                   = $p_query['rows'];
                            }

                            $keyword                    = '*';
                            if(isset($p_query['keyword'])){

                                $keyword                = $p_query['keyword'];
                            }

                            $fields                     = array('*');
                            if(isset($p_query['fields'])){

                                $fields                 = $p_query['fields'];
                            }
                            
                            $facets                     = array();
                            if(isset($p_query['facets'])){

                                $facets                 = $p_query['facets'];
                            }

                            $filters                     = array();
                            if(isset($p_query['filters'])){

                                $filters                 = $p_query['filters'];
                            }

                            $orders                      = array("objOrder" => array("order"=>"desc"));
                            if(isset($p_query['orders'])){

                                $orders                 = $p_query['orders'];
                            }

                            $ranges                      = array();
                            if(isset($p_query['ranges'])){

                                $ranges                 = $p_query['ranges'];
                            }
                            
                            $queryResult = $this->elastic->searchDocs($type, $keyword, $fields, $orders, $page, $rows, $facets, $filters, $ranges);
                            
                            if($queryResult != false){

                                $result = array();

                                $result['totals'] = $queryResult['totals'];
                                $result['facets'] = $queryResult['facets'];
                                $result['ids'] = array();

                                foreach ($queryResult['docs'] as $doc) {

                                    $result['ids'][] = $doc->getId();
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $result;
    }

    private function indexAdd($p_modelData, $p_modelDefinition, $p_modelRelations, $p_id, $p_data){
        
        $result = false;

        if($this->elastic->setClient()) {
            
            if (isset($p_modelData['modIndexOptions']) && is_object($p_modelData['modIndexOptions'])) {

                if (property_exists($p_modelData['modIndexOptions'], 'indexable') && $p_modelData['modIndexOptions']->indexable == true) {

                    $indexName  = $this->getIndexName($p_modelData);

                    $typeName   = $this->getTypeName($p_modelData);

                    $index = $this->getIndex($indexName, $p_modelData);
                    
                    if($index !== false){

                        $mapping = $this->getMapping($p_modelData, $p_modelDefinition, $p_modelRelations);
                        
                        $documentData = $this->getIndexableData($p_modelDefinition, $p_modelRelations, $p_data);
                        
                        $type = $this->getType($index, $typeName, $mapping);

                        if($type !== false) {

                            $result = $this->elastic->addDoc($type, $p_id, $documentData);
                        }
                    }

                } else {

                    $result = true;
                }
            }
        }

        return $result;
    }

    private function indexBulkAdd($p_modelData, $p_modelDefinition, $p_modelRelations, $p_data){
        
        $result = false;

        $client = $this->elastic->setClient();

        if($client) {

            if (isset($p_modelData['modIndexOptions']) && is_object($p_modelData['modIndexOptions'])) {

                if (property_exists($p_modelData['modIndexOptions'], 'indexable') && $p_modelData['modIndexOptions']->indexable == true) {

                    $indexName  = $this->getIndexName($p_modelData);

                    $typeName   = $this->getTypeName($p_modelData);

                    $index = $this->getIndex($indexName, $p_modelData);

                    if($index !== false){

                        $mapping = $this->getMapping($p_modelData, $p_modelDefinition, $p_modelRelations);
                        
                        $documents = array();
                        
                        foreach($p_data as $doc){

                            $documents[$doc['_id']] = $this->getIndexableData($p_modelDefinition, $p_modelRelations, $doc);
                        }
                        
                        $type = $this->getType($index, $typeName, $mapping);
                        
                        if($type !== false) {

                            $result = $this->elastic->addBulk($client, $index, $type, $documents);
                        }
                    }

                } else {

                    $result = true;
                }
            }
        }

        return $result;
    }

    private function indexUpdate($p_modelData, $p_modelDefinition, $p_modelRelations, $p_id, $p_data){

        $result = false;

        if($this->elastic->setClient()) {

            if (isset($p_modelData['modIndexOptions']) && is_object($p_modelData['modIndexOptions'])) {

                if (property_exists($p_modelData['modIndexOptions'], 'indexable') && $p_modelData['modIndexOptions']->indexable == true) {

                    $indexName  = $this->getIndexName($p_modelData);

                    $typeName   = $this->getTypeName($p_modelData);

                    $index = $this->getIndex($indexName, $p_modelData);

                    if($index !== false){

                        $mapping = $this->getMapping($p_modelData, $p_modelDefinition, $p_modelRelations);

                        $documentData = $this->getIndexableData($p_modelDefinition, $p_modelRelations, $p_data);

                        $type = $this->getType($index, $typeName, $mapping);

                        if($type !== false) {

                            $result = $this->elastic->updateDoc($type, $p_id, $documentData);
                        }
                    }

                } else {

                    $result = true;
                }
            }

        }

        return $result;
    }

    private function indexRemove($p_modelData, $p_id){

        $result = false;

        if($this->elastic->setClient()) {

            if (isset($p_modelData['modIndexOptions']) && is_object($p_modelData['modIndexOptions'])) {

                if (property_exists($p_modelData['modIndexOptions'], 'indexable') && $p_modelData['modIndexOptions']->indexable == true) {

                    $indexName  = $this->getIndexName($p_modelData);

                    $typeName   = $this->getTypeName($p_modelData);

                    $index = $this->getIndex($indexName, $p_modelData);

                    if($index !== false){

                        $type = $this->getType($index, $typeName);

                        if($type !== false) {

                            $result = $this->elastic->deleteDoc($type, $p_id);
                        }
                    }

                } else {

                    $result = true;
                }
            }

        }

        return $result;
    }

    private function getIndex($p_indexName, $p_modelData){

        $indexArgs = array();

        $indexArgs['analysis'] = $this->getDI()->get('config')->main->elastic->analysis;

        if (property_exists($p_modelData['modIndexOptions'], 'analysis')) {

            $indexArgs['analysis'] = \Nubesys\Core\Utils\Struct::extendFieldValues($indexArgs['analysis'], $p_modelData['modIndexOptions']->analysis);

            //$indexArgs['analysis'] = $p_modelData['modIndexOptions']->analysis;
        }
        
        //var_dump($indexArgs);exit();
        return $this->elastic->setIndex($p_indexName, $indexArgs);
    }

    private function getType($p_index, $p_typeName, $p_mapping = array()){

        return $this->elastic->setType($p_index, $p_typeName, $p_mapping);
    }

    private function getIndexName($p_modelData){

        return $this->model->getIndexName($p_modelData['modId']);
    }

    private function getTypeName($p_modelData){

        return $this->getDI()->get('config')->main->id . '_' . $p_modelData['modId'];
    }

    private function getMapping($p_modelData, $p_modelDefinition, $p_modelRelations){
        
        $basemapping = \Nubesys\Core\Utils\Struct::toObject($this->getDI()->get('config')->main->elastic->basemapping->properties->toArray());
        
        if (property_exists($p_modelData['modIndexOptions'], 'basemapping')) {

            if(property_exists($p_modelData['modIndexOptions']->basemapping,'properties')){

                $basemapping = \Nubesys\Core\Utils\Struct::extendFieldValues($basemapping, $p_modelData['modIndexOptions']->basemapping->properties);
            }
        }
        
        //OBJECT DATA MAPPING (PREDEFINIDO)
        $basedatamapping = new \stdClass();
        $basedatamapping->type = 'object';
        $basedatamapping->properties = new \stdClass();

        if(property_exists($basemapping,'objData')){

            $basemappingData = $basemapping->objData;

            if(property_exists($basemappingData,'properties')){

                $basedatamapping->properties = $basemappingData;
            }
        }

        //OBJECT DATA MAPPING
        $datamapping = new \stdClass();
        $datamapping->type = 'object';
        $datamapping->properties = new \stdClass();

        foreach($p_modelDefinition as $definition){

            $propertyName = $definition['dafId'];

            if(isset($definition['defDafIndexOptions'])){

                if(is_object($definition['defDafIndexOptions']) && property_exists($definition['defDafIndexOptions'],'indexable') && property_exists($definition['defDafIndexOptions'],'mapping')){

                    if($definition['defDafIndexOptions']->indexable == true){

                        if(is_object($definition['defDafIndexOptions']->mapping)){

                            $datamapping->properties->$propertyName = $definition['defDafIndexOptions']->mapping;

                            if($definition['typId'] == 'objectr'){

                                $fullTextPropertyName               = $propertyName . "_flltxt";

                                $fullTextPropertyMapping            = new \stdClass();
                                $fullTextPropertyMapping->type      = "text";
                                //$fullTextPropertyMapping->analizer  = "nbs_analyzer";

                                $datamapping->properties->$fullTextPropertyName = $fullTextPropertyMapping;
                            }

                            if($definition['typId'] == 'objectsr'){

                                $fullTextPropertyName               = $propertyName . "_flltxt";

                                $fullTextPropertyMapping            = new \stdClass();
                                $fullTextPropertyMapping->type      = "text";
                                //$fullTextPropertyMapping->analizer  = "nbs_analyzer";

                                $datamapping->properties->$fullTextPropertyName = $fullTextPropertyMapping;
                            }
                        }
                    }
                }
            }
        }
        
        $datamapping = \Nubesys\Core\Utils\Struct::extendFieldValues($basedatamapping, $datamapping);
        
        $basemapping->objData = $datamapping;

        //RELATIONS DATA MAPPING (PREDEFINIDO)
        $baserelmapping = new \stdClass();
        $baserelmapping->type = 'object';
        $baserelmapping->properties = new \stdClass();

        if(property_exists($basemapping,'relData')){

            $basemappingRel = $basemapping->relData;

            if(property_exists($basemappingRel,'properties')){

                $baserelmapping->properties = $basemappingRel;
            }
        }

        //RELATIONS DATA MAPPING
        $relmapping = new \stdClass();
        $relmapping->type = 'object';
        $relmapping->properties = new \stdClass();

        foreach($p_modelRelations as $relation){

            $propertyName = 'rel_' . $relation['modId'];

            if(isset($relation['relIndexOptions'])){

                if(is_object($relation['relIndexOptions']) && property_exists($relation['relIndexOptions'],'indexable')){

                    if($relation['relIndexOptions']->indexable == true){

                        //TODO VER MAPEO DE OTRA CALSE DE RELACIONES

                        $relationPropertyMapping            = new \stdClass();
                        $relationPropertyMapping->type      = "keyword";

                        $relmapping->properties->$propertyName = $relationPropertyMapping;
                    }
                }
            }
        }

        $relmapping = \Nubesys\Core\Utils\Struct::extendFieldValues($baserelmapping, $relmapping);

        $basemapping->relData = $relmapping;

        return \Nubesys\Core\Utils\Struct::toArray($basemapping);
    }

    private function getIndexableData($p_modelDefinition, $p_modelRelations, $p_data){
        
        $result = array();

        foreach($p_data as $key=>$value){

            if($key != '_id' && $key != 'objData'){

                $result[$key] = $value;
                
                if($key == 'objActive' || $key == 'objErased' || $key == 'objIndexed'){
                    
                    $result[$key] = ($value == 1 || $value == true) ? "true" : "false";
                }
            }
        }
        
        $_dataTemp = array();

        foreach($p_modelDefinition as $definition){

            $propertyName = $definition['dafId'];

            if(isset($definition['defDafIndexOptions'])){

                if(is_object($definition['defDafIndexOptions']) && property_exists($definition['defDafIndexOptions'],'indexable')){

                    if($definition['defDafIndexOptions']->indexable == true){

                        if(isset($p_data['objData'][$propertyName])){

                            //$_dataTemp[$propertyName] = $p_data['objData'][$propertyName];

                            if($definition['typId'] == 'objectr'){

                                if(isset($definition['defDafTypOptions']) && property_exists($definition['defDafTypOptions'],'model')){

                                    $fullTextPropertyName               = $propertyName . "_flltxt";
                                    
                                    $id                                 = $p_data['objData'][$propertyName];

                                    if(is_string($id) && $id != "[]"){

                                        $_dataTemp[$fullTextPropertyName]   = $this->name($definition['defDafTypOptions']->model, $id);
                                    }else{

                                        $_dataTemp[$fullTextPropertyName]   = $propertyName;
                                    }
                                    
                                }

                                $_dataTemp[$propertyName] = (array)$p_data['objData'][$propertyName];
                                
                            }else if($definition['typId'] == 'objectsr'){

                                if(isset($definition['defDafTypOptions']) && property_exists($definition['defDafTypOptions'],'model')){

                                    $fullTextPropertyName               = $propertyName . "_flltxt";
                                    
                                    $_flltxtDataTemp                    = "";

                                    $index                              = 0;

                                    if(is_array($p_data['objData'][$propertyName])){

                                        foreach ($p_data['objData'][$propertyName] as $id){

                                            $_flltxtDataTemp .= $this->name($definition['defDafTypOptions']->model, $id);
    
                                            if($index < count($p_data['objData'][$propertyName]) - 1){
    
                                                $_flltxtDataTemp .= " ";
                                            }
    
                                            $index++;
                                        }
                                    }

                                    $_dataTemp[$fullTextPropertyName]   = $_flltxtDataTemp;
                                }

                                $_dataTemp[$propertyName] = (array)$p_data['objData'][$propertyName];
                                
                            }else if($definition['typId'] == 'boolean'){
                                  
                                $_dataTemp[$propertyName] = ($p_data['objData'][$propertyName] == 1 || $p_data['objData'][$propertyName] == true) ? "true" : "false";
                            }else{

                                $_dataTemp[$propertyName] = $p_data['objData'][$propertyName];
                            }
                        }
                    }
                }
            }
        }
        
        $result['objData'] = $_dataTemp;

        $_relTemp = array();

        foreach($p_modelRelations as $relation){

            $propertyName = "rel_" . $relation['modId'];

            if(isset($definition['relIndexOptions'])){

                if(is_object($definition['relIndexOptions']) && property_exists($definition['relIndexOptions'],'indexable')){

                    if($definition['relIndexOptions']->indexable == true){

                        $_relTemp[$propertyName] = $p_data['relData'][$propertyName];
                    }
                }
            }
        }

        $result['objData'] = $_dataTemp;
        $result['RELData'] = $_relTemp;
        
        return $result;
    }

    public function get($p_model, $p_id){
        
        $result = false;

        $cacheKey       = 'data_object_' . $p_id;
        $cacheLifetime  = 3600;
        $cacheType      = 'file';
        

        if($this->hasCache($cacheKey)){

            $result = $this->getCache($cacheKey, array());
        }else {

            $modelData              = $this->model->get($p_model);
            $modelRelations         = $this->model->getRelations($p_model, 'IN');
            
            $tableName = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);
            
            if($this->database->isTableExist($tableName)){
                
                $selectOptions = array();
                $selectOptions['conditions'] = "_id = '" . $p_id . "'";
                
                $selectResult = $this->database->selectOne($tableName, $selectOptions);
                
                if ($selectResult) {
                    
                    if (isset($selectResult['objData'])) {
    
                        $selectResult['objData'] = json_decode($selectResult['objData']);

                    } else {
    
                        $selectResult['objData'] = new \stdClass();
                    }

                    if (isset($selectResult['objStatesLog'])) {
    
                        $selectResult['objStatesLog'] = json_decode($selectResult['objStatesLog']);

                    } else {
    
                        $selectResult['objStatesLog'] = array();
                    }

                    if (isset($selectResult['objStateData'])) {
    
                        $selectResult['objStateData'] = json_decode($selectResult['objStateData']);

                    } else {
    
                        $selectResult['objStateData'] = new \stdClass();
                    }

                    foreach ($modelRelations as $modelRelation) {

                        $fieldId = 'rel_' . $modelRelation['modId'];

                        $currentRelationsObjects = $this->getRelationsObjects($modelRelation['modId'], $p_id);
                        
                        $rightObjects = array();
                        foreach($currentRelationsObjects as $relId => $relData){
                            
                            $rightObjects[] = $relData['relRightObjId'];
                        }

                        $selectResult['objData']->$fieldId = $rightObjects;
                    }
                    
                    $result = $selectResult;
                }
                
            }
            $this->setCache($cacheKey, $result, $cacheLifetime);
        }
        
        return $result;
    }

    public function name($p_model, $p_id){
        
        $result = $p_id;
        
        $cacheKey       = 'data_object_name_' . $p_id;
        $cacheLifetime  = 3600;
        
        if($this->hasCache($cacheKey)){

            $result = $this->getCache($cacheKey, "");
        }else {

            $objectData = $this->get($p_model, $p_id);

            if ($objectData) {

                $modelDefinition = $this->definition->get($p_model, null);

                $nameField = $this->getNameField($modelDefinition)['dafId'];

                if (is_object($objectData['objData']) && property_exists($objectData['objData'], $nameField)) {

                    $result = $objectData['objData']->$nameField;
                }
            }

            $this->setCache($cacheKey, $result, $cacheLifetime);
        }
        
        return $result;
    }

    public function has($p_model, $p_id){

        $result = false;

        $modelData              = $this->model->get($p_model);

        $tableName              = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);

        //TODO : Validacion si la tabla existe

        $selectOptions                  = array();
        $selectOptions['conditions']    = "_id = '" . $p_id . "'";

        $selectResult = $this->database->selectOne($tableName,$selectOptions);

        if(is_array($selectResult)){

            $result = true;
        }

        return $result;
    }

    public function list($p_model, $p_options){
        $result = false;

        //TODO : Validacion si la tabla existe

        $modelData              = $this->model->get($p_model);

        $tableName              = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);

        $selectOptions          = array();

        $rows = 100;

        if(isset($p_options['rows'])){

            $rows = $p_options['rows'];

            $selectOptions['rows'] = $rows;
        }

        $page = 1;

        if(isset($p_options['page'])){

            $page = $p_options['page'];

            $selectOptions['offset'] = ($page - 1) * $rows;
        }

        if(isset($p_options['conditions'])){

            $selectOptions['conditions'] = $p_options['conditions'];
        }

        if(isset($p_options['order'])){

            $selectOptions['order'] = $p_options['order'];
        }

        //TODO: Falta orders y todo lo demas para hacer similar al elasticsearch

        $selectResult = $this->database->select($tableName,$selectOptions);
        
        if($selectResult){

            foreach($selectResult as $object){

                if(isset($object['objData'])){

                    $object['objData'] = json_decode($object['objData']);

                }else{

                    $object['objData'] = new \stdClass();
                }

                if(isset($object['objStatesLog'])){

                    $object['objStatesLog'] = json_decode($object['objStatesLog']);

                }else{

                    $object['objStatesLog'] = array();
                }

                if(isset($object['objStateData'])){

                    $object['objStateData'] = json_decode($object['objStateData']);

                }else{

                    $object['objStateData'] = new \stdClass();
                }

                $result[] = $object;
            }

        }

        return $result;
    }

    public function listNames($p_model, $p_options){
        $result = false;

        //TODO : Validacion si la tabla existe

        $modelData              = $this->model->get($p_model);

        $tableName              = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);

        $selectOptions          = array();

        $rows = 100;

        if(isset($p_options['rows'])){

            $rows = $p_options['rows'];

            $selectOptions['rows'] = $rows;
        }

        $page = 1;

        if(isset($p_options['page'])){

            $page = $p_options['page'];

            $selectOptions['offset'] = ($page - 1) * $rows;
        }

        //TODO: Falta orders y todo lo demas para hacer similar al elasticsearch

        $selectResult = $this->database->select($tableName,$selectOptions);

        if($selectResult){

            foreach($selectResult as $object){

                $objectTemp = new \stdClass();
                $objectTemp->_id = $object['_id'];
                $objectTemp->name  = $this->name($p_model, $object['_id']);

                $result[] = $objectTemp;
            }

        }

        return $result;
    }

    public function count($p_model, $p_options = array()){
        $result = false;

        //TODO : Validacion si la tabla existe

        $modelData              = $this->model->get($p_model);

        $tableName = $this->model->getModelObjectsTableName($p_model, $modelData['modType']);

        $selectOptions                  = array();

        if(isset($p_options['conditions'])){

            $selectOptions['conditions'] = $p_options['conditions'];
        }

        $selectResult = $this->database->selectCount($tableName,$selectOptions);

        if($selectResult !== false){

            $result = $selectResult;
        }

        return $result;
    }

    protected function getIdValue($p_data, $p_model, $p_definition){
        
        $result = false;
        
        if($p_model['modIdStrategy'] == 'SLUGPREFIX' || $p_model['modIdStrategy'] == 'SLUG'){

            $nameField = $this->getNameField($p_definition);

            $nameFieldId = $nameField['dafId'];

            if(isset($p_data[$nameFieldId])){

                $nameValue = $p_data[$nameFieldId];

                if($p_model['modIdStrategy'] == 'SLUGPREFIX'){

                    $result = $p_model['modId'] . '_' . \Nubesys\Core\Utils\Utils::slugify($nameValue);
                }

                if($p_model['modIdStrategy'] == 'SLUG'){

                    $result = \Nubesys\Core\Utils\Utils::slugify($nameValue);
                }

                //TODO : Verificar si ya no existe el Objeto en la db y agregarle un indice numerico
            }else{

                //TODO : no hay el campo como name en el data
            }
        }

        if($p_model['modIdStrategy'] == 'CUSTOM'){
            
            if(isset($p_data['_id'])){

                $result = $p_data['_id'];
            }
        }

        if($p_model['modIdStrategy'] == 'UUID'){

            $result = \Nubesys\Core\Utils\Utils::GUID($this->getDI());
        }

        if($p_model['modIdStrategy'] == 'AUTOINCREMENT'){

            $result = \Nubesys\Core\Utils\Utils::getSequenceNextValue($this->getDI(), $p_model['modId'] . '_objects_autoid');
        }

        if($p_model['modIdStrategy'] == 'BASE36'){

            $result = \Nubesys\Core\Utils\Utils::getU36($this->getDI());
        }

        return $result;
    }

    protected function getNameField($p_modelDefinition){

        $result = false;

        foreach($p_modelDefinition as $field){

            if(isset($field['defIsName']) && $field['defIsName'] == 1){

                $result = $field;
                break;
            }
        }

        return $result;
    }

    private function decodeUtf8($p_data){

        //SI ES ARRAY
        if(is_array($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data[$key] = utf8_decode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data[$key] = $this->decodeUtf8($value);
                }
            }
        }elseif(is_object($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data->$key = utf8_decode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data->$key = $this->decodeUtf8($value);
                }
            }
        }

        return $p_data;
    }

    private function encodeUtf8($p_data){

        //SI ES ARRAY
        if(is_array($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data[$key] = utf8_encode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data[$key] = $this->encodeUtf8($value);
                }
            }
        }elseif(is_object($p_data)){

            foreach($p_data as $key => $value){

                if(is_string($value)){

                    $p_data->$key = utf8_encode($value);
                }elseif(is_array($value) || is_object($value)){

                    $p_data->$key = $this->encodeUtf8($value);
                }
            }
        }

        return $p_data;
    }

}