<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 27/07/16
 * Time: 03:23 PM
 */

namespace Nubesys\Data\Objects;

use Nubesys\Core\Common;

use Nubesys\Data\Objects\Relation;

class Model extends Common
{

    protected $database;

    protected $relation;

    public function __construct($p_di, $p_database, Relation $p_relation)
    {
        parent::__construct($p_di);

        $this->database = $p_database;

        $this->relation = $p_relation;
    }

    public function has($p_model){

        $result = false;

        $selectOptions                  = array();
        $selectOptions['conditions']    = "modId = '" . $p_model . "'";

        $selectResult = $this->database->selectOne('data_models',$selectOptions);

        if(is_array($selectResult)){

            $result = true;
        }

        return $result;
    }

    public function get($p_modId){
        
        $result = false;
        $jsonFields = array('modIndexOptions','modUiOptions','modCacheOptions','modVersionsOptions','modStatesOptions');

        //TODO : Cache de Types

        $modelDataOptions     = array();

        if($p_modId == null) {
            
            $cacheKey       = 'data_model_all';
            $cacheLifetime  = 3600;
            
            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey);
            }else {

                $modelDataOptions['rows'] = 1000;

                $resultSet = $this->database->select('data_models', $modelDataOptions);

                $result = array();

                foreach ($resultSet as $model) {

                    $modelTemp = array();

                    foreach ($model as $field => $value) {

                        $modelTemp[$field] = \Nubesys\Core\Utils\Struct::decodeJsonField($field, $value, $jsonFields);
                    }

                    $result[] = $modelTemp;
                }

                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }else{
            
            $cacheKey       = 'data_model_' . $p_modId;
            $cacheLifetime  = 3600;
            
            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey);
            }else {

                $modelDataOptions['conditions'] = "modId = '" . $p_modId . "'";

                $resultSet = $this->database->selectOne('data_models', $modelDataOptions);

                $result = array();

                foreach ($resultSet as $field => $value) {

                    $result[$field] = \Nubesys\Core\Utils\Struct::decodeJsonField($field, $value, $jsonFields);
                }

                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }

        return $result;
    }

    public function getRelations($p_modId, $p_direction = 'all'){
        
        $result = array();

        if($p_direction == 'IN'){

            $result = $this->getInRelations($p_modId);
        }elseif($p_direction == 'OUT'){

            $result = $this->getOutRelations($p_modId);
        }else{

            $result = array_merge($this->getInRelations($p_modId), $this->getOutRelations($p_modId));
        }

        return $result;
    }
    
    private function getInRelations($p_modId){
        
        return $this->relation->getByModel($p_modId, 'LEFT');
    }

    private function getOutRelations($p_modId){

        return $this->relation->getByModel($p_modId, 'RIGHT');
    }

    public function isIndexable($p_modId){

        $result = false;

        $modelData = $this->get($p_modId);

        if(isset($modelData['modIndexOptions']) && property_exists($modelData['modIndexOptions'],'indexable')){

            $result = $modelData['modIndexOptions']->indexable;
        }

        return $result;
    }

    public function getName($p_modId){

        $result = false;

        $modelData = $this->get($p_modId);

        if(isset($modelData['modUiOptions']) && property_exists($modelData['modUiOptions'],'name')){

            $result = $modelData['modUiOptions']->name;
        }

        return $result;
    }

    public function getPluralName($p_modId){

        $result = false;

        $modelData = $this->get($p_modId);
        
        if(isset($modelData['modUiOptions']) && property_exists($modelData['modUiOptions'],'pluralName')){

            $result = $modelData['modUiOptions']->pluralName;
        }

        return $result;
    }

    public function getIndexName($p_modId){

        $result = false;

        //NAME OF INDEX AND TYPE
        $result = $this->getDI()->get('config')->application->client . '_system';

        $modelData = $this->get($p_modId);

        if(isset($modelData['modIndexOptions']) && property_exists($modelData['modIndexOptions'],'index')){

            $result = $this->getDI()->get('config')->application->client . '_' . $modelData['modIndexOptions']->index;
        }

        return $result;
    }

    public function add($p_data, $p_table = true){

        $result = $this->database->insert('data_models', $p_data);

        if($p_table) {

            if ($result && isset($p_data['modPartitionMode'])) {

                if(isset($p_data['modType'])){

                    $result = $this->createModelTable($p_data['modId'], $p_data['modType'], $p_data['modPartitionMode']);
                }
            }
        }

        return $result;
    }

    public function edit($p_id, $p_data){

        $resultSet = $this->database->update('data_models', $p_data, "modId = '$p_id'");

        $cacheKeys       = array('data_model_' . $p_id, 'data_model_all');

        foreach($cacheKeys as $key){

            if($this->hasCache($key)){

                $this->deleteCache($key);
            }
        }

        return $resultSet;
    }
    /*
    public function getDefinition($p_model){

        $result = false;

        $cacheContext   = 'datamodels';
        $cacheKey       = 'model_def_' . $p_model;
        $cacheLifetime  = 3600;
        $cacheType      = 'file';

        if($this->cacheExists($cacheContext, $cacheKey, $cacheType)){

            $result = $this->cacheGet($cacheContext, $cacheKey, $cacheType);
        }else {

            $modelFieldsOptions = array(

                'conditions' => "modId = '" . $p_model . "'",
                'order' => array('defOrder' => 'ASC')
            );

            //TODO : Cache de DataDefinitions del Modelo

            $modelFields = $this->database->select('data_definitions', $modelFieldsOptions);

            if ($modelFields) {

                $result = array();

                foreach ($modelFields as $modelField) {

                    $result[] = $this->defExtend($modelField);
                }
            }

            $this->cacheSave($cacheContext, $cacheKey, $result, $cacheLifetime, $cacheType);
        }

        return $result;
    }

    protected function defExtend($p_data){

        $fieldData = $this->field->get($p_data['dafId']);

        $p_data['defDafDefaultValue']           = \Nubesys\Platform\Util\Parse::extendFieldValues($fieldData['dafDefaultValue'], $p_data['defDafDefaultValue'], true);
        $p_data['defDafUiOptions']              = \Nubesys\Platform\Util\Parse::extendFieldValues($fieldData['dafUiOptions'], $p_data['defDafUiOptions']);
        $p_data['defDafIndexOptions']           = \Nubesys\Platform\Util\Parse::extendFieldValues($fieldData['dafIndexOptions'], $p_data['defDafIndexOptions']);
        $p_data['defDafTypOptions']             = \Nubesys\Platform\Util\Parse::extendFieldValues($fieldData['dafTypOptions'], $p_data['defDafTypOptions']);
        $p_data['defDafTypValidationOptions']   = \Nubesys\Platform\Util\Parse::extendFieldValues($fieldData['dafTypValidationOptions'], $p_data['defDafTypValidationOptions']);
        $p_data['defDafAttachFileOptions']      = \Nubesys\Platform\Util\Parse::extendFieldValues($fieldData['dafAttachFileOptions'], $p_data['defDafAttachFileOptions']);

        $p_data['typId']                    = $fieldData['typId'];
        $p_data['typSaveAs']                = $fieldData['typSaveAs'];
        $p_data['typReferenceTo']           = $fieldData['typReferenceTo'];

        return $p_data;
    }
    */

    public function getModelObjectsTableName($p_model, $p_type){

        $result = $p_model . '_objects';

        if($p_type != 'OBJECT'){

            switch ($p_type){

                case 'RELATION':

                    $result = $p_model . '_relations_objects';
                    break;
                case 'COLLECTION':

                    $result = $p_model . '_collections_objects';
                    break;
                case 'VERTEX':

                    $result = $p_model . '_vertex_objects';
                    break;
            }
        }

        return $result;
    }

    private function createModelTable($p_model, $p_type, $p_partition){

        $result = true;

        $tableName = $this->getModelObjectsTableName($p_model, $p_type);

        if(!$this->database->isTableExist($tableName)){

            $primarySQL = "`_id`,`objPartitionIndex`";
            $partitionSQL = "";
            $aditionalSQL = "";

            $createSQL = "CREATE TABLE `{TableName}` (
                              `_id` varchar(255) NOT NULL,
                              `modId` varchar(50) NOT NULL,
                              {Aditional}
                              `objTime` float unsigned NOT NULL,
                              `objOrder` bigint(20) unsigned NOT NULL DEFAULT '0',
                              `objActive` tinyint(1) NOT NULL DEFAULT '1',
                              `objData` json NOT NULL,
                              `objDateAdd` datetime DEFAULT NULL,
                              `objUserAdd` varchar(255) DEFAULT NULL,
                              `objDateUpdated` datetime DEFAULT NULL,
                              `objUserUpdated` varchar(255) DEFAULT NULL,
                              `objErased` tinyint(1) DEFAULT '0',
                              `objDateErased` datetime DEFAULT NULL,
                              `objUserErased` varchar(255) DEFAULT NULL,
                              `objIndexed` tinyint(1) DEFAULT '0',
                              `objDateIndexed` datetime DEFAULT NULL,
                              `objPartitionIndex` bigint(20) NOT NULL DEFAULT '0',
                              `objPage1000` bigint(20) NOT NULL DEFAULT '0',
                              `objPage10000` bigint(20) NOT NULL DEFAULT '0',
                              PRIMARY KEY ({Primary}),
                              KEY `objPage1000` (`objPage1000`),
                              KEY `objPage10000` (`objPage10000`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
                            {Partitions};";

            if($p_partition !== 'NONE'){

                $partitionSQL .= "PARTITION BY HASH (objPartitionIndex) ";

                switch($p_partition){

                    case 'Y4' :
                        $partitionSQL .= "PARTITIONS 4";
                        break;

                    case 'Y12' :
                        $partitionSQL .= "PARTITIONS 12";
                        break;

                    case 'Y53' :
                        $partitionSQL .= "PARTITIONS 53";
                        break;

                    case 'Y122' :
                        $partitionSQL .= "PARTITIONS 122";
                        break;
                }
            }

            if($p_type != 'OBJECT'){

                switch ($p_type){

                    case 'RELATION':
                        $aditionalSQL = "`relLeftObjId` varchar(255) NOT NULL,
                                        `relRightObjId` varchar(255) NOT NULL,";
                        $primarySQL = "`_id`,`relLeftObjId`,`relRightObjId`,`objPartitionIndex`";
                        break;
                    case 'COLLECTION':
                        $aditionalSQL = "`colObjId` varchar(255) NOT NULL,";
                        $primarySQL = "`_id`,`colObjId`,`objPartitionIndex`";
                        break;
                    case 'VERTEX':
                        $aditionalSQL = "`edgeKey` varchar(50) NOT NULL,
                                        `edgeObjId` varchar(255) NOT NULL,";
                        $primarySQL = "`_id`,`edgeKey`,`edgeObjId`,`objPartitionIndex`";

                        break;
                }
            }

            $createSQL = str_replace("{TableName}",$tableName, $createSQL);
            $createSQL = str_replace("{Aditional}",$aditionalSQL, $createSQL);
            $createSQL = str_replace("{Primary}",$primarySQL, $createSQL);
            $createSQL = str_replace("{Partitions}",$partitionSQL, $createSQL);

            if($p_type == 'VERTEX'){

                $this->createModelTable($p_model, 'OBJECT', $p_partition);
            }

            return $this->database->execute($createSQL);
        }

        return $result;
    }
}