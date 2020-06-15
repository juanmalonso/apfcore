<?php
/**
 * Created by PhpStorm.
 * UserMenu: juanma
 * Date: 28/07/16
 * Time: 10:32 AM
 */

namespace Nubesys\Data\Objects;

use Nubesys\Data\Objects\Adapters\Mysql;
use Nubesys\Data\Objects\Adapters\Elastic;

use Nubesys\Core\Common;
use Nubesys\Data\Objects\Model;
use Nubesys\Data\Objects\Relation;
use Nubesys\Data\Objects\NbsObject;
use Nubesys\Data\Objects\Field;
use Nubesys\Data\Objects\Type;
use Nubesys\Data\Objects\Definition;
use Nubesys\Data\Objects\Group;

class DataEngine extends Common
{

    protected $database;
    protected $elastic;

    protected $model;
    protected $object;
    protected $collection;
    protected $type;
    protected $field;
    protected $group;
    protected $relation;
    protected $definition;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        //$this->setEventsManager($this->getDI()->get('eventsManager'));

        //TODO : Seleccionar Data Manager Segun Configuracion
        $this->database     = new Mysql($p_di);
        $this->elastic      = new Elastic($p_di);

        $this->type         = new Type($p_di, $this->database);
        $this->field        = new Field($p_di, $this->database, $this->type);
        $this->relation     = new Relation($p_di, $this->database);
        $this->model        = new Model($p_di, $this->database, $this->relation);
        $this->definition   = new Definition($p_di, $this->database, $this->model, $this->field);
        $this->object       = new NbsObject($p_di, $this->database, $this->elastic, $this->model, $this->definition);

        $this->group        = new Group($p_di, $this->database);


    }

    public function reindexObject($p_modelData, $p_id, $p_objectData){

        return $this->object->reindex($p_modelData, $p_id, $p_objectData);
    }

    public function reindexObjects($p_modelData, $p_objectsData){

        return $this->object->multiReindex($p_modelData, $p_objectsData);
    }

    public function getObject($p_model, $p_id){

        return $this->object->get($p_model, $p_id);
    }

    public function getObjectName($p_model, $p_id){

        return $this->object->name($p_model, $p_id);
    }

    public function hasObject($p_model, $p_id){

        return $this->object->has($p_model, $p_id);
    }

    public function countObjects($p_model, $p_options){

        return $this->object->count($p_model, $p_options);
    }

    public function getObjects($p_model, $p_options){

        return $this->object->list($p_model, $p_options);
    }

    public function searchObjects($p_model, $p_query){

        return $this->object->search($p_model, $p_query);
    }

    public function searchObjectsNames($p_model, $p_query){

        return $this->object->searchNames($p_model, $p_query);
    }

    public function searchMultiObjects($p_model, $p_options){

        //return $this->object->list($p_model, $p_options);
    }

    public function addObject($p_model, $p_data){

        //TODO : Validacion is ModelExist

        //$this->_eventsManager->fire("object-engine:beforeAddObject", $this, array('model'=>$p_model, 'data'=>$p_data));

        $result = $this->object->add($p_model, $p_data);

        //$this->_eventsManager->fire("object-engine:afterAddObject", $this, array('model'=>$p_model, 'data'=>$p_data, 'result' => $result));

        return $result;
    }

    public function addObjects($p_model, $p_data){

        $result = $this->object->addMultiple($p_model, $p_data);

        return $result;
    }

    public function editObject($p_model, $p_id, $p_data){

        //TODO : Validacion is ModelExist

        //$this->_eventsManager->fire("object-engine:beforeEditObject", $this, array('model'=>$p_model, 'id' => $p_id, 'data'=>$p_data));

        $result = $this->object->edit($p_model, $p_id, $p_data);

        //$this->_eventsManager->fire("object-engine:afterEditObject", $this, array('model'=>$p_model, 'id' => $p_id, 'data'=>$p_data, 'result' => $result));

        return $result;
    }

    public function removeObject($p_model, $p_id){

        //TODO : Validacion is ModelExist

        $result = $this->object->remove($p_model, $p_id);

        return $result;
    }

    public function setState($p_model, $p_id, $p_state){

        return $this->object->state($p_model, $p_id, $p_state);
    }

    public function getField($p_field, $p_extended = true){

        return $this->field->get($p_field, $p_extended);
    }

    public function addField($p_data){

        return $this->field->add($p_data);
    }

    public function editField($p_field, $p_data){

        return $this->field->edit($p_field, $p_data);
    }

    public function getGroup($p_fieldsgroup){

        return $this->group->get($p_fieldsgroup);
    }

    public function addGroup($p_data){

        return $this->group->add($p_data);
    }

    public function editGroup($p_fieldsgroup, $p_data){

        return $this->group->edit($p_fieldsgroup, $p_data);
    }

    public function getType($p_type){

        return $this->type->get($p_type);
    }

    public function addType($p_data){

        return $this->type->add($p_data);
    }

    public function editType($p_type, $p_data){

        return $this->type->edit($p_type, $p_data);
    }

    public function addModel($p_data){

        return $this->model->add($p_data);
    }

    public function editModel($p_model, $p_data){

        return $this->model->edit($p_model, $p_data);
    }

    public function isIndexableModel($p_model){

        return $this->model->isIndexable($p_model);
    }

    public function getModelIndexName($p_model){

        return $this->model->getIndexName($p_model);
    }

    public function getModel($p_model){
        
        return $this->model->get($p_model);
    }

    public function getModelName($p_model){

        return $this->model->getName($p_model);
    }

    public function getModelPluralName($p_model){

        return $this->model->getPluralName($p_model);
    }

    /*
    public function getModelDefinition($p_model){

        return $this->model->getDefinition($p_model);
    }
    */
    public function getModelsByIndexName($p_indexName){

        $result = array();

        $models = $this->model->get(null);

        foreach($models as $modelData){

            if($this->model->getIndexName($modelData['modId']) == $p_indexName){

                $result[$modelData['modId']] = $modelData;
            }
        }

        return $result;
    }

    public function getModelRelations($p_model, $p_direction){

        return $this->model->getRelations($p_model, $p_direction);
    }

    public function getRelation($p_relation){

        return $this->relation->get($p_relation);
    }

    public function addRelation($p_data){

        return $this->relation->add($p_data);
    }

    public function editRelation($p_relation, $p_data){

        return $this->relation->edit($p_relation, $p_data);
    }

    public function hasRelation($p_relation){

        return $this->relation->has($p_relation);
    }

    public function getDefinition($p_model, $p_field, $p_extended = true){

        return $this->definition->get($p_model, $p_field, $p_extended);
    }

    public function addDefinition($p_data){

        return $this->definition->add($p_data);
    }

    public function editDefinition($p_model, $p_field, $p_data){

        return $this->definition->edit($p_model, $p_field, $p_data);
    }

    public function deleteIndex($p_indexName){

       return $result = $this->elastic->deleteIndex($p_indexName);;
    }
}