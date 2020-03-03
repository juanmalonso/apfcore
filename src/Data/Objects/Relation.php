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

    public function add($p_data){
        
        $result = $this->database->insert('data_relations', $p_data);

        return $result;
    }

    public function get($p_relId){

        $result = false;
        $jsonFields = array('relIndexOptions','relUiOptions');

        //TODO : Cache de Types

        $relationDataOptions     = array();

        if($p_relId == null) {

            $cacheKey       = 'data_relation_all';
            $cacheLifetime  = 3600;
            
            if($this->hasCache($cacheKey)){

                $result = $this->getCahe($cacheKey);
            }else {
                
                $relationDataOptions['rows'] = 1000;

                $resultSet = $this->database->select('data_relations', $relationDataOptions);
                
                $result = array();
                
                foreach ($resultSet as $relation) {

                    $relationTemp = array();

                    foreach ($relation as $field => $value) {

                        $relationTemp[$field] = \Nubesys\Platform\Util\Parse::decodeJsonField($field, $value, $jsonFields);
                    }

                    $result[] = $relationTemp;
                }

                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }else{

            $cacheKey       = 'data_relation_' . $p_relId;
            $cacheLifetime  = 3600;
            
            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey);
            }else {
            
                $relationDataOptions['conditions'] = "modId = '" . $p_relId . "'";

                $resultSet = $this->database->selectOne('data_relations', $relationDataOptions);
                
                $result = array();
                
                foreach ($resultSet as $field => $value) {

                    $result[$field] = \Nubesys\Platform\Util\Parse::decodeJsonField($field, $value, $jsonFields);
                }

                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }

        return $result;
    }

    public function getByModel($p_modId, $p_direction = 'LEFT'){
        
        $result = false;
        $jsonFields = array('relIndexOptions','relUiOptions');

        $cacheKey       = 'data_relations_' . $p_modId . '_in_' . $p_direction;
        $cacheLifetime  = 3600;
        
        if($this->hasCache($cacheKey)){

            $result = $this->getCache($cacheKey);
        }else {
            
            $relationDataOptions     = array();
            $relationDataOptions['rows'] = 1000;

            if($p_direction == 'LEFT'){

                $relationDataOptions['conditions'] = "relLeftModId = '" . $p_modId . "'";
            }elseif($p_direction == 'RIGHT'){

                $relationDataOptions['conditions'] = "relRightModId = '" . $p_modId . "'";
            }

            $resultSet = $this->database->select('data_relations', $relationDataOptions);
            
            $result = array();
            
            if($resultSet){
                foreach ($resultSet as $relation) {

                    $relationTemp = array();

                    foreach ($relation as $field => $value) {

                        $relationTemp[$field] = \Nubesys\Platform\Util\Parse::decodeJsonField($field, $value, $jsonFields);
                    }
                
                    $result[] = $relationTemp;
                }
            }
            $this->setCache($cacheKey, $result, $cacheLifetime);
        }
        
        return $result;
    }

    public function edit($p_id, $p_data){

        $resultSet = $this->database->update('data_relations', $p_data, "modId = '$p_id'");

        $cacheKeys       = array('data_relation_' . $p_id, 'data_relation_all');

        foreach($cacheKeys as $key){

            if($this->hasCache($key)){

                $this->deleteCache($key);
            }
        }

        return $resultSet;
    }
}