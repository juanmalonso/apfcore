<?php

namespace Nubesys\Data\Ui\Pages\Objects;

use Nubesys\Core\Ui\Pages\AppCrud;

class Definitions extends AppCrud {

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
        $result['dafId']['modId']                                   = 'definitions';
        $result['dafId']['typId']                                   = 'options';
        $result['dafId']['flgId']                                   = 'data';
        $result['dafId']['defOrder']                                = 1;
        $result['dafId']['defIsName']                               = true;
        $result['dafId']['defIsImage']                              = false;
        $result['dafId']['defDafIndexOptions']                      = new \stdClass();
        $result['dafId']['defDafDefaultValue']                      = '';
        $result['dafId']['defDafTypOptions']                        = new \stdClass();
        //TODO CREAR UN KEYNAMES GENERICO
        $result['dafId']['defDafTypOptions']->data                  = $this->getFieldsKeyNames();
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

        $result['modId']                                            = array();
        $result['modId']['dafId']                                   = 'modId';
        $result['modId']['modId']                                   = 'definitions';
        $result['modId']['typId']                                   = 'options';
        $result['modId']['flgId']                                   = 'data';
        $result['modId']['defOrder']                                = 2;
        $result['modId']['defIsName']                               = true;
        $result['modId']['defIsImage']                              = false;
        $result['modId']['defDafIndexOptions']                    = new \stdClass();
        $result['modId']['defDafDefaultValue']                      = ($this->hasGlobal('modId')) ? $this->getGlobal('modId') : '';
        $result['modId']['defDafTypOptions']                        = new \stdClass();
        $result['modId']['defDafTypOptions']->data                  = $this->getModelsKeyNames();
        $result['modId']['defDafUiOptions']                         = new \stdClass();
        $result['modId']['defDafUiOptions']->help                  = '';
        $result['modId']['defDafUiOptions']->icon                  = 'caret right';
        $result['modId']['defDafUiOptions']->info                  = '';
        $result['modId']['defDafUiOptions']->label                 = 'Model Name';
        $result['modId']['defDafUiOptions']->hidden              = false;
        $result['modId']['defDafUiOptions']->readOnly            = true;
        $result['modId']['defDafUiOptions']->listable            = true;
        $result['modId']['defDafUiOptions']->required            = true;
        $result['modId']['defDafUiOptions']->sortable            = false;
        $result['modId']['defDafUiOptions']->filterable          = false;
        $result['modId']['defDafUiOptions']->searchable          = false;
        $result['modId']['defDafTypValidationOptions']              = new \stdClass();
        $result['modId']['defDafAttachFileOptions']                 = false;

        $result['flgId']                                            = array();
        $result['flgId']['dafId']                                   = 'flgId';
        $result['flgId']['modId']                                   = 'definitions';
        $result['flgId']['typId']                                   = 'options';
        $result['flgId']['flgId']                                   = 'data';
        $result['flgId']['defOrder']                                = 3;
        $result['flgId']['defIsName']                               = false;
        $result['flgId']['defIsImage']                              = false;
        $result['flgId']['defDafIndexOptions']                    = new \stdClass();
        $result['flgId']['defDafDefaultValue']                      = 'data';
        $result['flgId']['defDafTypOptions']                        = new \stdClass();
        $result['flgId']['defDafTypOptions']->data                 = $this->getFieldsGroupsKeyNames();
        $result['flgId']['defDafUiOptions']                         = new \stdClass();
        $result['flgId']['defDafUiOptions']->help                  = '';
        $result['flgId']['defDafUiOptions']->icon                  = 'caret right';
        $result['flgId']['defDafUiOptions']->info                  = '';
        $result['flgId']['defDafUiOptions']->label                 = 'Field Group';
        $result['flgId']['defDafUiOptions']->hidden              = false;
        $result['flgId']['defDafUiOptions']->readOnly            = false;
        $result['flgId']['defDafUiOptions']->listable            = true;
        $result['flgId']['defDafUiOptions']->required            = true;
        $result['flgId']['defDafUiOptions']->sortable            = false;
        $result['flgId']['defDafUiOptions']->filterable          = false;
        $result['flgId']['defDafUiOptions']->searchable          = false;
        $result['flgId']['defDafTypValidationOptions']              = new \stdClass();
        $result['flgId']['defDafAttachFileOptions']                 = false;

