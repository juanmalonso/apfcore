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

                $result = $this->getCache($cacheKey);
            }else {

                $fieldDataOptions['rows'] = 1000;

                $fieldsData = $this->database->select('data_fields', $fieldDataOptions);

                if ($fieldsData) {

                    $result = array();

                    foreach ($fieldsData as $fieldData) {

                        $typeData = $this->getTypeData($fieldData['typId']);

                        if($p_extended){

                            $fieldData = $this->fieldExtend($fieldData, $typeData);
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

                $result = $this->getCache($cacheKey);
            }else {

                $fieldDataOptions['conditions'] = "dafId = '" . $p_fieldId . "'";

                $fieldData = $this->database->selectOne('data_fields', $fieldDataOptions);

                $typeData = $this->getTypeData($fieldData['typId']);

                if($p_extended){

                    $fieldData = $this->fieldExtend($fieldData, $typeData);
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

        return $resultSet;
    }

    public function edit($p_id, $p_data){

        $resultSet = $this->database->update('data_fields', $p_data, "dafId = '$p_id'");

        $cacheKeys       = array('data_field_' . $p_id, 'data_field_all');

        foreach($cacheKeys as $key){

            if($this->hasCache($key)){

                $this->deleteCache($key);
            }
        }

        return $resultSet;
    }

    protected function fieldExtend($p_data, $p_typeData){

        $p_data['dafTypOptions']            = \Nubesys\Platform\Util\Parse::extendFieldValues($p_typeData['typOptions'], $p_data['dafTypOptions']);
        $p_data['dafTypValidationOptions']  = \Nubesys\Platform\Util\Parse::extendFieldValues($p_typeData['typValidationOptions'], $p_data['dafTypValidationOptions']);

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