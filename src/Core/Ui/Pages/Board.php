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

        //TODO : VER LOGICA Y OPCIONES DE TIPO DE APPLICACION

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

        //FALTAN CARACTERISTICAS SEGUN MODEL (SHOW ID, SHOW NUM ROWS, SHOW DATEADD, SHOW DATE MODIFF, SHOW USER, ACTIVATABLE, DROPABLE,ETC)

        //ID
        $field                      = array();
        $field["renderType"]        = "HIDDEN";
        $result["_id"]              = $field;

        //ROW NUM
        $field                      = array();
        $field["renderType"]        = "HIDDEN";
        $result["rownum"]           = $field;
        
        //TODO: FALTAN LOS CAMPOS BASE
        //TODO: FALTAN LOS CAMPOS RELACION
        //TODO: FALTAN LOS CAMPOS TAGS
        //TODO: FALTAN LOS CAMPOS OBJECTSR
        //TODO: FALTAN LOS CAMPOS OBJECTR
        
        foreach($p_dataSource->getDataDefinitions() as $field=>$definition){

            $fieldTemp                  = array();
            $fieldTemp['renderType']    = "VALUE";
            
            if($definition["uiOptions"]->listable){

                if($definition["uiOptions"]->hidden){

                    $fieldTemp["renderType"]            = "HIDDEN";
                }else{

                    if($definition["isName"]){

                        $fieldTemp["renderType"]        = "LINK";
                        $fieldTemp["urlMap"]            = "#";

                    }

                    if($definition["isImage"]){

                        $fieldTemp["renderType"]        = "IMAGE";
                        $fieldTemp["imgSrcMap"]         = "#";

                    }

                    $fieldTemp["label"]                 = $definition["uiOptions"]->label;
                    $fieldTemp["icon"]                  = $definition["uiOptions"]->icon;
                    $fieldTemp["order"]                 = $definition["order"];
                }
            }else{

                $fieldTemp['renderType']    = "HIDDEN";
            }

            $result[$definition["id"]] = $fieldTemp;

        }

        //TODO: FALTA LOGICA DE COMO MOSTRAR LAS ACCIONES Y LOS LINKS SEGUN MODELO (SELECT MENU, CONTEXTUAL MENU, ITEMS)

        $links              = $this->getSelectorLinks();
        $linkIndex          = 0;
        
        foreach($links as $link){

            $fieldTemp                  = array();
            $fieldTemp["renderType"]    = "LINKBUTTON";
            $fieldTemp["label"]         = $link["label"];
            $fieldTemp["urlMap"]        = $link["urlMap"];
            $fieldTemp["style"]         = $link["style"];

            //TODO: FALTA LA LOGICA DE ACTIONS JS (PARA SERVICE y PARA LOCAL)

            $result["link" . $linkIndex] = $fieldTemp;
            $linkIndex++;
        }
        
        
        $actions            = $this->getSelectorActions();
        $actionIndex        = 0;

        foreach($actions as $action){

            $fieldTemp                  = array();
            $fieldTemp["renderType"]    = "ACTIONBUTTON";
            $fieldTemp["label"]         = $action["label"];
            $fieldTemp["urlMap"]        = $action["urlMap"];
            $fieldTemp["style"]         = $action["style"];

            //TODO: FALTA LA LOGICA DE ACTIONS JS (PARA SERVICE y PARA LOCAL)

            $result["action" . $actionIndex] = $fieldTemp;
            $actionIndex++;
        }

        return $result;
    }

    protected function getSelectorActions(){

        $result     = array();

        //TODO : DEFINIR CONDICIONANTES

        $result[]   = array(
            "label"         => "editar",
            "style"         => "edit green",
            "urlMap"        => "#"
        );

        $result[]   = array(
            "label"         => "borrar",
            "style"         => "edit red",
            "urlMap"        => "#"
        );

        //TODO : VER LOGICA DE ACCIONES ADICIONALES
        return $result;
    }

    protected function getSelectorLinks(){

        $result     = array();

        //TODO : DEFINIR CONDICIONANTES

        $result[]   = array(
            "label"         => "Link A",
            "urlMap"        => "#",
            "style"         => "teal",
        );

        $result[]   = array(
            "label"         => "Link B",
            "urlMap"        => "#",
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