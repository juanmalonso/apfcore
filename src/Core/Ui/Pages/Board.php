<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Navigation\SideMenu\SideMenu;
use Nubesys\Core\Ui\Components\App\Top\TopBar\TopBar;
use Nubesys\Core\Ui\Components\App\Selectors\TableList\TableList;

//NUBESYS DATA ENGINE
use Nubesys\Data\Objects\DataEngine;

class Board extends VueUiService {

    //OBJECTS APP VARS
    protected $dataEngine;
    protected $model                        = false;
    protected $modelId                      = null;
    protected $modelData                    = null;
    protected $modelDefinitions             = null;
    protected $modelObjectsData             = null;
    protected $modelSelectedObjectId        = null;
    protected $modelSelectedObjectData      = null;

    //APP DATA SELECTOR VARS
    protected $selectorFields               = null;
    protected $selectorActions              = null;
    protected $selectorLinks                = null;
    protected $selectorData                 = null;

    protected $action                       = null;

    public function mainAction(){

        $this->accessControl();

        $this->setTitle($this->getLocal("title"));

        $this->dataEngine                   = new DataEngine($this->getDI());
        
        //TODO : VER OPCIONES DE TIPO DE APPLICACION

        $this->setViewVar("content", "Panel Principal de Prueba");

        $this->setModel();
        
        $this->generateSideMenu();
        $this->generateTopBar();

        //DEFINIR LOGICA DE ACCIONES EN EL JSON
        $this->generateSelector();
    }

    //OBJECTS SELECTOR
    protected function generateSelector(){
        
        //PONER LOGICA SEGUN TIPO DE SELECTOR
        //TABLELIST SELECTOR
        $this->selectorFields                   = ($this->getLocal("application.selector"))["fields"];
        $this->selectorActions                  = ($this->getLocal("application.selector"))["actions"];
        $this->selectorLinks                    = ($this->getLocal("application.selector"))["links"];

        $objectSelectorParams                   = array();
        
        $objectSelectorParams['fields']         = $this->selectorFields;
        //$objectSelectorParams['data']         = $this->getLocal("application.selector.selectorData");;

        $objectSelector                         = new TableList($this->getDI());
        $this->placeComponent("main", $objectSelector, $objectSelectorParams);
        
    }

    protected function getSelectorFields(){

        if(\is_null($this->selectorFields)){

            //TODO : VER SI HACE FALTA LOGICA DISTINTA SEGUN TIPO DE SELECTOR
            $result         = array();

            //TODO: FALTA EL CAMPO NUMERO DE FILA
            
            //TODO: FALTAN LOS CAMPOS BASE, ID
            
            foreach($this->getModelDefinitions() as $field=>$definition){

                $fieldTemp                  = array();
                $fieldTemp["id"]            = $definition["id"];
                $fieldTemp["label"]         = $definition["uiOptions"]->label;
                $fieldTemp["icon"]          = $definition["uiOptions"]->icon;
                $fieldTemp["order"]         = $definition["order"];
                $fieldTemp["type"]          = $definition["type"];
                $fieldTemp["render"]        = new \stdClass();

                $fieldTemp["render"]->type  = null;

                if($definition["isName"]){

                    $fieldTemp["render"]->type  = "link";
                    $fieldTemp["render"]->url   = "#";
                }

                if($definition["isImage"]){

                    $fieldTemp["render"]->type  = "image";
                    
                    //TODO : IMG URL??
                }

                if(is_null($fieldTemp["render"]->type) && $definition["uiOptions"]->hidden){

                    $fieldTemp["render"]->type  = "hidden";
                }

                if(is_null($fieldTemp["render"]->type) && !$definition["uiOptions"]->listable){

                    $fieldTemp["render"]->type  = "none";
                }

                //TODO : FALTA LOS RENDERS DE TAGS, OBJECT REFERENCE, COLLECTIONS, ETC 

                if(is_null($fieldTemp["render"]->type)){

                    $fieldTemp["render"]->type  = "value";
                }

                $result[$fieldTemp["id"]] = $fieldTemp;

            }

            $links              = $this->getSelectorLinks();
            $linkIndex          = 0;
            
            foreach($links as $link){

                $fieldTemp                  = array();
                $fieldTemp["label"]         = $link["label"];
                $fieldTemp["render"]        = new \stdClass();

                $fieldTemp["render"]->type      = "buttonlink";
                $fieldTemp["render"]->url       = $link["url"];
                $fieldTemp["render"]->style     = $link["style"];

                $result["link" . $linkIndex] = $fieldTemp;
                $linkIndex++;
            }
            
            
            $actions            = $this->getSelectorActions();
            $actionIndex        = 0;

            foreach($actions as $action){

                $fieldTemp                  = array();
                $fieldTemp["render"]        = new \stdClass();

                $fieldTemp["render"]->type      = "buttonaction";
                $fieldTemp["render"]->url       = $link["url"];
                $fieldTemp["render"]->style     = $link["style"];

                $result["action" . $actionIndex] = $fieldTemp;
                $actionIndex++;
            }

        }else{

            $result = $this->selectorFields;
        }

        return $result;
    }

