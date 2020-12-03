<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 27/07/16
 * Time: 03:23 PM
 */

namespace Nubesys\Data\Objects;

use Nubesys\Core\Common;
use Nubesys\Core\Register;
use Nubesys\Data\Objects\Model;

class Definition extends Common
{

    protected $database;

    protected $model;
    protected $field;
    protected $fieldsCache;

    public function __construct($p_di, $p_database, Model $p_model, Field $p_field)
    {
        parent::__construct($p_di);

        $this->database = $p_database;

        $this->model        = $p_model;
        $this->field        = $p_field;
        $this->fieldsCache  = new Register();
    }

    public function get($p_modelId = null, $p_fieldId = null, $p_extended = true){
        
        $result = false;

        $definitionsDataOptions     = array();

        $definitionsDataOptions['order'] = array("defOrder" => "ASC");

        if($p_modelId == null && $p_fieldId == null){

            $cacheKey       = 'data_definitions_all';
            $cacheLifetime  = 3600;

            $cacheKey       .= ($p_extended) ? "_ext" : ""; 

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $definitionsDataOptions['rows'] = 1000;

                $definitionsData = $this->database->select('data_definitions', $definitionsDataOptions);

                if ($definitionsData) {

                    $result = array();

                    foreach ($definitionsData as $definitionData) {

                        $fieldData = $this->getFieldData($definitionData['dafId'], $p_extended);

                        if($p_extended){

                            $definitionData = $this->fieldToDefinitionExtend($definitionData, $fieldData);
                        }else{

                            $definitionData = $this->defEncode($definitionData);
                        }

                        $definitionData['typId']                    = $fieldData['typId'];
                        $definitionData['typSaveAs']                = $fieldData['typSaveAs'];
                        $definitionData['typReferenceTo']           = $fieldData['typReferenceTo'];

                        $result[] = $definitionData;
                    }
                }

                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }elseif($p_modelId != null && $p_fieldId == null){
            
            $cacheKey       = 'data_definitions_' . $p_modelId . '_all';
            $cacheLifetime  = 3600;

            $cacheKey       .= ($p_extended) ? "_ext" : "";
            
            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $definitionsDataOptions['conditions'] = "modId = '" . $p_modelId . "'";
                
                $definitionsData = $this->database->select('data_definitions', $definitionsDataOptions);
                
                if ($definitionsData) {

                    $result = array();

                    foreach ($definitionsData as $definitionData) {

                        $fieldData = $this->getFieldData($definitionData['dafId'], $p_extended);

                        if($p_extended){

                            $definitionData = $this->fieldToDefinitionExtend($definitionData, $fieldData);
                        }else{

                            $definitionData = $this->defEncode($definitionData);
                        }

                        $definitionData['typId']                    = $fieldData['typId'];
                        $definitionData['typSaveAs']                = $fieldData['typSaveAs'];
                        $definitionData['typReferenceTo']           = $fieldData['typReferenceTo'];

                        $result[] = $definitionData;
                    }
                }

                if($p_extended){
                        
                    $parentModelId                          = $this->model->getParent($p_modelId);

                    if($parentModelId != "root"){

                        $extendedResult                     = array();
                        $parentExtendedDefinitionsKeys      = array();
                        
                        $parentDefinitions                  = $this->get($parentModelId, $p_fieldId, $p_extended);
                        
                        $lastParentDefinitionOrder          = 0;
                        foreach($parentDefinitions as $parentDefinition){

                            if(\is_array($result)){
                                
                                foreach($result as $childDefinition){

                                    if($parentDefinition['dafId'] == $childDefinition['dafId']){

                                        $parentDefinition                   = $this->parentToChildExtend($parentDefinition, $childDefinition);
                                        $parentExtendedDefinitionsKeys[]    = $parentDefinition['dafId'];

                                        break;
                                    }
                                }
                            }

                            $extendedResult[]               = $parentDefinition;

                            $lastParentDefinitionOrder      = $parentDefinition['defOrder'];
                        }

                        if(\is_array($result)){
                            foreach($result as $childDefinition){

                                if(!in_array($childDefinition['dafId'], $parentExtendedDefinitionsKeys)){
                                    
                                    //$childDefinition['defOrder']    = $lastParentDefinitionOrder + $childDefinition['defOrder'];
                                    $childDefinition['defOrder']    = $childDefinition['defOrder'];

                                    $extendedResult[]               = $childDefinition;
                                }
                            }
                        }
                        $result = $extendedResult;
                        
                    }
                }
                
                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }else{
            
            $cacheKey       = 'data_definitions_' . $p_modelId . '_' . $p_fieldId;
            $cacheLifetime  = 3600;

            $cacheKey       .= ($p_extended) ? "_ext" : "";

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $definitionsDataOptions['conditions'] = "modId = '" . $p_modelId . "' AND dafId = '" . $p_fieldId . "'";

                $definitionData = $this->database->selectOne('data_definitions', $definitionsDataOptions);

                $fieldData = $this->getFieldData($definitionData['dafId'], $p_extended);

                if($p_extended){

                    $definitionData = $this->fieldToDefinitionExtend($definitionData, $fieldData);
                }else{

                    $definitionData = $this->defEncode($definitionData);
                }

                $definitionData['typId']                    = $fieldData['typId'];
                $definitionData['typSaveAs']                = $fieldData['typSaveAs'];
                $definitionData['typReferenceTo']           = $fieldData['typReferenceTo'];

                $result = $definitionData;
                
                $this->setCache($cacheKey, $result, $cacheLifetime);

            }
        }
        