        $result['defDafDefaultValue']                                    = array();
        $result['defDafDefaultValue']['dafId']                           = 'defDafDefaultValue';
        $result['defDafDefaultValue']['modId']                           = 'definitions';
        $result['defDafDefaultValue']['typId']                           = 'text';
        $result['defDafDefaultValue']['flgId']                           = 'data';
        $result['defDafDefaultValue']['defOrder']                        = 4;
        $result['defDafDefaultValue']['defIsName']                       = false;
        $result['defDafDefaultValue']['defIsImage']                      = false;
        $result['defDafDefaultValue']['defDafIndexOptions']            = new \stdClass();
        $result['defDafDefaultValue']['defDafDefaultValue']              = '';
        $result['defDafDefaultValue']['defDafTypOptions']                = new \stdClass();
        $result['defDafDefaultValue']['defDafUiOptions']                 = new \stdClass();
        $result['defDafDefaultValue']['defDafUiOptions']->help          = '';
        $result['defDafDefaultValue']['defDafUiOptions']->icon          = 'caret right';
        $result['defDafDefaultValue']['defDafUiOptions']->info          = '';
        $result['defDafDefaultValue']['defDafUiOptions']->label         = 'Default Value';
        $result['defDafDefaultValue']['defDafUiOptions']->hidden      = false;
        $result['defDafDefaultValue']['defDafUiOptions']->readOnly    = false;
        $result['defDafDefaultValue']['defDafUiOptions']->listable    = true;
        $result['defDafDefaultValue']['defDafUiOptions']->required    = false;
        $result['defDafDefaultValue']['defDafUiOptions']->sortable    = false;
        $result['defDafDefaultValue']['defDafUiOptions']->filterable  = false;
        $result['defDafDefaultValue']['defDafUiOptions']->searchable  = false;
        $result['defDafDefaultValue']['defDafTypValidationOptions']      = new \stdClass();
        $result['defDafDefaultValue']['defDafAttachFileOptions']         = false;

        $result['defOrder']                                            = array();
        $result['defOrder']['dafId']                                   = 'defOrder';
        $result['defOrder']['modId']                                   = 'definitions';
        $result['defOrder']['typId']                                   = 'integer';
        $result['defOrder']['flgId']                                   = 'data';
        $result['defOrder']['defOrder']                                = 5;
        $result['defOrder']['defIsName']                               = false;
        $result['defOrder']['defIsImage']                              = false;
        $result['defOrder']['defDafIndexOptions']                    = new \stdClass();
        $result['defOrder']['defDafDefaultValue']                      = '';
        $result['defOrder']['defDafTypOptions']                        = new \stdClass();
        $result['defOrder']['defDafUiOptions']                         = new \stdClass();
        $result['defOrder']['defDafUiOptions']->help                  = '';
        $result['defOrder']['defDafUiOptions']->icon                  = 'caret right';
        $result['defOrder']['defDafUiOptions']->info                  = '';
        $result['defOrder']['defDafUiOptions']->label                 = 'Order';
        $result['defOrder']['defDafUiOptions']->hidden              = false;
        $result['defOrder']['defDafUiOptions']->readOnly            = false;
        $result['defOrder']['defDafUiOptions']->listable            = true;
        $result['defOrder']['defDafUiOptions']->required            = true;
        $result['defOrder']['defDafUiOptions']->sortable            = false;
        $result['defOrder']['defDafUiOptions']->filterable          = false;
        $result['defOrder']['defDafUiOptions']->searchable          = false;
        $result['defOrder']['defDafTypValidationOptions']              = new \stdClass();
        $result['defOrder']['defDafAttachFileOptions']                 = false;