    protected function getSelectorActions(){

        if(\is_null($this->selectorActions)){
            
            $result     = array();

            //TODO : DEFINIR CONDICIONANTES

            $result[]   = array(
                "style" => "edit green",
                'url' => "#"
            );

            $result[]   = array(
                "style" => "remove green",
                'url' => "#"
            );

            //TODO : VER LOGICA DE ACCIONES ADICIONALES

        }else{

            $result = $this->selectorActions;
        }

        return $result;
    }

    protected function getSelectorLinks(){

        if(\is_null($this->selectorLinks)){
            
            $result     = array();

            //TODO : DEFINIR CONDICIONANTES

            $result[]   = array(
                "label"         => "Link A",
                "url"           => "#",
                "style"         => "teal",
            );

            $result[]   = array(
                "label"         => "Link B",
                "url"           => "#",
                "style"         => "basic",
            );

        }else{

            $result = $this->selectorLinks;
        }

        //TODO : VER LOGICA DE LINKS ADICIONALES
        return $result;
    }

    protected function getSelectorData(){

        $result     = array();

        

        return $result;
    }

    //OBJECTS APPS METHODS
    //MODEL
    protected function setModel(){

        if(!$this->model){

            $this->modelId                  = $this->getLocal("application.modelId");

            $this->modelData                = $this->getLocal("application.modelData");
            $this->modelDefinitions         = $this->getLocal("application.modelDefinitions");

            $this->modelObjectsData         = $this->getLocal("application.modelObjectsData");

            $this->model                    = true;
        }
    }

    protected function getModelId(){

        return "affiliates";
    }

    //MODEL DATA
    protected function getModelData(){

        if(is_null($this->modelData)){

            //TODO ACA SE PONE LA LOGICA DE RECUPERAR DESDE LA BASES DE DATOS
            $result                     = array();
            $result['id']               = "affiliates";
            $result['parent']           = "root";
            $result["type"]             = "OBJECT";
            $result["idStrategy"]       = "AUTOINCREMENT";
            $result["partitionMode"]    = "NONE";

            $result["uiOptions"]                            = new \stdClass();
            $result["uiOptions"]->help                      = "Texto de ayuda del Modelo";
            $result["uiOptions"]->icon                      = "users";
            $result["uiOptions"]->name                      = "Afiliado";
            $result["uiOptions"]->pluralName                = "Afiliados";
            $result["uiOptions"]->manageAs                  = "LIST";
            $result["uiOptions"]->description               = "Texto de Descripcion";

            $result["indexOptions"]                         = new \stdClass();
            $result["indexOptions"]->indexable              = true;
            $result["indexOptions"]->index                  = "affiliates";
            $result["indexOptions"]->analysis               = new \stdClass();
            $result["indexOptions"]->basemapping            = new \stdClass();

            $result["cacheOptions"]                         = new \stdClass();
            $result["cacheOptions"]->cacheable              = true;
            $result["cacheOptions"]->adapter                = "MEMORY";
            $result["cacheOptions"]->cacheLife              = 3600;

            $result["versionsOptions"]                      = new \stdClass();
            $result["statesOptions"]                        = new \stdClass();
            
        }else{

            $result = $this->modelData;
        }

        return $result;
    }


