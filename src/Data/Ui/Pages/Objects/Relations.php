<?php

namespace Nubesys\Data\Ui\Pages\Objects;

use Nubesys\Core\Ui\Pages\AppCrud;

class Relations extends AppCrud {

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
        $result['modId']['modId']                                   = 'relations';
        $result['modId']['typId']                                   = 'text';
        $result['modId']['flgId']                                   = 'data';
        $result['modId']['defOrder']                                = 1;
        $result['modId']['defIsName']                               = true;
        $result['modId']['defIsImage']                              = false;
        $result['modId']['defDafIndexOptions']                      = new \stdClass();
        $result['modId']['defDafDefaultValue']                      = '';
        $result['modId']['defDafTypOptions']                        = new \stdClass();
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

        $result['relLeftModId']                                            = array();
        $result['relLeftModId']['dafId']                                   = 'relLeftModId';
        $result['relLeftModId']['modId']                                   = 'relations';
        $result['relLeftModId']['typId']                                   = 'options';
        $result['relLeftModId']['flgId']                                   = 'data';
        $result['relLeftModId']['defOrder']                                = 2;
        $result['relLeftModId']['defIsName']                               = true;
        $result['relLeftModId']['defIsImage']                              = false;
        $result['relLeftModId']['defDafIndexOptions']                      = new \stdClass();
        $result['relLeftModId']['defDafDefaultValue']                      = '';
        $result['relLeftModId']['defDafTypOptions']                        = new \stdClass();
        $result['relLeftModId']['defDafTypOptions']->data                  = $this->getModelsObjectsKeyNames();
        $result['relLeftModId']['defDafUiOptions']                         = new \stdClass();
        $result['relLeftModId']['defDafUiOptions']->help                  = '';
        $result['relLeftModId']['defDafUiOptions']->icon                  = 'caret right';
        $result['relLeftModId']['defDafUiOptions']->info                  = '';
        $result['relLeftModId']['defDafUiOptions']->label                 = 'Left Model';
        $result['relLeftModId']['defDafUiOptions']->hidden              = false;
        $result['relLeftModId']['defDafUiOptions']->readOnly            = false;;
        $result['relLeftModId']['defDafUiOptions']->listable            = true;
        $result['relLeftModId']['defDafUiOptions']->required            = true;
        $result['relLeftModId']['defDafUiOptions']->sortable            = false;
        $result['relLeftModId']['defDafUiOptions']->filterable          = false;
        $result['relLeftModId']['defDafUiOptions']->searchable          = false;
        $result['relLeftModId']['defDafTypValidationOptions']                  = new \stdClass();
        $result['relLeftModId']['defDafAttachFileOptions']                     = new \stdClass();

