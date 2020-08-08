<?php

namespace Nubesys\Data\Ui\Pages\Objects;

use Nubesys\Core\Ui\Pages\AppCrud;

class Types extends AppCrud {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        //aka le llama antes al getobjectdefinition
    }

    //OVERRIDES
    protected function getObjectsDefinitions(){
        
        $result = array();

        $result['typId']                                            = array();
        $result['typId']['dafId']                                   = 'typId';
        $result['typId']['modId']                                   = 'types';
        $result['typId']['typId']                                   = 'text';
        $result['typId']['flgId']                                   = 'data';
        $result['typId']['defOrder']                                = 1;
        $result['typId']['defIsName']                               = true;
        $result['typId']['defIsImage']                              = false;
        $result['typId']['defDafIndexOptions']                      = new \stdClass();
        $result['typId']['defDafDefaultValue']                      = '';
        $result['typId']['defDafTypOptions']                        = new \stdClass();
        $result['typId']['defDafUiOptions']                         = new \stdClass();
        $result['typId']['defDafUiOptions']->help                  = '';
        $result['typId']['defDafUiOptions']->icon                  = 'caret right';
        $result['typId']['defDafUiOptions']->info                  = '';
        $result['typId']['defDafUiOptions']->label                 = 'Type Name';
        $result['typId']['defDafUiOptions']->hidden              = false;
        $result['typId']['defDafUiOptions']->readOnly            = false;
        $result['typId']['defDafUiOptions']->listable            = true;
        $result['typId']['defDafUiOptions']->required            = true;
        $result['typId']['defDafUiOptions']->sortable            = false;
        $result['typId']['defDafUiOptions']->filterable          = false;
        $result['typId']['defDafUiOptions']->searchable          = false;
        $result['typId']['defDafTypValidationOptions']              = new \stdClass();
        $result['typId']['defDafAttachFileOptions']                 = false;

        $result['typParent']                                            = array();
        $result['typParent']['dafId']                                   = 'typParent';
        $result['typParent']['modId']                                   = 'types';
        $result['typParent']['typId']                                   = 'options';
        $result['typParent']['flgId']                                   = 'data';
        $result['typParent']['defOrder']                                = 2;
        $result['typParent']['defIsName']                               = false;
        $result['typParent']['defIsImage']                              = false;
        $result['typParent']['defDafIndexOptions']                      = new \stdClass();
        $result['typParent']['defDafDefaultValue']                      = 'root';
        $result['typParent']['defDafTypOptions']                        = new \stdClass();
        $result['typParent']['defDafTypOptions']->data                  = $this->getTypesKeyNames();
        $result['typParent']['defDafUiOptions']                         = new \stdClass();
        $result['typParent']['defDafUiOptions']->help                  = '';
        $result['typParent']['defDafUiOptions']->icon                  = 'caret right';
        $result['typParent']['defDafUiOptions']->info                  = '';
        $result['typParent']['defDafUiOptions']->label                 = 'Parent Data Type';
        $result['typParent']['defDafUiOptions']->hidden              = false;
        $result['typParent']['defDafUiOptions']->readOnly            = false;;
        $result['typParent']['defDafUiOptions']->listable            = true;
        $result['typParent']['defDafUiOptions']->required            = true;
        $result['typParent']['defDafUiOptions']->sortable            = false;
        $result['typParent']['defDafUiOptions']->filterable          = false;
        $result['typParent']['defDafUiOptions']->searchable          = false;
        $result['typParent']['defDafTypValidationOptions']                  = new \stdClass();
        $result['typParent']['defDafAttachFileOptions']                     = new \stdClass();

        $result['typOptions']                                     = array();
        $result['typOptions']['dafId']                            = 'typOptions';
        $result['typOptions']['modId']                            = 'types';
        $result['typOptions']['typId']                            = 'json';
        $result['typOptions']['flgId']                            = 'options';
        $result['typOptions']['defOrder']                         = 3;
        $result['typOptions']['defIsName']                        = false;
        $result['typOptions']['defIsImage']                       = false;
        $result['typOptions']['defDafIndexOptions']               = new \stdClass();
        $result['typOptions']['defDafDefaultValue']               = new \stdClass();;
        $result['typOptions']['defDafTypOptions']                 = new \stdClass();
        $result['typOptions']['defDafUiOptions']                  = new \stdClass();
        $result['typOptions']['defDafUiOptions']->help          = '';
        $result['typOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['typOptions']['defDafUiOptions']->info          = '';
        $result['typOptions']['defDafUiOptions']->label         = 'Type Options';
        $result['typOptions']['defDafUiOptions']->hidden      = false;
        $result['typOptions']['defDafUiOptions']->readOnly    = false;
        $result['typOptions']['defDafUiOptions']->listable    = true;
        $result['typOptions']['defDafUiOptions']->required    = true;
        $result['typOptions']['defDafUiOptions']->sortable    = false;
        $result['typOptions']['defDafUiOptions']->filterable  = false;
        $result['typOptions']['defDafUiOptions']->searchable  = false;
        $result['typOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['typOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['typValidationOptions']                                     = array();
        $result['typValidationOptions']['dafId']                            = 'typValidationOptions';
        $result['typValidationOptions']['modId']                            = 'types';
        $result['typValidationOptions']['typId']                            = 'json';
        $result['typValidationOptions']['flgId']                            = 'validation';
        $result['typValidationOptions']['defOrder']                         = 3;
        $result['typValidationOptions']['defIsName']                        = false;
        $result['typValidationOptions']['defIsImage']                       = false;
        $result['typValidationOptions']['defDafIndexOptions']               = new \stdClass();
        $result['typValidationOptions']['defDafDefaultValue']               = new \stdClass();;
        $result['typValidationOptions']['defDafTypOptions']                 = new \stdClass();
        $result['typValidationOptions']['defDafUiOptions']                  = new \stdClass();
        $result['typValidationOptions']['defDafUiOptions']->help          = '';
        $result['typValidationOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['typValidationOptions']['defDafUiOptions']->info          = '';
        $result['typValidationOptions']['defDafUiOptions']->label         = 'Validatios Options';
        $result['typValidationOptions']['defDafUiOptions']->hidden      = false;
        $result['typValidationOptions']['defDafUiOptions']->readOnly    = false;
        $result['typValidationOptions']['defDafUiOptions']->listable    = true;
        $result['typValidationOptions']['defDafUiOptions']->required    = true;
        $result['typValidationOptions']['defDafUiOptions']->sortable    = false;
        $result['typValidationOptions']['defDafUiOptions']->filterable  = false;
        $result['typValidationOptions']['defDafUiOptions']->searchable  = false;
        $result['typValidationOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['typValidationOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['typSaveAs']                                          = array();
        $result['typSaveAs']['dafId']                                 = 'typSaveAs';
        $result['typSaveAs']['modId']                                 = 'types';
        $result['typSaveAs']['typId']                                 = 'options';
        $result['typSaveAs']['flgId']                                 = 'data';
        $result['typSaveAs']['defOrder']                              = 4;
        $result['typSaveAs']['defIsName']                             = false;
        $result['typSaveAs']['defIsImage']                            = false;
        $result['typSaveAs']['defDafIndexOptions']                    = new \stdClass();
        $result['typSaveAs']['defDafDefaultValue']                    = 'STRING';
        $result['typSaveAs']['defDafTypOptions']                      = new \stdClass();
        $result['typSaveAs']['defDafTypOptions']->data                    = array();
        $result['typSaveAs']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'NUMBER','value'=>'NUMBER'));
        $result['typSaveAs']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'STRING','value'=>'STRING'));
        $result['typSaveAs']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'BOOLEAN','value'=>'BOOLEAN'));
        $result['typSaveAs']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'JSON','value'=>'JSON'));
        $result['typSaveAs']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'SERIALIZE','value'=>'SERIALIZE'));
        $result['typSaveAs']['defDafUiOptions']                       = new \stdClass();
        $result['typSaveAs']['defDafUiOptions']->help                = '';
        $result['typSaveAs']['defDafUiOptions']->icon                = 'caret right';
        $result['typSaveAs']['defDafUiOptions']->info                = '';
        $result['typSaveAs']['defDafUiOptions']->label               = 'Save As';
        $result['typSaveAs']['defDafUiOptions']->hidden            = false;
        $result['typSaveAs']['defDafUiOptions']->readOnly          = false;
        $result['typSaveAs']['defDafUiOptions']->listable          = true;
        $result['typSaveAs']['defDafUiOptions']->required          = true;
        $result['typSaveAs']['defDafUiOptions']->sortable          = false;
        $result['typSaveAs']['defDafUiOptions']->filterable        = false;
        $result['typSaveAs']['defDafUiOptions']->searchable        = false;
        $result['typSaveAs']['defDafTypValidationOptions']            = new \stdClass();
        $result['typSaveAs']['defDafAttachFileOptions']               = new \stdClass();

        $result['typReferenceTo']                                          = array();
        $result['typReferenceTo']['dafId']                                 = 'typReferenceTo';
        $result['typReferenceTo']['modId']                                 = 'types';
        $result['typReferenceTo']['typId']                                 = 'options';
        $result['typReferenceTo']['flgId']                                 = 'data';
        $result['typReferenceTo']['defOrder']                              = 5;
        $result['typReferenceTo']['defIsName']                             = false;
        $result['typReferenceTo']['defIsImage']                            = false;
        $result['typReferenceTo']['defDafIndexOptions']                    = new \stdClass();
        $result['typReferenceTo']['defDafDefaultValue']                    = 'NONE';
        $result['typReferenceTo']['defDafTypOptions']                      = new \stdClass();
        $result['typReferenceTo']['defDafTypOptions']->data                    = array();
        $result['typReferenceTo']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'NONE','value'=>'NONE'));
        $result['typReferenceTo']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'OBJECT','value'=>'OBJECT'));
        $result['typReferenceTo']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'COLLECTION','value'=>'COLLECTION'));
        $result['typReferenceTo']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'RELATION','value'=>'RELATION'));
        $result['typReferenceTo']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'VERTEX','value'=>'VERTEX'));
        $result['typReferenceTo']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'MODEL','value'=>'MODEL'));
        $result['typReferenceTo']['defDafUiOptions']                       = new \stdClass();
        $result['typReferenceTo']['defDafUiOptions']->help                = '';
        $result['typReferenceTo']['defDafUiOptions']->icon                = 'caret right';
        $result['typReferenceTo']['defDafUiOptions']->info                = '';
        $result['typReferenceTo']['defDafUiOptions']->label               = 'Teference To';
        $result['typReferenceTo']['defDafUiOptions']->hidden            = false;
        $result['typReferenceTo']['defDafUiOptions']->readOnly          = false;
        $result['typReferenceTo']['defDafUiOptions']->listable          = true;
        $result['typReferenceTo']['defDafUiOptions']->required          = true;
        $result['typReferenceTo']['defDafUiOptions']->sortable          = false;
        $result['typReferenceTo']['defDafUiOptions']->filterable        = false;
        $result['typReferenceTo']['defDafUiOptions']->searchable        = false;
        $result['typReferenceTo']['defDafTypValidationOptions']            = new \stdClass();
        $result['typReferenceTo']['defDafAttachFileOptions']               = new \stdClass();

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
            $definitionsRow["isState"]                          = false;
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

        return                  $dataEngine->getType(null);
    }

    protected function getObjectData($p_id){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->getType($p_id);
    }

    protected function getObjectToSaveId($p_data){
        
        return $p_data["typId"];
    }

    protected function getTypesKeyNames(){

        $result                         = array();

        $tableDataSource                = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        $queryResult                    = $tableDataSource->rawQuery("(SELECT 'root' AS 'label', 'root' AS 'value') UNION ALL (SELECT typId AS 'value', typId AS 'label' FROM data_types)");

        foreach($queryResult as $row){

            $result[]                   = $this->toObject($row);
        }

        return $result;
    }

    protected function saveObjectsData($p_data){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->addType($p_data);
    }

    protected function editObjectsData($p_id, $p_data){
        
        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->editType($p_id, $p_data);
    }
}