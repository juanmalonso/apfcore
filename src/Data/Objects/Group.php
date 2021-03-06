<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 27/07/16
 * Time: 03:23 PM
 */

namespace Nubesys\Data\Objects;

use Nubesys\Core\Common;

class Group extends Common
{

    protected $database;
    protected $elastic;

    protected $type;

    public function __construct($p_di, $p_database)
    {
        parent::__construct($p_di);

        $this->database = $p_database;
    }

    public function get($p_groupId = null){

        $result = false;

        $fieldsGroupsOptions     = array();

        if($p_groupId == null){

            $cacheKey       = 'data_groups_all';
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $groupsData = $this->database->select('fields_groups', $fieldsGroupsOptions);

                if ($groupsData) {

                    $result = array();

                    foreach ($groupsData as $groupData) {

                        $result[] = $groupData;
                    }
                }

                $this->setCache($cacheKey, $result, $cacheLifetime);
            }
        }else{

            $cacheKey       = 'data_group_' . $p_groupId;
            $cacheLifetime  = 3600;

            if($this->hasCache($cacheKey)){

                $result = $this->getCache($cacheKey, array());
            }else {

                $fieldsGroupsOptions['conditions'] = "flgId = '" . $p_groupId . "'";

                $groupData = $this->database->selectOne('fields_groups', $fieldsGroupsOptions);

                $result = $groupData;

                $this->setCache($cacheKey, $result, $cacheLifetime);

            }
        }

        return $result;
    }

    public function add($p_data){

        $resultSet = $this->database->insert('fields_groups', $p_data);

        if(isset($p_data['flgId'])){

            $this->deleteGroupCache($p_data['flgId']);
        }

        return $resultSet;
    }

    public function edit($p_id, $p_data){

        $resultSet = $this->database->update('fields_groups', $p_data, "flgId = '$p_id'");

        $this->deleteGroupCache($p_id);

        return $resultSet;
    }

    protected function deleteGroupCache($p_flgId){

        $keys   = array();
        $keys[] = 'data_groups_all';
        $keys[] = 'data_group_' . $p_flgId;

        $this->deleteMultipleCache($keys);
    }

}