        $result['relLeftDirection']                                          = array();
        $result['relLeftDirection']['dafId']                                 = 'relLeftDirection';
        $result['relLeftDirection']['modId']                                 = 'relations';
        $result['relLeftDirection']['typId']                                 = 'options';
        $result['relLeftDirection']['flgId']                                 = 'data';
        $result['relLeftDirection']['defOrder']                              = 3;
        $result['relLeftDirection']['defIsName']                             = true;
        $result['relLeftDirection']['defIsImage']                            = false;
        $result['relLeftDirection']['defDafIndexOptions']                    = new \stdClass();
        $result['relLeftDirection']['defDafDefaultValue']                    = 'IN';
        $result['relLeftDirection']['defDafTypOptions']                      = new \stdClass();
        $result['relLeftDirection']['defDafTypOptions']->data                    = array();
        $result['relLeftDirection']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'IN','value'=>'IN'));
        $result['relLeftDirection']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'OUT','value'=>'OUT'));
        $result['relLeftDirection']['defDafUiOptions']                       = new \stdClass();
        $result['relLeftDirection']['defDafUiOptions']->help                = '';
        $result['relLeftDirection']['defDafUiOptions']->icon                = 'caret right';
        $result['relLeftDirection']['defDafUiOptions']->info                = '';
        $result['relLeftDirection']['defDafUiOptions']->label               = 'Left Direction';
        $result['relLeftDirection']['defDafUiOptions']->hidden            = false;
        $result['relLeftDirection']['defDafUiOptions']->readOnly          = false;
        $result['relLeftDirection']['defDafUiOptions']->listable          = true;
        $result['relLeftDirection']['defDafUiOptions']->required          = true;
        $result['relLeftDirection']['defDafUiOptions']->sortable          = false;
        $result['relLeftDirection']['defDafUiOptions']->filterable        = false;
        $result['relLeftDirection']['defDafUiOptions']->searchable        = false;
        $result['relLeftDirection']['defDafTypValidationOptions']            = new \stdClass();
        $result['relLeftDirection']['defDafAttachFileOptions']               = new \stdClass();

        $result['relRightModId']                                            = array();
        $result['relRightModId']['dafId']                                   = 'relRightModId';
        $result['relRightModId']['modId']                                   = 'relations';
        $result['relRightModId']['typId']                                   = 'options';
        $result['relRightModId']['flgId']                                   = 'data';
        $result['relRightModId']['defOrder']                                = 4;
        $result['relRightModId']['defIsName']                               = true;
        $result['relRightModId']['defIsImage']                              = false;
        $result['relRightModId']['defDafIndexOptions']                      = new \stdClass();
        $result['relRightModId']['defDafDefaultValue']                      = '';
        $result['relRightModId']['defDafTypOptions']                        = new \stdClass();
        $result['relRightModId']['defDafTypOptions']->data                  = $this->getModelsObjectsKeyNames();
        $result['relRightModId']['defDafUiOptions']                         = new \stdClass();
        $result['relRightModId']['defDafUiOptions']->help                  = '';
        $result['relRightModId']['defDafUiOptions']->icon                  = 'caret right';
        $result['relRightModId']['defDafUiOptions']->info                  = '';
        $result['relRightModId']['defDafUiOptions']->label                 = 'Right Model';
        $result['relRightModId']['defDafUiOptions']->hidden              = false;
        $result['relRightModId']['defDafUiOptions']->readOnly            = false;;
        $result['relRightModId']['defDafUiOptions']->listable            = true;
        $result['relRightModId']['defDafUiOptions']->required            = true;
        $result['relRightModId']['defDafUiOptions']->sortable            = false;
        $result['relRightModId']['defDafUiOptions']->filterable          = false;
        $result['relRightModId']['defDafUiOptions']->searchable          = false;
        $result['relRightModId']['defDafTypValidationOptions']                  = new \stdClass();
        $result['relRightModId']['defDafAttachFileOptions']                     = new \stdClass();

        $result['relRightDirection']                                          = array();
        $result['relRightDirection']['dafId']                                 = 'relRightDirection';
        $result['relRightDirection']['modId']                                 = 'relations';
        $result['relRightDirection']['typId']                                 = 'options';
        $result['relRightDirection']['flgId']                                 = 'data';
        $result['relRightDirection']['defOrder']                              = 5;
        $result['relRightDirection']['defIsName']                             = true;
        $result['relRightDirection']['defIsImage']                            = false;
        $result['relRightDirection']['defDafIndexOptions']                    = new \stdClass();
        $result['relRightDirection']['defDafDefaultValue']                    = 'OUT';
        $result['relRightDirection']['defDafTypOptions']                      = new \stdClass();
        $result['relRightDirection']['defDafTypOptions']->data                    = array();
        $result['relRightDirection']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'IN','value'=>'IN'));
        $result['relRightDirection']['defDafTypOptions']->data[]                  = $this->toObject(array('label'=>'OUT','value'=>'OUT'));
        $result['relRightDirection']['defDafUiOptions']                       = new \stdClass();
        $result['relRightDirection']['defDafUiOptions']->help                = '';
        $result['relRightDirection']['defDafUiOptions']->icon                = 'caret right';
        $result['relRightDirection']['defDafUiOptions']->info                = '';
        $result['relRightDirection']['defDafUiOptions']->label               = 'Right Direction';
        $result['relRightDirection']['defDafUiOptions']->hidden            = false;
        $result['relRightDirection']['defDafUiOptions']->readOnly          = false;
        $result['relRightDirection']['defDafUiOptions']->listable          = true;
        $result['relRightDirection']['defDafUiOptions']->required          = true;
        $result['relRightDirection']['defDafUiOptions']->sortable          = false;
        $result['relRightDirection']['defDafUiOptions']->filterable        = false;
        $result['relRightDirection']['defDafUiOptions']->searchable        = false;
        $result['relRightDirection']['defDafTypValidationOptions']            = new \stdClass();
        $result['relRightDirection']['defDafAttachFileOptions']               = new \stdClass();

        $result['relCardinality']                                    = array();
        $result['relCardinality']['dafId']                           = 'relCardinality';
        $result['relCardinality']['modId']                           = 'relations';
        $result['relCardinality']['typId']                           = 'options';
        $result['relCardinality']['flgId']                           = 'data';
        $result['relCardinality']['defOrder']                        = 6;
        $result['relCardinality']['defIsName']                       = true;
        $result['relCardinality']['defIsImage']                      = false;
        $result['relCardinality']['defDafIndexOptions']              = new \stdClass();
        $result['relCardinality']['defDafDefaultValue']              = '1:n';
        $result['relCardinality']['defDafTypOptions']                = new \stdClass();
        $result['relCardinality']['defDafTypOptions']->data              = array();
        $result['relCardinality']['defDafTypOptions']->data[]            = $this->toObject(array('label'=>'1:n','value'=>'1:n'));
        $result['relCardinality']['defDafTypOptions']->data[]            = $this->toObject(array('label'=>'1:1','value'=>'1:1'));
        $result['relCardinality']['defDafUiOptions']                 = new \stdClass();
        $result['relCardinality']['defDafUiOptions']->help          = '';
        $result['relCardinality']['defDafUiOptions']->icon          = 'caret right';
        $result['relCardinality']['defDafUiOptions']->info          = '';
        $result['relCardinality']['defDafUiOptions']->label         = 'Cardinalidad';
        $result['relCardinality']['defDafUiOptions']->hidden      = false;
        $result['relCardinality']['defDafUiOptions']->readOnly    = false;
        $result['relCardinality']['defDafUiOptions']->listable    = true;
        $result['relCardinality']['defDafUiOptions']->required    = true;
        $result['relCardinality']['defDafUiOptions']->sortable    = false;
        $result['relCardinality']['defDafUiOptions']->filterable  = false;
        $result['relCardinality']['defDafUiOptions']->searchable  = false;
        $result['relCardinality']['defDafTypValidationOptions']      = new \stdClass();
        $result['relCardinality']['defDafAttachFileOptions']         = new \stdClass();

        $result['relIndexOptions']                                  = array();
        $result['relIndexOptions']['dafId']                         = 'relIndexOptions';
        $result['relIndexOptions']['modId']                         = 'relations';
        $result['relIndexOptions']['typId']                         = 'json';
        $result['relIndexOptions']['flgId']                         = 'index';
        $result['relIndexOptions']['defOrder']                      = 7;
        $result['relIndexOptions']['defIsName']                     = true;
        $result['relIndexOptions']['defIsImage']                    = false;
        $result['relIndexOptions']['defDafIndexOptions']            = new \stdClass();
        $result['relIndexOptions']['defDafDefaultValue']            = new \stdClass();
        $result['relIndexOptions']['defDafDefaultValue']->indexable     = true;
        $result['relIndexOptions']['defDafTypOptions']              = new \stdClass();
        $result['relIndexOptions']['defDafUiOptions']               = new \stdClass();
        $result['relIndexOptions']['defDafUiOptions']->help        = '';
        $result['relIndexOptions']['defDafUiOptions']->icon        = 'caret right';
        $result['relIndexOptions']['defDafUiOptions']->info        = '';
        $result['relIndexOptions']['defDafUiOptions']->label       = 'Index Options';
        $result['relIndexOptions']['defDafUiOptions']->hidden    = false;
        $result['relIndexOptions']['defDafUiOptions']->readOnly  = false;
        $result['relIndexOptions']['defDafUiOptions']->listable  = true;
        $result['relIndexOptions']['defDafUiOptions']->required  = true;
        $result['relIndexOptions']['defDafUiOptions']->sortable  = false;
        $result['relIndexOptions']['defDafUiOptions']->filterable= false;
        $result['relIndexOptions']['defDafUiOptions']->searchable= false;
        $result['relIndexOptions']['defDafTypValidationOptions']    = new \stdClass();
        $result['relIndexOptions']['defDafAttachFileOptions']       = new \stdClass();

        $result['relUiOptions']                                     = array();
        $result['relUiOptions']['dafId']                            = 'relUiOptions';
        $result['relUiOptions']['modId']                            = 'relations';
        $result['relUiOptions']['typId']                            = 'json';
        $result['relUiOptions']['flgId']                            = 'ui';
        $result['relUiOptions']['defOrder']                         = 8;
        $result['relUiOptions']['defIsName']                        = true;
        $result['relUiOptions']['defIsImage']                       = false;
        $result['relUiOptions']['defDafIndexOptions']               = new \stdClass();
        $result['relUiOptions']['defDafDefaultValue']               = new \stdClass();
        $result['relUiOptions']['defDafDefaultValue']->help        = '';
        $result['relUiOptions']['defDafDefaultValue']->info        = '';
        $result['relUiOptions']['defDafDefaultValue']->hidden    = false;
        $result['relUiOptions']['defDafDefaultValue']->listable  = false;
        $result['relUiOptions']['defDafDefaultValue']->linkable  = true;
        $result['relUiOptions']['defDafDefaultValue']->readOnly  = false;
        $result['relUiOptions']['defDafDefaultValue']->required  = false;
        $result['relUiOptions']['defDafDefaultValue']->filterable  = false;
        $result['relUiOptions']['defDafDefaultValue']->searchable  = false;
        $result['relUiOptions']['defDafDefaultValue']->editAs      = 'FIELD';
        $result['relUiOptions']['defDafDefaultValue']->editGroup   = 'data';
        $result['relUiOptions']['defDafTypOptions']                 = new \stdClass();
        $result['relUiOptions']['defDafUiOptions']                  = new \stdClass();
        $result['relUiOptions']['defDafUiOptions']->help          = $this->getValidFieldGroupsIds();
        $result['relUiOptions']['defDafUiOptions']->icon          = 'caret right';
        $result['relUiOptions']['defDafUiOptions']->info          = '';
        $result['relUiOptions']['defDafUiOptions']->label         = 'Model Ui Tags';
        $result['relUiOptions']['defDafUiOptions']->hidden      = false;
        $result['relUiOptions']['defDafUiOptions']->readOnly    = false;
        $result['relUiOptions']['defDafUiOptions']->listable    = true;
        $result['relUiOptions']['defDafUiOptions']->required    = true;
        $result['relUiOptions']['defDafUiOptions']->sortable    = false;
        $result['relUiOptions']['defDafUiOptions']->filterable  = false;
        $result['relUiOptions']['defDafUiOptions']->searchable  = false;
        $result['relUiOptions']['defDafTypValidationOptions']       = new \stdClass();
        $result['relUiOptions']['defDafAttachFileOptions']          = new \stdClass();

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

            $result                 = $dataEngine->getRelation($modId, null, false);
        }

        return $result;
    }

    protected function getObjectData($p_id){

        $result                 = array();

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        if($dataEngine->hasRelation($p_id)){

            $result             = $dataEngine->getRelation($p_id);
        }else{

            $result             = array();
            $result['modId']    = $p_id;
        }

        return $result;
    }

    protected function getObjectToSaveId($p_data){
        
        return $p_data["modId"];
    }

    protected function getModelsObjectsKeyNames(){

        $result                         = array();

        $tableDataSource                = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        $queryResult                    = $tableDataSource->rawQuery("SELECT modId AS 'label', modid AS 'value' FROM data_models WHERE modType = 'OBJECT'");

        foreach($queryResult as $row){

            $result[]                   = $this->toObject($row);
        }

        return $result;
    }

    protected function getValidFieldGroupsIds(){

        $result                 = "";

        $tableDataSource        = new \Nubesys\Data\DataSource\DataSourceAdapters\Table($this->getDI());

        $tableQueryResult       = $tableDataSource->rawQuery("SELECT flgId AS 'id' FROM fields_groups");

        $col = 1;
        $maxcol = 5;

        $result .= "<table class='ui celled table'>";
        $result .= "<thead><tr><th colspan='" . $maxcol . "'>Valid editGropus</th></thead><tbody><tr>";

        foreach($tableQueryResult as $fieldGroup){

            $result .= "<td>" . $fieldGroup['id'] . "</td>";

            if($col == $maxcol){

                $result .= "</tr>";
                $col = 1;
            }else{

                $col++;
            }
        }

        if($col != 1){

            $result .= "</tr>";
        }

        $result .= "</tbody></table>";

        return $result;
    }

    /* 
    
  <thead>
    <tr><th>Name</th>
    <th>Age</th>
    <th>Job</th>
  </tr></thead>
  <tbody>
    <tr>
      <td data-label="Name">James</td>
      <td data-label="Age">24</td>
      <td data-label="Job">Engineer</td>
    </tr>
    <tr>
      <td data-label="Name">Jill</td>
      <td data-label="Age">26</td>
      <td data-label="Job">Engineer</td>
    </tr>
    <tr>
      <td data-label="Name">Elyse</td>
      <td data-label="Age">24</td>
      <td data-label="Job">Designer</td>
    </tr>
  </tbody>
</table>
    */

    protected function saveObjectsData($p_data){

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        return $dataEngine->addRelation($p_data);
    }

    protected function editObjectsData($p_id, $p_data){

        $result                 = false;

        $dataEngine             = new \Nubesys\Data\Objects\DataEngine($this->getDI());

        if($dataEngine->hasRelation($p_id)){

            $result             = $dataEngine->editRelation($p_id, $p_data);
        }else{

            $result             = $this->saveObjectsData($p_data);
        }
        
        return $result;
    }
}