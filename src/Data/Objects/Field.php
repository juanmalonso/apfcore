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

class Field extends Common
{

    protected $database;

    protected $type;
    protected $typesCache;

    public function __construct($p_di, $p_database, Type $p_type)
    {
        parent::__construct($p_di);

        $this->database = $p_database;

        $this->type         = $p_type;
        $this->typesCache   = new Register();
    }

    public function get($p_fieldId = null, $p_extended = true){

        $result = false;

        $fieldDataOptions     = array();

        if($p_fieldId == null){

            $cacheKey       = 'data_field_all';
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $fieldDataOptions['rows'] = 1000;

                $fieldsData = $this->database->select('data_fields', $fieldDataOptions);
                
                if ($fieldsData) {

                    $result = array();

                    foreach ($fieldsData as $fieldData) {

                        $typeData = $this->getTypeData($fieldData['typId']);

                        if($p_extended){

                            $fieldData = $this->fieldExtend($fieldData, $typeData);
                        }else{
        
                            $fieldData = $this->fieldEncode($fieldData);
                        }

                        $fieldData['typId']                    = $typeData['typId'];
                        $fieldData['typSaveAs']                = $typeData['typSaveAs'];
                        $fieldData['typReferenceTo']           = $typeData['typReferenceTo'];

                        $result[] = $fieldData;
                    }
                }
                
                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }else{

            $cacheKey       = 'data_field_' . $p_fieldId;
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $fieldDataOptions['conditions'] = "dafId = '" . $p_fieldId . "'";

                $fieldData = $this->database->selectOne('data_fields', $fieldDataOptions);

                $typeData = $this->getTypeData($fieldData['typId']);

                if($p_extended){

                    $fieldData = $this->fieldExtend($fieldData, $typeData);
                }else{
        
                    $fieldData = $this->fieldEncode($fieldData);
                }

                $fieldData['typId']                    = $typeData['typId'];
                $fieldData['typSaveAs']                = $typeData['typSaveAs'];
                $fieldData['typReferenceTo']           = $typeData['typReferenceTo'];

                $result = $fieldData;

                $this->setCache($cacheKey, $result, $cacheLifetime);

            }
        }

        return $result;
    }

    public function add($p_data){

        $resultSet = $this->database->insert('data_fields', $p_data);

        if(isset($p_data['dafId'])){

            $this->deleteFieldCache($p_data['dafId']);
        }

        return $resultSet;
    }

    public function edit($p_id, $p_data){
        
        $resultSet = $this->database->update('data_fields', $p_data, "dafId = '$p_id'");

        $this->deleteFieldCache($p_id);

        return $resultSet;
    }

    protected function deleteFieldCache($p_dafId){

        $keys   = array();
        $keys[] = 'data_field_all';
        $keys[] = 'data_field_' . $p_dafId;

        $this->deleteMultipleCache($keys);
    }

    protected function fieldEncode($p_data){
        
        $p_data['dafUiOptions']             = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafUiOptions']));
        $p_data['dafIndexOptions']          = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafIndexOptions']));
        $p_data['dafTypOptions']            = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafTypOptions']));
        $p_data['dafTypValidationOptions']  = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafTypValidationOptions']));
        $p_data['dafAttachFileOptions']     = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafAttachFileOptions']));
        
        return $p_data;
    }

    protected function fieldExtend($p_data, $p_typeData){

        $p_data['dafUiOptions']             = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafUiOptions']));
        $p_data['dafIndexOptions']          = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafIndexOptions']));
        $p_data['dafTypOptions']            = \Nubesys\Core\Utils\Struct::extendFieldValues($p_typeData['typOptions'], $p_data['dafTypOptions']);
        $p_data['dafTypValidationOptions']  = \Nubesys\Core\Utils\Struct::extendFieldValues($p_typeData['typValidationOptions'], $p_data['dafTypValidationOptions']);
        $p_data['dafAttachFileOptions']     = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['dafAttachFileOptions']));

        return $p_data;
    }

    private function getTypeData($p_typId){

        if($this->typesCache->has($p_typId)){

            $result = $this->typesCache->get($p_typId);
        }else{

            $result = $this->type->get($p_typId);

            $this->typesCache->set($p_typId, $result);
        }

        return $result;
    }
}