    //MODEL DEFINITIONS
    protected function getModelDefinitions(){

        if(is_null($this->modelDefinitions)){

            $definitions            = array();

            $definitionsRow                         = array();
            $definitionsRow["id"]                   = "name";
            $definitionsRow["type"]                 = "text";
            $definitionsRow["group"]                = "data";
            $definitionsRow["defaultValue"]         = "";
            $definitionsRow["order"]                = 1;
            $definitionsRow["isName"]               = 1;
            $definitionsRow["isImage"]              = 0;

            $definitionsRow["uiOptions"]                        = new \stdClass();
            $definitionsRow["uiOptions"]->help                  = "Texto de Ayuda del campo Nombre";
            $definitionsRow["uiOptions"]->icon                  = "caret right";
            $definitionsRow["uiOptions"]->label                 = "Nombre";
            $definitionsRow["uiOptions"]->hidden                = false;
            $definitionsRow["uiOptions"]->listable              = true;
            $definitionsRow["uiOptions"]->readOnly              = true;
            $definitionsRow["uiOptions"]->required              = true;
            $definitionsRow["uiOptions"]->sortable              = true;
            $definitionsRow["uiOptions"]->filterable            = true;
            $definitionsRow["uiOptions"]->searchable            = true;

            $definitionsRow["indexOptions"]                     = new \stdClass();
            $definitionsRow["indexOptions"]->indexable          = true;
            $definitionsRow["indexOptions"]->mapping            = new \stdClass();

            $definitionsRow['typeOptions']                      = new \stdClass();
            $definitionsRow['validationOptions']                = new \stdClass();
            $definitionsRow['attachFileOptions']                = new \stdClass();

            $definitions[$definitionsRow["id"]]                 = $definitionsRow;

            $definitionsRow                         = array();
            $definitionsRow["id"]                   = "description";
            $definitionsRow["type"]                 = "text";
            $definitionsRow["group"]                = "data";
            $definitionsRow["defaultValue"]         = "";
            $definitionsRow["order"]                = 1;
            $definitionsRow["isName"]               = 0;
            $definitionsRow["isImage"]              = 0;
            $definitionsRow["isImage"]              = 0;

            $definitionsRow["uiOptions"]                        = new \stdClass();
            $definitionsRow["uiOptions"]->help                  = "Texto de Ayuda del campo Descripción";
            $definitionsRow["uiOptions"]->icon                  = "caret right";
            $definitionsRow["uiOptions"]->label                 = "Descripcion";
            $definitionsRow["uiOptions"]->hidden                = false;
            $definitionsRow["uiOptions"]->listable              = true;
            $definitionsRow["uiOptions"]->readOnly              = true;
            $definitionsRow["uiOptions"]->required              = true;
            $definitionsRow["uiOptions"]->sortable              = true;
            $definitionsRow["uiOptions"]->filterable            = true;
            $definitionsRow["uiOptions"]->searchable            = true;

            $definitionsRow["indexOptions"]                     = new \stdClass();
            $definitionsRow["indexOptions"]->indexable          = true;
            $definitionsRow["indexOptions"]->mapping            = new \stdClass();

            $definitionsRow['typeOptions']                      = new \stdClass();
            $definitionsRow['validationOptions']                = new \stdClass();
            $definitionsRow['attachFileOptions']                = new \stdClass();

            $definitions[$definitionsRow["id"]]                 = $definitionsRow;
        
        }else{

            $definitions = $this->modelDefinitions;
        }

        return $definitions;
    }

