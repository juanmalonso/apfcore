<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 27/07/16
 * Time: 03:23 PM
 */

namespace Nubesys\Data\Objects;

use Nubesys\Core\Common;

class Relation extends Common
{

    protected $database;

    public function __construct($p_di, $p_database)
    {
        parent::__construct($p_di);

        $this->database = $p_database;
    }

    public function has($p_relation){

        $result = false;

        $selectOptions                  = array();
        $selectOptions['conditions']    = "modId = '" . $p_relation . "'";

        $selectResult = $this->database->selectOne('data_relations',$selectOptions);

        if(is_array($selectResult)){

            $result = true;
        }

        return $result;
    }

    public function get($p_relId){

        $result = false;

        $relationDataOptions     = array();

        if($p_relId == null) {

            $cacheKey       = 'data_relation_all';
            $cacheLifetime  = 3600;
            
            if($this->hasCache($cacheKey)){

                $result = $this->getCahe($cacheKey);
            }else {
                
                $relationDataOptions['rows']    = 1000;

                $resultSet                      = $this->database->select('data_relations', $relationDataOptions);

                if(is_array($resultSet)){

                    $result = array();

                    foreach ($resultSet as $relation) {

                        $result[] = $this->relEncode($relation);
                    }

                    if(is_array($result) && count($result) > 0){

                        $this->setCache($cacheKey, $result, $cacheLifetime);
                    }
                }else{

                    //TODO : NO
                }
            }
        }else{

            $cacheKey       = 'data_relation_' . $p_relId;
            $cacheLifetime  = 3600;
            
            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {
            
                $relationDataOptions['conditions'] = "modId = '" . $p_relId . "'";

                $resultSet = $this->database->selectOne('data_relations', $relationDataOptions);
                
                if(is_array($resultSet) && count($resultSet) > 0){
                
                    $result = $this->relEncode($resultSet);
                    
                    $this->setCache($cacheKey, $result, $cacheLifetime);
                }
            }
        }

        return $result;
    }

    public function getByModel($p_modId, $p_direction = 'IN'){
        
        $result = array();
        
        $cacheKey       = 'data_relations_' . $p_modId . '_' . $p_direction . '_relations' . time();
        $cacheLifetime  = 3600;
        
        if($this->hasCache($cacheKey)){

            $result = $this->getCache($cacheKey, array());
        }else {
            
            $relationDataOptions     = array();
            $relationDataOptions['rows'] = 1000;

            $relationDataOptions['conditions']      = "(relLeftModId = '" . $p_modId . "' AND relLeftDirection = '" . $p_direction . "') OR (relRightModId = '" . $p_modId . "' AND relRightDirection = '" . $p_direction . "')";

            $resultSet = $this->database->select('data_relations', $relationDataOptions);
            
            if(is_array($resultSet)){

                $result = array();

                foreach ($resultSet as $relation) {

                    $result[] = $this->relEncode($relation);
                }

                if(is_array($result) && count($result) > 0){

                    $this->setCache($cacheKey, $result, $cacheLifetime);
                }
            }else{

                //TODO : NO
            }
        }
        
        return $result;
    }

    public function add($p_data){
        
        $result = $this->database->insert('data_relations', $p_data);

        if(isset($p_data['modId'])){

            $this->deleteRelationCache($p_data['modId']);
        }

        return $result;
    }

    public function edit($p_id, $p_data){

        $resultSet = $this->database->update('data_relations', $p_data, "modId = '$p_id'");

        $this->deleteRelationCache($p_id);

        return $resultSet;
    }

    protected function deleteRelationCache($p_relId){

        $keys   = array();
        $keys[] = 'data_relation_all';
        $keys[] = 'data_relation_' . $p_relId;
        $keys[] = 'data_relations_' . $p_relId . '_in_LEFT';
        $keys[] = 'data_relations_' . $p_relId . '_in_RIGHT';

        $this->deleteMultipleCache($keys);
    }

    protected function relEncode($p_data){
        
        $p_data['relIndexOptions']              = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['relIndexOptions']));
        $p_data['relUiOptions']                 = \Nubesys\Core\Utils\Struct::toObject(\Nubesys\Core\Utils\Struct::encodeFieldValue($p_data['relUiOptions']));
        
        return $p_data;
    }
}