<?php

namespace Nubesys\Data\Ui\Pages\Objects;

use Nubesys\Core\Ui\Pages\AppCrud;

class Fgroups extends AppCrud {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        //aka le llama antes al getobjectdefinition
    }

    //OVERRIDES
    protected function getObjectsDefinitions(){
        
        $result = array();

        $result['flgId']                                            = array();
        $result['flgId']['dafId']                                   = 'flgId';
        $result['flgId']['modId']                                   = 'fieldsgroups';
        $result['flgId']['typId']                                   = 'text';
        $result['flgId']['flgId']                                   = 'data';
        $result['flgId']['defOrder']                                = 1;
        $result['flgId']['defIsName']                               = false;
        $result['flgId']['defIsImage']                              = false;
        $result['flgId']['defDafIndexOptions']                      = new \stdClass();
        $result['flgId']['defDafDefaultValue']                      = '';
        $result['flgId']['defDafTypOptions']                        = new \stdClass();
        $result['flgId']['defDafUiOptions']                         = new \stdClass();
        $result['flgId']['defDafUiOptions']->help                  = '';
        $result['flgId']['defDafUiOptions']->icon                  = 'caret right';
        $result['flgId']['defDafUiOptions']->info                  = '';
        $result['flgId']['defDafUiOptions']->label                 = 'Group ID';
        $result['flgId']['defDafUiOptions']->hidden              = false;
        $result['flgId']['defDafUiOptions']->readOnly            = false;
        $result['flgId']['defDafUiOptions']->listable            = true;
        $result['flgId']['defDafUiOptions']->required            = true;
        $result['flgId']['defDafUiOptions']->sortable            = false;
        $result['flgId']['defDafUiOptions']->filterable          = false;
        $result['flgId']['defDafUiOptions']->searchable          = false;
        $result['flgId']['defDafTypValidationOptions']              = new \stdClass();
        $result['flgId']['defDafAttachFileOptions']                 = false;

        $result['flgName']                                          = array();
        $result['flgName']['dafId']                                 = 'flgName';
        $result['flgName']['modId']                                 = 'fieldsgroups';
        $result['flgName']['typId']                                 = 'text';
        $result['flgName']['flgId']                                 = 'data';
        $result['flgName']['defOrder']                              = 2;
        $result['flgName']['defIsName']                             = true;
        $result['flgName']['defIsImage']                            = false;
        $result['flgName']['defDafIndexOptions']                    = new \stdClass();
        $result['flgName']['defDafDefaultValue']                    = '';
        $result['flgName']['defDafTypOptions']                      = new \stdClass();
        $result['flgName']['defDafUiOptions']                       = new \stdClass();
        $result['flgName']['defDafUiOptions']->help                = '';
        $result['flgName']['defDafUiOptions']->icon                = 'caret right';
        $result['flgName']['defDafUiOptions']->info                = '';
        $result['flgName']['defDafUiOptions']->label               = 'Group Name';
        $result['flgName']['defDafUiOptions']->hidden            = false;
        $result['flgName']['defDafUiOptions']->readOnly          = false;
        $result['flgName']['defDafUiOptions']->listable          = true;
        $result['flgName']['defDafUiOptions']->required          = true;
        $result['flgName']['defDafUiOptions']->sortable          = false;
        $result['flgName']['defDafUiOptions']->filterable        = false;
        $result['flgName']['defDafUiOptions']->searchable        = false;
        $result['flgName']['defDafTypValidationOptions']            = new \stdClass();
        $result['flgName']['defDafAttachFileOptions']               = false;

        $return = array();

        foreach($result as $definition){

            $definitionsRow                                     = array();
            $definitionsRow["id"]                               = $definition['dafId'];
            $definitionsRow["type"]                             = $definition['typId'];
            $definitionsRow["group"]                            = $definition['flgId'];
            $definitionsRow["defaultValue"]                     = $definition['defDafDefaultValue'];
            $definitionsRow["order"]                            = $definition['defOrder'];
            $definitionsRow["isName"]                           = $definition['defIsName'];
            $definitionsRow["isImage"]                          = $definition['defIsImage'];
            $definitionsRow["isRelation"]                       = false;
            $definitionsRow["uiOptions"]                        = $definition['defDafUiOptions'];
            $definitionsRow["indexOptions"]                     = $definition['defDafIndexOptions'];
            $definitionsRow['typeOptions']                      = $definition['defDafTypOptions'];
            $definitionsRow['validationOptions']                = $definition['defDafTypValidationOptions'];
            $definitionsRow['attachFileOptions']                = $definition['defDafAttachFileOptions'];

            $return[$definitionsRow["id"]]  = $definitionsRow;
        }

        return $return;
    }

    protected function getObjectsData(){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return                  $dataEngine->getGroup(null);
    }

    protected function getObjectData($p_id){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->getGroup($p_id);
    }

    protected function getObjectToSaveId($p_data){
        
        return $p_data["flgId"];
    }

    protected function getTypesKeyNames(){

        $tableDataSource                           = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        return                                     $tableDataSource->rawQuery("SELECT flgId AS 'value', flgName AS 'label' FROM fields_groups");
    }

    protected function saveObjectsData($p_data){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->addGroup($p_data);
    }

    protected function editObjectsData($p_id, $p_data){
        
        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->editGroup($p_id, $p_data);
    }
}