        return $result;
    }

    public function add($p_data){

        $resultSet = $this->database->insert('data_definitions', $p_data);

        if(isset($p_data['dafId']) && isset($p_data['modId'])){

            $this->deleteDefinitionsCache($p_data['modId'], $p_data['dafId']);
        }

        return $resultSet;
    }

    public function edit($p_modId, $p_dafId, $p_data){

        $resultSet = $this->database->update('data_definitions', $p_data, "dafId = '$p_dafId' AND modId = '$p_modId'");

        $this->deleteDefinitionsCache($p_modId, $p_dafId);

        return $resultSet;
    }

    protected function deleteDefinitionsCache($p_modId, $p_dafId){

        $keys   = array();
        $keys[] = 'data_definitions_all';
        $keys[] = 'data_definitions_' . $p_modId . '_all';
        $keys[] = 'data_definitions_' . $p_modId . '_' . $p_dafId;
        $keys[] = 'data_definitions_all_ext';
        $keys[] = 'data_definitions_' . $p_modId . '_all_ext';
        $keys[] = 'data_definitions_' . $p_modId . '_' . $p_dafId . '_ext';

        $this->deleteMultipleCache($keys);
    }

    protected function defEncode($p_data){
        
        $p_data['defDafDefaultValue']           = \Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['defDafDefaultValue']);
        $p_data['defDafUiOptions']              = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['defDafUiOptions']));
        $p_data['defDafIndexOptions']           = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['defDafIndexOptions']));
        $p_data['defDafTypOptions']             = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['defDafTypOptions']));
        $p_data['defDafTypValidationOptions']   = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['defDafTypValidationOptions']));
        $p_data['defDafAttachFileOptions']      = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['defDafAttachFileOptions']));
        
        return $p_data;
    }

    protected function parentToChildExtend($p_parentData, $p_childData){

        $p_parentData['defDafDefaultValue']           = \Nubesys\Core\Utils\Struct::extendFieldValues($p_parentData['defDafDefaultValue'], $p_childData['defDafDefaultValue'], true);
        $p_parentData['defDafUiOptions']              = \Nubesys\Core\Utils\Struct::extendFieldValues($p_parentData['defDafUiOptions'], $p_childData['defDafUiOptions']);
        $p_parentData['defDafIndexOptions']           = \Nubesys\Core\Utils\Struct::extendFieldValues($p_parentData['defDafIndexOptions'], $p_childData['defDafIndexOptions']);
        $p_parentData['defDafTypOptions']             = \Nubesys\Core\Utils\Struct::extendFieldValues($p_parentData['defDafTypOptions'], $p_childData['defDafTypOptions']);
        $p_parentData['defDafTypValidationOptions']   = \Nubesys\Core\Utils\Struct::extendFieldValues($p_parentData['defDafTypValidationOptions'], $p_childData['defDafTypValidationOptions']);
        $p_parentData['defDafAttachFileOptions']      = \Nubesys\Core\Utils\Struct::extendFieldValues($p_parentData['defDafAttachFileOptions'], $p_childData['defDafAttachFileOptions']);

        return $p_parentData;
    }

    protected function fieldToDefinitionExtend($p_definitionData, $p_fieldData){

        $p_definitionData['defDafDefaultValue']           = \Nubesys\Core\Utils\Struct::extendFieldValues($p_fieldData['dafDefaultValue'], $p_definitionData['defDafDefaultValue'], true);
        $p_definitionData['defDafUiOptions']              = \Nubesys\Core\Utils\Struct::extendFieldValues($p_fieldData['dafUiOptions'], $p_definitionData['defDafUiOptions']);
        $p_definitionData['defDafIndexOptions']           = \Nubesys\Core\Utils\Struct::extendFieldValues($p_fieldData['dafIndexOptions'], $p_definitionData['defDafIndexOptions']);
        $p_definitionData['defDafTypOptions']             = \Nubesys\Core\Utils\Struct::extendFieldValues($p_fieldData['dafTypOptions'], $p_definitionData['defDafTypOptions']);
        $p_definitionData['defDafTypValidationOptions']   = \Nubesys\Core\Utils\Struct::extendFieldValues($p_fieldData['dafTypValidationOptions'], $p_definitionData['defDafTypValidationOptions']);
        $p_definitionData['defDafAttachFileOptions']      = \Nubesys\Core\Utils\Struct::extendFieldValues($p_fieldData['dafAttachFileOptions'], $p_definitionData['defDafAttachFileOptions']);

        return $p_definitionData;
    }

    private function getFieldData($p_dafId, $p_extended = true){

        if($this->fieldsCache->has($p_dafId)){

            $result = $this->fieldsCache->get($p_dafId);
        }else{

            $result = $this->field->get($p_dafId, $p_extended);

            $this->fieldsCache->set($p_dafId, $result);
        }

        return $result;
    }
}