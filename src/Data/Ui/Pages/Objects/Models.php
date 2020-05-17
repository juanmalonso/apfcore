<?php

namespace Nubesys\Data\Ui\Pages\Objects;

use Nubesys\Core\Ui\Pages\AppCrud;

class Models extends AppCrud {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        //aka le llama antes al getobjectdefinition
    }

    //OVERRIDES
    protected function getObjectsDefinitions(){
        
        $result = array();

        $result['modId']                                            = array();
        $result['modId']['dafId']                                   = 'modId';
        $result['modId']['modId']                                   = 'models';
        $result['modId']['typId']                                   = 'text';
        $result['modId']['flgId']                                   = 'data';
        $result['modId']['defOrder']                                = 1;
        $result['modId']['defIsName']                               = true;
        $result['modId']['defIsImage']                              = false;
        $result['modId']['defDafDefaultValue']                      = '';
        $result['modId']['defDafIndexOptions']                      = new \stdClass();
        $result['modId']['defDafTypOptions']                        = new \stdClass();
        $result['modId']['defDafUiOptions']                         = new \stdClass();
        $result['modId']['defDafUiOptions']->help                  = '';
        $result['modId']['defDafUiOptions']->icon                  = 'caret right';
        $result['modId']['defDafUiOptions']->info                  = '';
        $result['modId']['defDafUiOptions']->label                 = 'Name';
        $result['modId']['defDafUiOptions']->hidden              = false;
        //TODO : readOnly dinamico
        $result['modId']['defDafUiOptions']->readOnly            = ($this->action == 'edit') ? true : false;
        $result['modId']['defDafUiOptions']->listable            = true;
        $result['modId']['defDafUiOptions']->required            = true;
        $result['modId']['defDafUiOptions']->sortable            = false;
        $result['modId']['defDafUiOptions']->filterable          = false;
        $result['modId']['defDafUiOptions']->searchable          = false;
        $result['modId']['defDafTypValidationOptions']              = new \stdClass();
        $result['modId']['defDafAttachFileOptions']                 = new \stdClass();

        $result['modParent']                                            = array();
        $result['modParent']['dafId']                                   = 'modParent';
        $result['modParent']['modId']                                   = 'models';
        $result['modParent']['typId']                                   = 'options';
        $result['modParent']['flgId']                                   = 'data';
        $result['modParent']['defOrder']                                = 2;
        $result['modParent']['defIsName']                               = true;
        $result['modParent']['defIsImage']                              = false;
        $result['modParent']['defDafDefaultValue']                      = 'root';
        $result['modParent']['defDafIndexOptions']                      = new \stdClass();
        $result['modParent']['defDafTypOptions']                        = new \stdClass();
        $result['modParent']['defDafTypOptions']->data                  = $this->getModelsKeyNames();
        $result['modParent']['defDafUiOptions']                         = new \stdClass();
        $result['modParent']['defDafUiOptions']->help                  = '';
        $result['modParent']['defDafUiOptions']->icon                  = 'caret right';
        $result['modParent']['defDafUiOptions']->info                  = '';
        $result['modParent']['defDafUiOptions']->label                 = 'Paren Model';
        $result['modParent']['defDafUiOptions']->hidden              = false;
        //TODO : readOnly dinamico
        $result['modParent']['defDafUiOptions']->readOnly            = ($this->action == 'edit') ? true : false;
        $result['modParent']['defDafUiOptions']->listable            = true;
        $result['modParent']['defDafUiOptions']->required            = true;
        $result['modParent']['defDafUiOptions']->sortable            = false;
        $result['modParent']['defDafUiOptions']->filterable          = false;
        $result['modParent']['defDafUiOptions']->searchable          = false;
        $result['modParent']['defDafTypValidationOptions']              = new \stdClass();
        $result['modParent']['defDafAttachFileOptions']                 = new \stdClass();

        $result['modType']                                          = array();
        $result['modType']['dafId']                                 = 'modType';
        $result['modType']['modId']                                 = 'models';
        $result['modType']['typId']                                 = 'options';
        $result['modType']['flgId']                                 = 'data';
        $result['modType']['defOrder']                              = 3;
        $result['modType']['defIsName']                             = false;
        $result['modType']['defIsImage']                            = false;
        $result['modType']['defDafDefaultValue']                    = 'OBJECT';
        $result['modType']['defDafIndexOptions']                    = new \stdClass();
        $result['modType']['defDafTypOptions']                      = new \stdClass();
        $result['modType']['defDafTypOptions']->data                    = array();
        $result['modType']['defDafTypOptions']->data[]                  = array('label'=>'OBJETO','value'=>'OBJECT');
        $result['modType']['defDafTypOptions']->data[]                  = array('label'=>'RELACION','value'=>'RELATION');
        $result['modType']['defDafTypOptions']->data[]                  = array('label'=>'COLECCION','value'=>'COLECTION');
        $result['modType']['defDafTypOptions']->data[]                  = array('label'=>'TABLA','value'=>'TABLE');
        $result['modType']['defDafUiOptions']                       = new \stdClass();
        $result['modType']['defDafUiOptions']->help                = '';
        $result['modType']['defDafUiOptions']->icon                = 'caret right';
        $result['modType']['defDafUiOptions']->info                = '';
        $result['modType']['defDafUiOptions']->label               = 'Type';
        $result['modType']['defDafUiOptions']->hidden            = false;
        $result['modType']['defDafUiOptions']->readOnly          = ($this->action == 'edit') ? true : false;
        $result['modType']['defDafUiOptions']->listable          = true;
        $result['modType']['defDafUiOptions']->required          = true;
        $result['modType']['defDafUiOptions']->sortable          = false;
        $result['modType']['defDafUiOptions']->filterable        = false;
        $result['modType']['defDafUiOptions']->searchable        = false;
        $result['modType']['defDafTypValidationOptions']            = new \stdClass();
        $result['modType']['defDafAttachFileOptions']               = new \stdClass();

        $result['modIdStrategy']                                    = array();
        $result['modIdStrategy']['dafId']                           = 'modIdStrategy';
        $result['modIdStrategy']['modId']                           = 'models';
        $result['modIdStrategy']['typId']                           = 'options';
        $result['modIdStrategy']['flgId']                           = 'data';
        $result['modIdStrategy']['defOrder']                        = 4;
        $result['modIdStrategy']['defIsName']                       = false;
        $result['modIdStrategy']['defIsImage']                      = false;
        $result['modIdStrategy']['defDafDefaultValue']              = 'CUSTOM';
        $result['modIdStrategy']['defDafIndexOptions']              = new \stdClass();
        $result['modIdStrategy']['defDafTypOptions']                = new \stdClass();
        $result['modIdStrategy']['defDafTypOptions']->data              = array();
        $result['modIdStrategy']['defDafTypOptions']->data[]            = array('label'=>'UUID','value'=>'UUID');
        $result['modIdStrategy']['defDafTypOptions']->data[]            = array('label'=>'SLUG','value'=>'SLUG');
        $result['modIdStrategy']['defDafTypOptions']->data[]            = array('label'=>'CUSTOM (_id)','value'=>'CUSTOM');
        $result['modIdStrategy']['defDafTypOptions']->data[]            = array('label'=>'SLUG con Prefijo','value'=>'SLUGPREFIX');
        $result['modIdStrategy']['defDafTypOptions']->data[]            = array('label'=>'Auto Numerico','value'=>'AUTOINCREMENT');
        $result['modIdStrategy']['defDafTypOptions']->data[]            = array('label'=>'BASE 36','value'=>'BASE36');
        $result['modIdStrategy']['defDafUiOptions']                 = new \stdClass();
        $result['modIdStrategy']['defDafUiOptions']->help          = '';
        $result['modIdStrategy']['defDafUiOptions']->icon          = 'caret right';
        $result['modIdStrategy']['defDafUiOptions']->info          = '';
        $result['modIdStrategy']['defDafUiOptions']->label         = 'Id Strategy';
        $result['modIdStrategy']['defDafUiOptions']->hidden      = false;
        $result['modIdStrategy']['defDafUiOptions']->readOnly    = false;
        $result['modIdStrategy']['defDafUiOptions']->listable    = true;
        $result['modIdStrategy']['defDafUiOptions']->required    = true;
        $result['modIdStrategy']['defDafUiOptions']->sortable    = false;
        $result['modIdStrategy']['defDafUiOptions']->filterable  = false;
        $result['modIdStrategy']['defDafUiOptions']->searchable  = false;
        $result['modIdStrategy']['defDafTypValidationOptions']      = new \stdClass();
        $result['modIdStrategy']['defDafAttachFileOptions']         = new \stdClass();

        $result['modPartitionMode']                                 = array();
        $result['modPartitionMode']['dafId']                        = 'modPartitionMode';
        $result['modPartitionMode']['modId']                        = 'models';
        $result['modPartitionMode']['typId']                        = 'options';
        $result['modPartitionMode']['flgId']                        = 'data';
        $result['modPartitionMode']['defOrder']                     = 5;
        $result['modPartitionMode']['defIsName']                    = false;
        $result['modPartitionMode']['defIsImage']                   = false;
        $result['modPartitionMode']['defDafDefaultValue']           = 'NONE';
        $result['modPartitionMode']['defDafIndexOptions']           = new \stdClass();
        $result['modPartitionMode']['defDafTypOptions']             = new \stdClass();
        $result['modPartitionMode']['defDafTypOptions']->data            = array();
        $result['modPartitionMode']['defDafTypOptions']->data[]          = array('label'=>'Ninguno','value'=>'NONE');
        $result['modPartitionMode']['defDafTypOptions']->data[]          = array('label'=>'Por Trimestres','value'=>'Y4');
        $result['modPartitionMode']['defDafTypOptions']->data[]          = array('label'=>'Por Meses','value'=>'Y12');
        $result['modPartitionMode']['defDafTypOptions']->data[]          = array('label'=>'Por Semanas','value'=>'Y53');
        $result['modPartitionMode']['defDafTypOptions']->data[]          = array('label'=>'Cada 3 Dias','value'=>'Y122');
        $result['modPartitionMode']['defDafUiOptions']              = new \stdClass();
        $result['modPartitionMode']['defDafUiOptions']->help        = '';
        $result['modPartitionMode']['defDafUiOptions']->icon        = 'caret right';
        $result['modPartitionMode']['defDafUiOptions']->info        = '';
        $result['modPartitionMode']['defDafUiOptions']->label       = 'Partition Mode';
        $result['modPartitionMode']['defDafUiOptions']->hidden    = false;
        $result['modPartitionMode']['defDafUiOptions']->readOnly  = ($this->action == 'edit') ? true : false;
        $result['modPartitionMode']['defDafUiOptions']->listable  = true;
        $result['modPartitionMode']['defDafUiOptions']->required  = true;
        $result['modPartitionMode']['defDafUiOptions']->sortable  = false;
        $result['modPartitionMode']['defDafUiOptions']->filterable= false;
        $result['modPartitionMode']['defDafUiOptions']->searchable= false;
        $result['modPartitionMode']['defDafTypValidationOptions']   = new \stdClass();
        $result['modPartitionMode']['defDafAttachFileOptions']      = new \stdClass();

        $result['modIndexOptions']                                  = array();
        $result['modIndexOptions']['dafId']                         = 'modIndexOptions';
        $result['modIndexOptions']['modId']                         = 'models';
        $result['modIndexOptions']['typId']                         = 'json';
        $result['modIndexOptions']['flgId']                         = 'index';
        $result['modIndexOptions']['defOrder']                      = 6;
        $result['modIndexOptions']['defIsName']                     = false;
        $result['modIndexOptions']['defIsImage']                    = false;
        $result['modIndexOptions']['defDafDefaultValue']            = new \stdClass();
        $result['modIndexOptions']['defDafIndexOptions']            = new \stdClass();
        $result['modIndexOptions']['defDafDefaultValue']->indexable     = false;
        $result['modIndexOptions']['defDafDefaultValue']->index         = '';
        $result['modIndexOptions']['defDafDefaultValue']->analysis      = new \stdClass();
        $result['modIndexOptions']['defDafDefaultValue']->basemapping   = new \stdClass();
        $result['modIndexOptions']['defDafTypOptions']              = new \stdClass();
        $result['modIndexOptions']['defDafUiOptions']               = new \stdClass();
        $result['modIndexOptions']['defDafUiOptions']->help        = '';
        $result['modIndexOptions']['defDafUiOptions']->icon        = 'caret right';
        $result['modIndexOptions']['defDafUiOptions']->info        = '';
        $result['modIndexOptions']['defDafUiOptions']->label       = 'Index Options';
        $result['modIndexOptions']['defDafUiOptions']->hidden    = false;
        $result['modIndexOptions']['defDafUiOptions']->readOnly  = false;
        $result['modIndexOptions']['defDafUiOptions']->listable  = true;
        $result['modIndexOptions']['defDafUiOptions']->required  = true;
        $result['modIndexOptions']['defDafUiOptions']->sortable  = false;
        $result['modIndexOptions']['defDafUiOptions']->filterable= false;
        $result['modIndexOptions']['defDafUiOptions']->searchable= false;
        $result['modIndexOptions']['defDafTypValidationOptions']    = new \stdClass();
        $result['modIndexOptions']['defDafAttachFileOptions']       = new \stdClass();

        $result['modUiOptions']                                     = array();
        $result['modUiOptions']['dafId']                            = 'modUiOptions';
        $result['modUiOptions']['modId']                            = 'models';
        $result['modUiOptions']['typId']                            = 'json';
        $result['modUiOptions']['flgId']                            = 'ui';
        $result['modUiOptions']['defOrder']                         = 7;
        $result['modUiOptions']['defIsName']                        = false;
        $result['modUiOptions']['defIsImage']                       = false;
        $result['modUiOptions']['defDafDefaultValue']               = new \stdClass();
        $result['modUiOptions']['defDafDefaultValue']->help        = '';
        $result['modUiOptions']['defDafDefaultValue']->icon        = 'caret right';
        $result['modUiOptions']['defDafDefaultValue']->name        = '';
        $result['modUiOptions']['defDafDefaultValue']->manageAs    = 'LIST';
        $result['modUiOptions']['defDafDefaultValue']->pluralName  = '';
        $result['modUiOptions']['defDafDefaultValue']->description = '';
        $result['modUiOptions']['defDafIndexOptions']              = new \stdClass();
        $result['modUiOptions']['defDafTypOptions']                = new \stdClass();
        $result['modUiOptions']['defDafUiOptions']                 = new \stdClass();
        $result['modUiOptions']['defDafUiOptions']->help          = '';
        $result['modUiOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['modUiOptions']['defDafUiOptions']->info          = '';
        $result['modUiOptions']['defDafUiOptions']->label         = 'Ui Options';
        $result['modUiOptions']['defDafUiOptions']->hidden      = false;
        $result['modUiOptions']['defDafUiOptions']->readOnly    = false;
        $result['modUiOptions']['defDafUiOptions']->listable    = true;
        $result['modUiOptions']['defDafUiOptions']->required    = true;
        $result['modUiOptions']['defDafUiOptions']->sortable    = false;
        $result['modUiOptions']['defDafUiOptions']->filterable  = false;
        $result['modUiOptions']['defDafUiOptions']->searchable  = false;
        $result['modUiOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['modUiOptions']['defDafAttachFileOptions']          = new \stdClass();

        $result['modCacheOptions']                                  = array();
        $result['modCacheOptions']['dafId']                         = 'modCacheOptions';
        $result['modCacheOptions']['modId']                         = 'models';
        $result['modCacheOptions']['typId']                         = 'json';
        $result['modCacheOptions']['flgId']                         = 'cache';
        $result['modCacheOptions']['defOrder']                      = 8;
        $result['modCacheOptions']['defIsName']                     = false;
        $result['modCacheOptions']['defIsImage']                    = false;
        $result['modCacheOptions']['defDafDefaultValue']            = new \stdClass();
        $result['modCacheOptions']['defDafDefaultValue']->cacheable     = true;
        $result['modCacheOptions']['defDafDefaultValue']->adapter       = 'MEMORY';
        $result['modCacheOptions']['defDafDefaultValue']->cacheLife     = 3600;
        $result['modCacheOptions']['defDafIndexOptions']            = new \stdClass();
        $result['modCacheOptions']['defDafTypOptions']              = new \stdClass();
        $result['modCacheOptions']['defDafUiOptions']               = new \stdClass();
        $result['modCacheOptions']['defDafUiOptions']->help        = '';
        $result['modCacheOptions']['defDafUiOptions']->icon        = 'caret right';
        $result['modCacheOptions']['defDafUiOptions']->info        = '';
        $result['modCacheOptions']['defDafUiOptions']->label       = 'Cache Options';
        $result['modCacheOptions']['defDafUiOptions']->hidden    = false;
        $result['modCacheOptions']['defDafUiOptions']->readOnly  = false;
        $result['modCacheOptions']['defDafUiOptions']->listable  = true;
        $result['modCacheOptions']['defDafUiOptions']->required  = true;
        $result['modCacheOptions']['defDafUiOptions']->sortable  = false;
        $result['modCacheOptions']['defDafUiOptions']->filterable= false;
        $result['modCacheOptions']['defDafUiOptions']->searchable= false;
        $result['modCacheOptions']['defDafTypValidationOptions']    = new \stdClass();
        $result['modCacheOptions']['defDafAttachFileOptions']       = new \stdClass();

        $result['modVersionsOptions']                               = array();
        $result['modVersionsOptions']['dafId']                      = 'modVersionsOptions';
        $result['modVersionsOptions']['modId']                      = 'models';
        $result['modVersionsOptions']['typId']                      = 'json';
        $result['modVersionsOptions']['flgId']                      = 'versions';
        $result['modVersionsOptions']['defOrder']                   = 9;
        $result['modVersionsOptions']['defIsName']                  = false;
        $result['modVersionsOptions']['defIsImage']                 = false;
        $result['modVersionsOptions']['defDafDefaultValue']         = new \stdClass();
        $result['modVersionsOptions']['defDafIndexOptions']         = new \stdClass();
        $result['modVersionsOptions']['defDafTypOptions']           = new \stdClass();
        $result['modVersionsOptions']['defDafUiOptions']            = new \stdClass();
        $result['modVersionsOptions']['defDafUiOptions']->help         = '';
        $result['modVersionsOptions']['defDafUiOptions']->icon         = 'caret right';
        $result['modVersionsOptions']['defDafUiOptions']->info         = '';
        $result['modVersionsOptions']['defDafUiOptions']->label        = 'Versions Options';
        $result['modVersionsOptions']['defDafUiOptions']->hidden     = false;
        $result['modVersionsOptions']['defDafUiOptions']->readOnly   = false;
        $result['modVersionsOptions']['defDafUiOptions']->listable   = true;
        $result['modVersionsOptions']['defDafUiOptions']->required   = true;
        $result['modVersionsOptions']['defDafUiOptions']->sortable   = false;
        $result['modVersionsOptions']['defDafUiOptions']->filterable = false;
        $result['modVersionsOptions']['defDafUiOptions']->searchable = false;
        $result['modVersionsOptions']['defDafTypValidationOptions'] = new \stdClass();
        $result['modVersionsOptions']['defDafAttachFileOptions']    = new \stdClass();

        $result['modStatesOptions']                                 = array();
        $result['modStatesOptions']['dafId']                        = 'modStatesOptions';
        $result['modStatesOptions']['modId']                        = 'models';
        $result['modStatesOptions']['typId']                        = 'json';
        $result['modStatesOptions']['flgId']                        = 'states';
        $result['modStatesOptions']['defOrder']                     = 10;
        $result['modStatesOptions']['defIsName']                    = false;
        $result['modStatesOptions']['defIsImage']                   = false;
        $result['modStatesOptions']['defDafDefaultValue']           = new \stdClass();
        $result['modStatesOptions']['defDafDefaultValue']->stateable         = false;
        $result['modStatesOptions']['defDafDefaultValue']->states            = array();
        $result['modStatesOptions']['defDafDefaultValue']->stateInit         = '';
        $result['modStatesOptions']['defDafDefaultValue']->statesFlow        = new \stdClass();
        $result['modStatesOptions']['defDafIndexOptions']           = new \stdClass();
        $result['modStatesOptions']['defDafTypOptions']             = new \stdClass();
        $result['modStatesOptions']['defDafUiOptions']              = new \stdClass();
        $result['modStatesOptions']['defDafUiOptions']->help        = '';
        $result['modStatesOptions']['defDafUiOptions']->icon        = 'caret right';
        $result['modStatesOptions']['defDafUiOptions']->info        = '';
        $result['modStatesOptions']['defDafUiOptions']->label       = 'States Options';
        $result['modStatesOptions']['defDafUiOptions']->hidden    = false;
        $result['modStatesOptions']['defDafUiOptions']->readOnly  = false;
        $result['modStatesOptions']['defDafUiOptions']->listable  = true;
        $result['modStatesOptions']['defDafUiOptions']->required  = true;
        $result['modStatesOptions']['defDafUiOptions']->sortable  = false;
        $result['modStatesOptions']['defDafUiOptions']->filterable= false;
        $result['modStatesOptions']['defDafUiOptions']->searchable= false;
        $result['modStatesOptions']['defDafTypValidationOptions']   = new \stdClass();
        $result['modStatesOptions']['defDafAttachFileOptions']      = new \stdClass();

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

    protected function getModelsKeyNames(){

        $tableDataSource                           = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        return                                     $tableDataSource->rawQuery("(SELECT 'root' AS 'label', 'root' AS 'value') UNION ALL (SELECT modId AS 'value', modId AS 'label' FROM data_models WHERE modType = 'OBJECT')");
    }

    protected function getObjectsData(){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->getModel(null);
    }

    protected function getObjectData($p_id){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->getModel($p_id);
    }

    protected function getObjectToSaveId($p_data){
        
        return $p_data["modId"];
    }

    protected function saveObjectsData($p_data){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->addModel($p_data);
    }

    protected function editObjectsData($p_id, $p_data){
        
        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->editModel($p_id, $p_data);
    }
}