    //MODEL OBJECTS
    protected function getModelObjectsData(){

        $objects                                    = array();

        if(is_null($this->modelObjectsData)){

            $objectTmp                              = array();
            $objectTmp["_id"]                       = "10";
            $objectTmp["objTIme"]                   = 15785800000000;
            $objectTmp["objOrder"]                  = 1;
            $objectTmp["objActive"]                 = 1;

            $objectTmp["objData"]                   = new \stdClass();
            $objectTmp["objData"]->name                 = "Affiliado1";
            $objectTmp["objData"]->description          = "Affiliado1 Desc";

            $objectTmp["objDateAdd"]                = "2020-01-09 14:12:43";
            $objectTmp["objUserAdd"]                = NULL;
            $objectTmp["objDateUpdated"]            = "2020-01-09 14:12:43";
            $objectTmp["objUserUpdated"]            = NULL;
            $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
            $objectTmp["objUserErased"]             = NULL;
            $objectTmp["objErased"]                 = 0;
            $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
            $objectTmp["objUserErased"]             = NULL;
            $objectTmp["objDateIndexed"]            = "2020-01-09 14:12:43";
            $objectTmp["objPartitionIndex"]         = 1;
            $objectTmp["objPage1000"]               = 1;
            $objectTmp["objPage10000"]              = 1;
            $objectTmp["objPage10000"]              = 1;

            $objects[] = $objectTmp;

            $objectTmp                              = array();
            $objectTmp["_id"]                       = "11";
            $objectTmp["objTIme"]                   = 15785800000000;
            $objectTmp["objOrder"]                  = 1;
            $objectTmp["objActive"]                 = 1;

            $objectTmp["objData"]                   = new \stdClass();
            $objectTmp["objData"]->name                 = "Affiliado2";
            $objectTmp["objData"]->description          = "Affiliado2 Desc";

            $objectTmp["objDateAdd"]                = "2020-01-09 14:12:43";
            $objectTmp["objUserAdd"]                = NULL;
            $objectTmp["objDateUpdated"]            = "2020-01-09 14:12:43";
            $objectTmp["objUserUpdated"]            = NULL;
            $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
            $objectTmp["objUserErased"]             = NULL;
            $objectTmp["objErased"]                 = 0;
            $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
            $objectTmp["objUserErased"]             = NULL;
            $objectTmp["objDateIndexed"]            = "2020-01-09 14:12:43";
            $objectTmp["objPartitionIndex"]         = 1;
            $objectTmp["objPage1000"]               = 1;
            $objectTmp["objPage10000"]              = 1;
            $objectTmp["objPage10000"]              = 1;

            $objects[] = $objectTmp;

            $objectTmp                              = array();
            $objectTmp["_id"]                       = "12";
            $objectTmp["objTIme"]                   = 15785800000000;
            $objectTmp["objOrder"]                  = 1;
            $objectTmp["objActive"]                 = 1;

            $objectTmp["objData"]                   = new \stdClass();
            $objectTmp["objData"]->name                 = "Affiliado3";
            $objectTmp["objData"]->description          = "Affiliado3 Desc";

            $objectTmp["objDateAdd"]                = "2020-01-09 14:12:43";
            $objectTmp["objUserAdd"]                = NULL;
            $objectTmp["objDateUpdated"]            = "2020-01-09 14:12:43";
            $objectTmp["objUserUpdated"]            = NULL;
            $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
            $objectTmp["objUserErased"]             = NULL;
            $objectTmp["objErased"]                 = 0;
            $objectTmp["objDateErased"]             = "2020-01-09 14:12:43";
            $objectTmp["objUserErased"]             = NULL;
            $objectTmp["objDateIndexed"]            = "2020-01-09 14:12:43";
            $objectTmp["objPartitionIndex"]         = 1;
            $objectTmp["objPage1000"]               = 1;
            $objectTmp["objPage10000"]              = 1;
            $objectTmp["objPage10000"]              = 1;

            $objects[] = $objectTmp;
        
        }else{

            $objects = $this->modelObjectsData;
        }

        return $objects;
    }

    //ROLE SIDE MENU
    protected function generateSideMenu(){
        
        //TODO REEMPLAZAR POR LOS ITEMS DE MENU DE ROL

        $user                   = $this->getLocal("navigation.user");

        $items                  = $this->getLocal("navigation.items");

        $sideMenuParams             = array();
        $sideMenuParams['user']     = $user;
        $sideMenuParams['items']    = $items;


        $sideMenu                   = new SideMenu($this->getDI());
        $this->placeComponent("side", $sideMenu, $sideMenuParams);
        
    }

    //ROLE SIDE MENU
    protected function generateTopBar(){

        $actions                = array();

        //TODO : ITEMS SEGUN MODULO

        $action                 = new \stdClass();
        $action->label          = "nuevo";
        $action->url            = "";
        $action->icon           = "plus green icon";

        $actions[]              = $action;

        $topBarParams               = array();
        $topBarParams['title']      = "Board";
        $topBarParams['actions']    = $actions;

        $topBar                     = new TopBar($this->getDI());
        $this->placeComponent("top", $topBar, $topBarParams);
        
    }

    //FOOTER
    protected function generateFooter(){

        //TODO : 
    }

    //ACCESS CONTROL
    //TODO VER SI HACE FALTA LLEVAR A LA SUPERCLASE PARA LAS APIS
    protected function accessControl(){
        
        if($this->getLocal("accessControl")){

            $loginurl = $this->getDI()->get('config')->main->url->base . "login";
            
            if($this->hasSession("user_loged")){

                if(!$this->getSession("user_loged")){

                    header("Location: " . $loginurl);
                    exit();
                }
            }else{
                
                header("Location: " . $loginurl);
                exit();
            }
        }
    }
}