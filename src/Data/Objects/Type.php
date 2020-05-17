<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 27/07/16
 * Time: 03:23 PM
 */

namespace Nubesys\Data\Objects;

use Nubesys\Core\Common;

class Type extends Common
{

    protected $database;

    public function __construct($p_di, $p_database)
    {
        parent::__construct($p_di);

        $this->database = $p_database;
    }

    /**
     * @param null $p_typeId
     * @return bool|array
     */
    public function get($p_typeId = null){

        $result = false;

        $typeDataOptions     = array();

        if($p_typeId == null) {

            $cacheKey       = 'data_type_all';
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $resultSet = $this->database->select('data_types', $typeDataOptions);

                if(is_array($resultSet)){

                    $result = array();

                    foreach ($resultSet as $type) {

                        $result[] = $this->typEncode($type);
                    }

                    if(is_array($result) && count($result) > 0){

                        $this->setCache($cacheKey, $result, $cacheLifetime);
                    }
                }else{

                    //TODO : NO
                }
            }

        }else{

            $cacheKey       = 'data_type_' . $p_typeId;
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $typeDataOptions['conditions'] = "typId = '" . $p_typeId . "'";

                $resultSet = $this->database->selectOne('data_types', $typeDataOptions);
                
                if(is_array($resultSet) && count($resultSet) > 0){
                
                    $result = $this->typEncode($resultSet);
                    
                    $this->setCache($cacheKey, $result, $cacheLifetime);
                }

            }
        }

        return $result;

    }

    public function add($p_data){

        $resultSet = $this->database->insert('data_types', $p_data);

        if(isset($p_data['typId'])){

            $this->deleteTypeCache($p_data['typId']);
        }

        return $resultSet;
    }

    public function edit($p_id, $p_data){

        $resultSet = $this->database->update('data_types', $p_data, "typId = '$p_id'");

        $this->deleteTypeCache($p_id);

        return $resultSet;
    }

    protected function deleteTypeCache($p_typId){

        $keys   = array();
        $keys[] = 'data_type_all';
        $keys[] = 'data_type_' . $p_typId;

        $this->deleteMultipleCache($keys);
    }

    protected function typEncode($p_data){
        
        $p_data['typOptions']             = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['typOptions']));
        $p_data['typValidationOptions']   = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['typValidationOptions']));
        
        return $p_data;
    }
}