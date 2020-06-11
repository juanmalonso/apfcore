<?php

namespace Nubesys\Data\Ui\Pages\Objects;

use Nubesys\Core\Ui\Pages\AppCrud;

class Fields extends AppCrud {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        //aka le llama antes al getobjectdefinition
    }

    //OVERRIDES
    protected function getObjectsDefinitions(){
        
        $result = array();

        $result['dafId']                                            = array();
        $result['dafId']['dafId']                                   = 'dafId';
        $result['dafId']['modId']                                   = 'fields';
        $result['dafId']['typId']                                   = 'text';
        $result['dafId']['flgId']                                   = 'data';
        $result['dafId']['defOrder']                                = 1;
        $result['dafId']['defIsName']                               = true;
        $result['dafId']['defIsImage']                              = false;
        $result['dafId']['defDafIndexOptions']                      = new \stdClass();
        $result['dafId']['defDafDefaultValue']                      = '';
        $result['dafId']['defDafTypOptions']                        = new \stdClass();
        $result['dafId']['defDafUiOptions']                         = new \stdClass();
        $result['dafId']['defDafUiOptions']->help                  = '';
        $result['dafId']['defDafUiOptions']->icon                  = 'caret right';
        $result['dafId']['defDafUiOptions']->info                  = '';
        $result['dafId']['defDafUiOptions']->label                 = 'Field Name';
        $result['dafId']['defDafUiOptions']->hidden              = false;
        $result['dafId']['defDafUiOptions']->readOnly            = ($this->action == 'edit') ? true : false;
        $result['dafId']['defDafUiOptions']->listable            = true;
        $result['dafId']['defDafUiOptions']->required            = true;
        $result['dafId']['defDafUiOptions']->sortable            = false;
        $result['dafId']['defDafUiOptions']->filterable          = false;
        $result['dafId']['defDafUiOptions']->searchable          = false;
        $result['dafId']['defDafTypValidationOptions']              = new \stdClass();
        $result['dafId']['defDafAttachFileOptions']                 = false;

        $result['typId']                                            = array();
        $result['typId']['dafId']                                   = 'typId';
        $result['typId']['modId']                                   = 'fields';
        $result['typId']['typId']                                   = 'options';
        $result['typId']['flgId']                                   = 'data';
        $result['typId']['defOrder']                                = 2;
        $result['typId']['defIsName']                               = false;
        $result['typId']['defIsImage']                              = false;
        $result['typId']['defDafIndexOptions']                      = new \stdClass();
        $result['typId']['defDafDefaultValue']                      = '';
        $result['typId']['defDafTypOptions']                        = new \stdClass();
        $result['typId']['defDafTypOptions']->data                  = $this->getTypesKeyNames();
        $result['typId']['defDafUiOptions']                         = new \stdClass();
        $result['typId']['defDafUiOptions']->help                  = '';
        $result['typId']['defDafUiOptions']->icon                  = 'caret right';
        $result['typId']['defDafUiOptions']->info                  = '';
        $result['typId']['defDafUiOptions']->label                 = 'Data Type';
        $result['typId']['defDafUiOptions']->hidden              = false;
        $result['typId']['defDafUiOptions']->readOnly            = false;
        $result['typId']['defDafUiOptions']->listable            = true;
        $result['typId']['defDafUiOptions']->required            = true;
        $result['typId']['defDafUiOptions']->sortable            = false;
        $result['typId']['defDafUiOptions']->filterable          = false;
        $result['typId']['defDafUiOptions']->searchable          = false;
        $result['typId']['defDafTypValidationOptions']                  = new \stdClass();
        $result['typId']['defDafAttachFileOptions']                     = new \stdClass();

        $result['dafDefaultValue']                                    = array();
        $result['dafDefaultValue']['dafId']                           = 'dafDefaultValue';
        $result['dafDefaultValue']['modId']                           = 'fields';
        $result['dafDefaultValue']['typId']                           = 'text';
        $result['dafDefaultValue']['flgId']                           = 'data';
        $result['dafDefaultValue']['defOrder']                        = 3;
        $result['dafDefaultValue']['defIsName']                       = false;
        $result['dafDefaultValue']['defIsImage']                      = false;
        $result['dafDefaultValue']['defDafIndexOptions']              = new \stdClass();
        $result['dafDefaultValue']['defDafDefaultValue']              = '';
        $result['dafDefaultValue']['defDafTypOptions']                = new \stdClass();
        $result['dafDefaultValue']['defDafUiOptions']                 = new \stdClass();
        $result['dafDefaultValue']['defDafUiOptions']->help          = '';
        $result['dafDefaultValue']['defDafUiOptions']->icon          = 'caret right';
        $result['dafDefaultValue']['defDafUiOptions']->info          = '';
        $result['dafDefaultValue']['defDafUiOptions']->label         = 'Default Value';
        $result['dafDefaultValue']['defDafUiOptions']->hidden      = false;
        $result['dafDefaultValue']['defDafUiOptions']->readOnly    = false;
        $result['dafDefaultValue']['defDafUiOptions']->listable    = true;
        $result['dafDefaultValue']['defDafUiOptions']->required    = false;
        $result['dafDefaultValue']['defDafUiOptions']->sortable    = false;
        $result['dafDefaultValue']['defDafUiOptions']->filterable  = false;
        $result['dafDefaultValue']['defDafUiOptions']->searchable  = false;
        $result['dafDefaultValue']['defDafTypValidationOptions']      = new \stdClass();
        $result['dafDefaultValue']['defDafAttachFileOptions']         = false;

        $result['dafUiOptions']                                     = array();
        $result['dafUiOptions']['dafId']                            = 'dafUiOptions';
        $result['dafUiOptions']['modId']                            = 'fields';
        $result['dafUiOptions']['typId']                            = 'json';
        $result['dafUiOptions']['flgId']                            = 'ui';
        $result['dafUiOptions']['defOrder']                         = 4;
        $result['dafUiOptions']['defIsName']                        = false;
        $result['dafUiOptions']['defIsImage']                       = false;
        $result['dafUiOptions']['defDafIndexOptions']               = new \stdClass();
        $result['dafUiOptions']['defDafDefaultValue']               = new \stdClass();
        $result['dafUiOptions']['defDafDefaultValue']->help        = '';
        $result['dafUiOptions']['defDafDefaultValue']->icon        = 'caret right';
        $result['dafUiOptions']['defDafDefaultValue']->info        = '';
        $result['dafUiOptions']['defDafDefaultValue']->label       = 'UI Options';
        $result['dafUiOptions']['defDafDefaultValue']->hidden      = false;
        $result['dafUiOptions']['defDafDefaultValue']->readOnly    = false;
        $result['dafUiOptions']['defDafDefaultValue']->listable    = true;
        $result['dafUiOptions']['defDafDefaultValue']->required    = true;
        $result['dafUiOptions']['defDafDefaultValue']->sortable    = true;
        $result['dafUiOptions']['defDafDefaultValue']->filterable  = true;
        $result['dafUiOptions']['defDafDefaultValue']->searchable  = true;
        $result['dafUiOptions']['defDafTypOptions']                 = new \stdClass();
        $result['dafUiOptions']['defDafUiOptions']                  = new \stdClass();
        $result['dafUiOptions']['defDafUiOptions']->help          = '';
        $result['dafUiOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['dafUiOptions']['defDafUiOptions']->info          = '';
        $result['dafUiOptions']['defDafUiOptions']->label         = 'Ui Options';
        $result['dafUiOptions']['defDafUiOptions']->hidden      = false;
        $result['dafUiOptions']['defDafUiOptions']->readOnly    = false;
        $result['dafUiOptions']['defDafUiOptions']->listable    = true;
        $result['dafUiOptions']['defDafUiOptions']->required    = true;
        $result['dafUiOptions']['defDafUiOptions']->sortable    = false;
        $result['dafUiOptions']['defDafUiOptions']->filterable  = false;
        $result['dafUiOptions']['defDafUiOptions']->searchable  = false;
        $result['dafUiOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['dafUiOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['dafIndexOptions']                                     = array();
        $result['dafIndexOptions']['dafId']                            = 'dafIndexOptions';
        $result['dafIndexOptions']['modId']                            = 'fields';
        $result['dafIndexOptions']['typId']                            = 'json';
        $result['dafIndexOptions']['flgId']                            = 'index';
        $result['dafIndexOptions']['defOrder']                         = 5;
        $result['dafIndexOptions']['defIsName']                        = false;
        $result['dafIndexOptions']['defIsImage']                       = false;
        $result['dafIndexOptions']['defDafIndexOptions']               = new \stdClass();
        $result['dafIndexOptions']['defDafDefaultValue']               = new \stdClass();
        $result['dafIndexOptions']['defDafDefaultValue']->indexable    = false;
        $result['dafIndexOptions']['defDafDefaultValue']->mapping      = new \stdClass();
        $result['dafIndexOptions']['defDafTypOptions']                 = new \stdClass();
        $result['dafIndexOptions']['defDafUiOptions']                  = new \stdClass();
        $result['dafIndexOptions']['defDafUiOptions']->help          = '';
        $result['dafIndexOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['dafIndexOptions']['defDafUiOptions']->info          = '';
        $result['dafIndexOptions']['defDafUiOptions']->label         = 'Indexing Options';
        $result['dafIndexOptions']['defDafUiOptions']->hidden      = false;
        $result['dafIndexOptions']['defDafUiOptions']->readOnly    = false;
        $result['dafIndexOptions']['defDafUiOptions']->listable    = true;
        $result['dafIndexOptions']['defDafUiOptions']->required    = true;
        $result['dafIndexOptions']['defDafUiOptions']->sortable    = false;
        $result['dafIndexOptions']['defDafUiOptions']->filterable  = false;
        $result['dafIndexOptions']['defDafUiOptions']->searchable  = false;
        $result['dafIndexOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['dafIndexOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['dafTypOptions']                                     = array();
        $result['dafTypOptions']['dafId']                            = 'dafTypOptions';
        $result['dafTypOptions']['modId']                            = 'fields';
        $result['dafTypOptions']['typId']                            = 'json';
        $result['dafTypOptions']['flgId']                            = 'options';
        $result['dafTypOptions']['defOrder']                         = 6;
        $result['dafTypOptions']['defIsName']                        = false;
        $result['dafTypOptions']['defIsImage']                       = false;
        $result['dafTypOptions']['defDafIndexOptions']               = new \stdClass();
        $result['dafTypOptions']['defDafDefaultValue']               = new \stdClass();;
        $result['dafTypOptions']['defDafTypOptions']                 = new \stdClass();
        $result['dafTypOptions']['defDafUiOptions']                  = new \stdClass();
        $result['dafTypOptions']['defDafUiOptions']->help          = '';
        $result['dafTypOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['dafTypOptions']['defDafUiOptions']->info          = '';
        $result['dafTypOptions']['defDafUiOptions']->label         = 'Type Extra Options';
        $result['dafTypOptions']['defDafUiOptions']->hidden      = false;
        $result['dafTypOptions']['defDafUiOptions']->readOnly    = false;
        $result['dafTypOptions']['defDafUiOptions']->listable    = true;
        $result['dafTypOptions']['defDafUiOptions']->required    = true;
        $result['dafTypOptions']['defDafUiOptions']->sortable    = false;
        $result['dafTypOptions']['defDafUiOptions']->filterable  = false;
        $result['dafTypOptions']['defDafUiOptions']->searchable  = false;
        $result['dafTypOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['dafTypOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['dafTypValidationOptions']                                     = array();
        $result['dafTypValidationOptions']['dafId']                            = 'dafTypValidationOptions';
        $result['dafTypValidationOptions']['modId']                            = 'fields';
        $result['dafTypValidationOptions']['typId']                            = 'json';
        $result['dafTypValidationOptions']['flgId']                            = 'validation';
        $result['dafTypValidationOptions']['defOrder']                         = 6;
        $result['dafTypValidationOptions']['defIsName']                        = false;
        $result['dafTypValidationOptions']['defIsImage']                       = false;
        $result['dafTypValidationOptions']['defDafIndexOptions']               = new \stdClass();
        $result['dafTypValidationOptions']['defDafDefaultValue']               = new \stdClass();;
        $result['dafTypValidationOptions']['defDafTypOptions']                 = new \stdClass();
        $result['dafTypValidationOptions']['defDafUiOptions']                  = new \stdClass();
        $result['dafTypValidationOptions']['defDafUiOptions']->help          = '';
        $result['dafTypValidationOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['dafTypValidationOptions']['defDafUiOptions']->info          = '';
        $result['dafTypValidationOptions']['defDafUiOptions']->label         = 'Validations Options';
        $result['dafTypValidationOptions']['defDafUiOptions']->hidden      = false;
        $result['dafTypValidationOptions']['defDafUiOptions']->readOnly    = false;
        $result['dafTypValidationOptions']['defDafUiOptions']->listable    = true;
        $result['dafTypValidationOptions']['defDafUiOptions']->required    = true;
        $result['dafTypValidationOptions']['defDafUiOptions']->sortable    = false;
        $result['dafTypValidationOptions']['defDafUiOptions']->filterable  = false;
        $result['dafTypValidationOptions']['defDafUiOptions']->searchable  = false;
        $result['dafTypValidationOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['dafTypValidationOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['dafAttachFileOptions']                                     = array();
        $result['dafAttachFileOptions']['dafId']                            = 'dafAttachFileOptions';
        $result['dafAttachFileOptions']['modId']                            = 'fields';
        $result['dafAttachFileOptions']['typId']                            = 'json';
        $result['dafAttachFileOptions']['flgId']                            = 'file';
        $result['dafAttachFileOptions']['defOrder']                         = 7;
        $result['dafAttachFileOptions']['defIsName']                        = false;
        $result['dafAttachFileOptions']['defIsImage']                       = false;
        $result['dafAttachFileOptions']['defDafIndexOptions']               = new \stdClass();
        $result['dafAttachFileOptions']['defDafDefaultValue']               = new \stdClass();;
        $result['dafAttachFileOptions']['defDafTypOptions']                 = new \stdClass();
        $result['dafAttachFileOptions']['defDafUiOptions']                  = new \stdClass();
        $result['dafAttachFileOptions']['defDafUiOptions']->help          = '';
        $result['dafAttachFileOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['dafAttachFileOptions']['defDafUiOptions']->info          = '';
        $result['dafAttachFileOptions']['defDafUiOptions']->label         = 'Attach File Options';
        $result['dafAttachFileOptions']['defDafUiOptions']->hidden      = false;
        $result['dafAttachFileOptions']['defDafUiOptions']->readOnly    = false;
        $result['dafAttachFileOptions']['defDafUiOptions']->listable    = true;
        $result['dafAttachFileOptions']['defDafUiOptions']->required    = true;
        $result['dafAttachFileOptions']['defDafUiOptions']->sortable    = false;
        $result['dafAttachFileOptions']['defDafUiOptions']->filterable  = false;
        $result['dafAttachFileOptions']['defDafUiOptions']->searchable  = false;
        $result['dafAttachFileOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['dafAttachFileOptions']['defDafAttachFileOptions']          = new \stdClass();

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
        
        return                  $dataEngine->getField(null, false);
    }

    protected function getObjectData($p_id){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->getField($p_id, false);
    }

    protected function getObjectToSaveId($p_data){
        
        return $p_data["dafId"];
    }

    protected function getTypesKeyNames(){

        $result                         = array();

        $tableDataSource                = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        $queryResult                    = $tableDataSource->rawQuery("SELECT typId AS 'value', typId AS 'label' FROM data_types");

        foreach($queryResult as $row){

            $result[]                   = $this->toObject($row);
        }

        return $result;
    }

    protected function saveObjectsData($p_data){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->addField($p_data);
    }

    protected function editObjectsData($p_id, $p_data){
        
        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->editField($p_id, $p_data);
    }
}