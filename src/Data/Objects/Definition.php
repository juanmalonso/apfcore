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

class Definition extends Common
{

    protected $database;

    protected $field;
    protected $fieldsCache;

    public function __construct($p_di, $p_database, Field $p_field)
    {
        parent::__construct($p_di);

        $this->database = $p_database;

        $this->field         = $p_field;
        $this->fieldsCache   = new Register();
    }

    public function get($p_modelId = null, $p_fieldId = null, $p_extended = true){

        $result = false;

        $definitionsDataOptions     = array();

        $definitionsDataOptions['order'] = array("defOrder" => "ASC");

        if($p_modelId == null && $p_fieldId == null){

            $cacheKey       = 'data_definitions_all';
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey);
            }else {

                $definitionsDataOptions['rows'] = 1000;

                $definitionsData = $this->database->select('data_definitions', $definitionsDataOptions);

                if ($definitionsData) {

                    $result = array();

                    foreach ($definitionsData as $definitionData) {

                        $fieldData = $this->getFieldData($definitionData['dafId'], $p_extended);

                        if($p_extended){

                            $definitionData = $this->defExtend($definitionData, $fieldData);
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

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey);
            }else {

                $definitionsDataOptions['conditions'] = "modId = '" . $p_modelId . "'";

                $definitionsData = $this->database->select('data_definitions', $definitionsDataOptions);

                if ($definitionsData) {

                    $result = array();

                    foreach ($definitionsData as $definitionData) {

                        $fieldData = $this->getFieldData($definitionData['dafId'], $p_extended);

                        if($p_extended){

                            $definitionData = $this->defExtend($definitionData, $fieldData);
                        }

                        $definitionData['typId']                    = $fieldData['typId'];
                        $definitionData['typSaveAs']                = $fieldData['typSaveAs'];
                        $definitionData['typReferenceTo']           = $fieldData['typReferenceTo'];

                        $result[] = $definitionData;
                    }
                }
                
                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }else{

            $cacheKey       = 'data_definitions_' . $p_modelId . '_' . $p_fieldId;
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey);
            }else {

                $definitionsDataOptions['conditions'] = "modId = '" . $p_modelId . "' AND dafId = '" . $p_fieldId . "'";

                $definitionData = $this->database->selectOne('data_definitions', $definitionsDataOptions);

                $fieldData = $this->getFieldData($definitionData['dafId'], $p_extended);

                if($p_extended){

                    $definitionData = $this->defExtend($definitionData, $fieldData);
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

        return $resultSet;
    }

    public function edit($p_modId, $p_dafId, $p_data){

        $resultSet = $this->database->update('data_definitions', $p_data, "dafId = '$p_dafId' AND modId = '$p_modId'");

        $cacheKeys       = array('data_definitions_' . $p_modId . '_' . $p_dafId, 'data_definitions_' . $p_modId . '_all', 'data_definitions_all');

        foreach($cacheKeys as $key){

            if($this->hasCache($cacheContext, $key, $cacheType)){

                $this->deleteCache($cacheContext, $key, $cacheType);
            }
        }

        return $resultSet;
    }

    protected function defExtend($p_data, $p_fieldData){

        $p_data['defDafDefaultValue']           = \Nubesys\Platform\Util\Parse::extendFieldValues($p_fieldData['dafDefaultValue'], $p_data['defDafDefaultValue'], true);
        $p_data['defDafUiOptions']              = \Nubesys\Platform\Util\Parse::extendFieldValues($p_fieldData['dafUiOptions'], $p_data['defDafUiOptions']);
        $p_data['defDafIndexOptions']           = \Nubesys\Platform\Util\Parse::extendFieldValues($p_fieldData['dafIndexOptions'], $p_data['defDafIndexOptions']);
        $p_data['defDafTypOptions']             = \Nubesys\Platform\Util\Parse::extendFieldValues($p_fieldData['dafTypOptions'], $p_data['defDafTypOptions']);
        $p_data['defDafTypValidationOptions']   = \Nubesys\Platform\Util\Parse::extendFieldValues($p_fieldData['dafTypValidationOptions'], $p_data['defDafTypValidationOptions']);
        $p_data['defDafAttachFileOptions']      = \Nubesys\Platform\Util\Parse::extendFieldValues($p_fieldData['dafAttachFileOptions'], $p_data['defDafAttachFileOptions']);

        return $p_data;
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