        $result['defIsName']                                            = array();
        $result['defIsName']['dafId']                                   = 'defIsName';
        $result['defIsName']['modId']                                   = 'definitions';
        $result['defIsName']['typId']                                   = 'boolean01';
        $result['defIsName']['flgId']                                   = 'data';
        $result['defIsName']['defOrder']                                = 6;
        $result['defIsName']['defIsName']                               = false;
        $result['defIsName']['defIsImage']                              = false;
        $result['defIsName']['defDafIndexOptions']                    = new \stdClass();
        $result['defIsName']['defDafDefaultValue']                      = 1;
        $result['defIsName']['defDafTypOptions']                        = new \stdClass();
        $result['defIsName']['defDafUiOptions']                         = new \stdClass();
        $result['defIsName']['defDafUiOptions']->help                  = '';
        $result['defIsName']['defDafUiOptions']->icon                  = 'caret right';
        $result['defIsName']['defDafUiOptions']->info                  = '';
        $result['defIsName']['defDafUiOptions']->label                 = 'Is Name';
        $result['defIsName']['defDafUiOptions']->hidden              = false;
        $result['defIsName']['defDafUiOptions']->readOnly            = false;
        $result['defIsName']['defDafUiOptions']->listable            = true;
        $result['defIsName']['defDafUiOptions']->required            = true;
        $result['defIsName']['defDafUiOptions']->sortable            = false;
        $result['defIsName']['defDafUiOptions']->filterable          = false;
        $result['defIsName']['defDafUiOptions']->searchable          = false;
        $result['defIsName']['defDafTypValidationOptions']              = new \stdClass();
        $result['defIsName']['defDafAttachFileOptions']                 = false;

        $result['defIsImage']                                            = array();
        $result['defIsImage']['dafId']                                   = 'defIsImage';
        $result['defIsImage']['modId']                                   = 'definitions';
        $result['defIsImage']['typId']                                   = 'boolean01';
        $result['defIsImage']['flgId']                                   = 'data';
        $result['defIsImage']['defOrder']                                = 7;
        $result['defIsImage']['defIsName']                               = false;
        $result['defIsImage']['defIsImage']                              = false;
        $result['defIsImage']['defDafIndexOptions']                    = new \stdClass();
        $result['defIsImage']['defDafDefaultValue']                      = 0;
        $result['defIsImage']['defDafTypOptions']                        = new \stdClass();
        $result['defIsImage']['defDafUiOptions']                         = new \stdClass();
        $result['defIsImage']['defDafUiOptions']->help                  = '';
        $result['defIsImage']['defDafUiOptions']->icon                  = 'caret right';
        $result['defIsImage']['defDafUiOptions']->info                  = '';
        $result['defIsImage']['defDafUiOptions']->label                 = 'Is Image';
        $result['defIsImage']['defDafUiOptions']->hidden              = false;
        $result['defIsImage']['defDafUiOptions']->readOnly            = false;
        $result['defIsImage']['defDafUiOptions']->listable            = true;
        $result['defIsImage']['defDafUiOptions']->required            = true;
        $result['defIsImage']['defDafUiOptions']->sortable            = false;
        $result['defIsImage']['defDafUiOptions']->filterable          = false;
        $result['defIsImage']['defDafUiOptions']->searchable          = false;
        $result['defIsImage']['defDafTypValidationOptions']              = new \stdClass();
        $result['defIsImage']['defDafAttachFileOptions']                 = false;


