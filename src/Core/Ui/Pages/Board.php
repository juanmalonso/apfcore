<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Navigation\SideMenu\SideMenu;
use Nubesys\Core\Ui\Components\App\Top\TopBar\TopBar;
use Nubesys\Core\Ui\Components\App\Selectors\TableList\TableList;

//DATA SOURCE
use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class Board extends VueUiService {

    //OBJECTS APP VARS
    
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

        $this->setDataSources();

        //TODO : VER OPCIONES DE TIPO DE APPLICACION

        $this->setViewVar("content", "Panel Principal de Prueba");

        //$this->setModel();
        
        $this->generateSideMenu();
        $this->generateTopBar();

        //DEFINIR LOGICA DE ACCIONES EN EL JSON
        $this->generateSelector();
    }

    //DATA SOURCES
    protected function setDataSources(){

        $this->dataSources = array();

        foreach($this->getLocal("application.dataSources") as $dataSoyrceName=>$dataSource){

            //TODO VER DE MODIFICAR EL DATASOURCE ADAPTER DINAMICAMENTE SEGUN OPCIONES                        

            $this->setDataSource("objects", new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSource["options"])));
        }
    }

    //OBJECTS SELECTOR
    protected function generateSelector(){
        
        //PONER LOGICA SEGUN TIPO DE SELECTOR
        
        //TABLELIST SELECTOR
        $objectsSelectorDataSource              = $this->getDataSource(($this->getLocal("application.selector"))["dataSource"]);

        $objectsSelectorParams                  = array();
        $objectsSelectorParams['fields']        = $this->getSelectorFields($objectsSelectorDataSource);
        $objectsSelectorParams['data']          = $this->getSelectorData($objectsSelectorDataSource);

        $objectsSelector                        = new TableList($this->getDI());
        $this->placeComponent("main", $objectsSelector, $objectsSelectorParams);
        
    }

    protected function getSelectorFields($p_dataSource){

        //TODO : VER SI HACE FALTA LOGICA DISTINTA SEGUN TIPO DE SELECTOR
        $result         = array();

        //TODO: FALTA EL CAMPO NUMERO DE FILA

        //ID
        $fieldId                    = array();
        $fieldId["id"]              = "_id";
        $fieldId["label"]           = "ID";
        $fieldId["order"]           = 0;
        $fieldId["type"]            = "text";
        $fieldId["render"]          = new \stdClass();
        $fieldId["render"]->type    = "hidden";
        $result[$fieldId["id"]]     = $fieldId;


        
        //TODO: FALTAN LOS CAMPOS BASE
        
        foreach($p_dataSource->getDataDefinitions() as $field=>$definition){

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

        return $result;
    }

    protected function getSelectorActions(){

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

        return $result;
    }

    protected function getSelectorLinks(){

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

        //TODO : VER LOGICA DE LINKS ADICIONALES
        return $result;
    }

    protected function getSelectorData($p_dataSource){

        return $p_dataSource->getData();
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

    //TOP BAR
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

        //TODO : FALTA FOOTER
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