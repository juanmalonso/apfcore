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

                $result = $this->getCache($cacheKey);
            }else {

                $result = $this->database->select('data_types', $typeDataOptions);

                $this->setCache($cacheKey, $result, $cacheLifetime);

            }

        }else{

            $cacheKey       = 'data_type_' . $p_typeId;
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey);
            }else {

                $typeDataOptions['conditions'] = "typId = '" . $p_typeId . "'";

                $result = $this->database->selectOne('data_types', $typeDataOptions);

                $this->setCache($cacheKey, $result, $cacheLifetime);

            }
        }

        return $result;

    }

    public function add($p_data){

        $resultSet = $this->database->insert('data_types', $p_data);

        return $resultSet;
    }

    public function edit($p_id, $p_data){

        $resultSet = $this->database->update('data_types', $p_data, "typId = '$p_id'");

        $cacheKeys       = array('data_type_' . $p_id, 'data_type_all');
        $cacheType      = 'redis';

        foreach($cacheKeys as $key){

            if($this->hasCache($key)){

                $this->deleteCache($key);
            }
        }

        return $resultSet;
    }
}