        $result['defDafUiOptions']                                     = array();
        $result['defDafUiOptions']['dafId']                            = 'defDafUiOptions';
        $result['defDafUiOptions']['modId']                            = 'definitions';
        $result['defDafUiOptions']['typId']                            = 'json';
        $result['defDafUiOptions']['flgId']                            = 'ui';
        $result['defDafUiOptions']['defOrder']                         = 8;
        $result['defDafUiOptions']['defIsName']                        = false;
        $result['defDafUiOptions']['defIsImage']                       = false;
        $result['defDafUiOptions']['defDafIndexOptions']             = new \stdClass();
        $result['defDafUiOptions']['defDafDefaultValue']               = new \stdClass();
        $result['defDafUiOptions']['defDafDefaultValue']->help        = '';
        $result['defDafUiOptions']['defDafDefaultValue']->icon        = 'caret right';
        $result['defDafUiOptions']['defDafDefaultValue']->info        = '';
        $result['defDafUiOptions']['defDafDefaultValue']->label       = 'UI Options';
        $result['defDafUiOptions']['defDafDefaultValue']->hidden    = false;
        $result['defDafUiOptions']['defDafDefaultValue']->readOnly    = false;
        $result['defDafUiOptions']['defDafDefaultValue']->listable    = true;
        $result['defDafUiOptions']['defDafDefaultValue']->required    = true;
        $result['defDafUiOptions']['defDafDefaultValue']->sortable    = true;
        $result['defDafUiOptions']['defDafDefaultValue']->filterable  = true;
        $result['defDafUiOptions']['defDafDefaultValue']->searchable  = true;
        $result['defDafUiOptions']['defDafTypOptions']                 = new \stdClass();
        $result['defDafUiOptions']['defDafUiOptions']                  = new \stdClass();
        $result['defDafUiOptions']['defDafUiOptions']->help          = '';
        $result['defDafUiOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['defDafUiOptions']['defDafUiOptions']->info          = '';
        $result['defDafUiOptions']['defDafUiOptions']->label         = 'UI Options';
        $result['defDafUiOptions']['defDafUiOptions']->hidden      = false;
        $result['defDafUiOptions']['defDafUiOptions']->readOnly    = false;
        $result['defDafUiOptions']['defDafUiOptions']->listable    = true;
        $result['defDafUiOptions']['defDafUiOptions']->required    = true;
        $result['defDafUiOptions']['defDafUiOptions']->sortable    = false;
        $result['defDafUiOptions']['defDafUiOptions']->filterable  = false;
        $result['defDafUiOptions']['defDafUiOptions']->searchable  = false;
        $result['defDafUiOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['defDafUiOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['defDafIndexOptions']                                     = array();
        $result['defDafIndexOptions']['dafId']                            = 'defDafIndexOptions';
        $result['defDafIndexOptions']['modId']                            = 'definitions';
        $result['defDafIndexOptions']['typId']                            = 'json';
        $result['defDafIndexOptions']['flgId']                            = 'index';
        $result['defDafIndexOptions']['defOrder']                         = 9;
        $result['defDafIndexOptions']['defIsName']                        = false;
        $result['defDafIndexOptions']['defIsImage']                       = false;
        $result['defDafIndexOptions']['defDafIndexOptions']               = new \stdClass();
        $result['defDafIndexOptions']['defDafDefaultValue']               = new \stdClass();
        $result['defDafIndexOptions']['defDafDefaultValue']->indexable      = false;
        $result['defDafIndexOptions']['defDafDefaultValue']->mapping        = new \stdClass();
        $result['defDafIndexOptions']['defDafTypOptions']                 = new \stdClass();
        $result['defDafIndexOptions']['defDafUiOptions']                  = new \stdClass();
        $result['defDafIndexOptions']['defDafUiOptions']->help          = '';
        $result['defDafIndexOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['defDafIndexOptions']['defDafUiOptions']->info          = '';
        $result['defDafIndexOptions']['defDafUiOptions']->label         = 'Indexing Options';
        $result['defDafIndexOptions']['defDafUiOptions']->hidden      = false;
        $result['defDafIndexOptions']['defDafUiOptions']->readOnly    = false;
        $result['defDafIndexOptions']['defDafUiOptions']->listable    = true;
        $result['defDafIndexOptions']['defDafUiOptions']->required    = true;
        $result['defDafIndexOptions']['defDafUiOptions']->sortable    = false;
        $result['defDafIndexOptions']['defDafUiOptions']->filterable  = false;
        $result['defDafIndexOptions']['defDafUiOptions']->searchable  = false;
        $result['defDafIndexOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['defDafIndexOptions']['defDafAttachFileOptions']          = new \stdClass();


        $result['defDafTypOptions']                                     = array();
        $result['defDafTypOptions']['dafId']                            = 'defDafTypOptions';
        $result['defDafTypOptions']['modId']                            = 'definitions';
        $result['defDafTypOptions']['typId']                            = 'json';
        $result['defDafTypOptions']['flgId']                            = 'options';
        $result['defDafTypOptions']['defOrder']                         = 10;
        $result['defDafTypOptions']['defIsName']                        = false;
        $result['defDafTypOptions']['defIsImage']                       = false;
        $result['defDafTypOptions']['defDafIndexOptions']               = new \stdClass();
        $result['defDafTypOptions']['defDafDefaultValue']               = new \stdClass();
        $result['defDafTypOptions']['defDafTypOptions']                 = new \stdClass();
        $result['defDafTypOptions']['defDafUiOptions']                  = new \stdClass();
        $result['defDafTypOptions']['defDafUiOptions']->help          = '';
        $result['defDafTypOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['defDafTypOptions']['defDafUiOptions']->info          = '';
        $result['defDafTypOptions']['defDafUiOptions']->label         = 'Type Extra Options';
        $result['defDafTypOptions']['defDafUiOptions']->hidden      = false;
        $result['defDafTypOptions']['defDafUiOptions']->readOnly    = false;
        $result['defDafTypOptions']['defDafUiOptions']->listable    = true;
        $result['defDafTypOptions']['defDafUiOptions']->required    = true;
        $result['defDafTypOptions']['defDafUiOptions']->sortable    = false;
        $result['defDafTypOptions']['defDafUiOptions']->filterable  = false;
        $result['defDafTypOptions']['defDafUiOptions']->searchable  = false;
        $result['defDafTypOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['defDafTypOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['defDafTypValidationOptions']                                     = array();
        $result['defDafTypValidationOptions']['dafId']                            = 'defDafTypValidationOptions';
        $result['defDafTypValidationOptions']['modId']                            = 'definitions';
        $result['defDafTypValidationOptions']['typId']                            = 'json';
        $result['defDafTypValidationOptions']['flgId']                            = 'validation';
        $result['defDafTypValidationOptions']['defOrder']                         = 11;
        $result['defDafTypValidationOptions']['defIsName']                        = false;
        $result['defDafTypValidationOptions']['defIsImage']                       = false;
        $result['defDafTypValidationOptions']['defDafIndexOptions']             = new \stdClass();
        $result['defDafTypValidationOptions']['defDafDefaultValue']               = new \stdClass();
        $result['defDafTypValidationOptions']['defDafTypOptions']                 = new \stdClass();
        $result['defDafTypValidationOptions']['defDafUiOptions']                  = new \stdClass();
        $result['defDafTypValidationOptions']['defDafUiOptions']->help          = '';
        $result['defDafTypValidationOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['defDafTypValidationOptions']['defDafUiOptions']->info          = '';
        $result['defDafTypValidationOptions']['defDafUiOptions']->label         = 'Validations Options';
        $result['defDafTypValidationOptions']['defDafUiOptions']->hidden      = false;
        $result['defDafTypValidationOptions']['defDafUiOptions']->readOnly    = false;
        $result['defDafTypValidationOptions']['defDafUiOptions']->listable    = true;
        $result['defDafTypValidationOptions']['defDafUiOptions']->required    = true;
        $result['defDafTypValidationOptions']['defDafUiOptions']->sortable    = false;
        $result['defDafTypValidationOptions']['defDafUiOptions']->filterable  = false;
        $result['defDafTypValidationOptions']['defDafUiOptions']->searchable  = false;
        $result['defDafTypValidationOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['defDafTypValidationOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['defDafAttachFileOptions']                                     = array();
        $result['defDafAttachFileOptions']['dafId']                            = 'defDafAttachFileOptions';
        $result['defDafAttachFileOptions']['modId']                            = 'definitions';
        $result['defDafAttachFileOptions']['typId']                            = 'json';
        $result['defDafAttachFileOptions']['flgId']                            = 'file';
        $result['defDafAttachFileOptions']['defOrder']                         = 12;
        $result['defDafAttachFileOptions']['defIsName']                        = false;
        $result['defDafAttachFileOptions']['defIsImage']                       = false;
        $result['defDafAttachFileOptions']['defDafIndexOptions']             = new \stdClass();
        $result['defDafAttachFileOptions']['defDafDefaultValue']               = new \stdClass();
        $result['defDafAttachFileOptions']['defDafTypOptions']                 = new \stdClass();
        $result['defDafAttachFileOptions']['defDafUiOptions']                  = new \stdClass();
        $result['defDafAttachFileOptions']['defDafUiOptions']->help          = '';
        $result['defDafAttachFileOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['defDafAttachFileOptions']['defDafUiOptions']->info          = '';
        $result['defDafAttachFileOptions']['defDafUiOptions']->label         = 'Attach File Options';
        $result['defDafAttachFileOptions']['defDafUiOptions']->hidden      = false;
        $result['defDafAttachFileOptions']['defDafUiOptions']->readOnly    = false;
        $result['defDafAttachFileOptions']['defDafUiOptions']->listable    = true;
        $result['defDafAttachFileOptions']['defDafUiOptions']->required    = true;
        $result['defDafAttachFileOptions']['defDafUiOptions']->sortable    = false;
        $result['defDafAttachFileOptions']['defDafUiOptions']->filterable  = false;
        $result['defDafAttachFileOptions']['defDafUiOptions']->searchable  = false;
        $result['defDafAttachFileOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['defDafAttachFileOptions']['defDafAttachFileOptions']          = new \stdClass();

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
        
        $result                                     = array();

        if($this->hasUrlParam("modId")){

            $modId                  = $this->getUrlParam("modId");
            
            $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

            $result                 = $dataEngine->getDefinition($modId, null, false);
        }

        return $result;
    }

    protected function getObjectData($p_id){

        $result = array();

        if($this->hasUrlParam("modId")){

            $modId                      = $this->getUrlParam("modId");

            if($p_id !== false){

                $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

                $result                 = $dataEngine->getDefinition($modId, $p_id, false);
            }else{

                $result["modId"]        = $this->getUrlParam("modId");
            }
        }
        
        return $result;
    }

    protected function getObjectToSaveId($p_data){
        
        $result             = array();
        $result["dafId"]    = $p_data["dafId"];
        $result["modId"]    = $p_data["modId"];

        return $result;
    }

    protected function getFieldsKeyNames(){

        $result                         = array();

        $tableDataSource                = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        $queryResult                    = $tableDataSource->rawQuery("SELECT dafId AS 'value', dafId AS 'label' FROM data_fields");

        foreach($queryResult as $row){

            $result[]                   = $this->toObject($row);
        }

        return $result;
    }

    protected function getFieldsGroupsKeyNames(){

        $result                         = array();

        $tableDataSource                = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        $queryResult                    = $tableDataSource->rawQuery("SELECT flgId AS 'value', flgName AS 'label' FROM fields_groups");

        foreach($queryResult as $row){

            $result[]                   = $this->toObject($row);
        }

        return $result;
    }

    protected function getModelsKeyNames(){

        $result                         = array();

        $tableDataSource                = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        $queryResult                    = $tableDataSource->rawQuery("SELECT modId AS 'value', modId AS 'label' FROM data_models");

        foreach($queryResult as $row){

            $result[]                   = $this->toObject($row);
        }

        return $result;
    }
    

    protected function saveObjectsData($p_data){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->addDefinition($p_data);
    }

    protected function editObjectsData($p_id, $p_data){
        
        $result                 = false;

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        if(isset($p_data['modId']) && isset($p_data['dafId'])){

            $result             = $dataEngine->editDefinition($p_data['modId'], $p_data['dafId'], $p_data);
        }

        return $result;
    }
}