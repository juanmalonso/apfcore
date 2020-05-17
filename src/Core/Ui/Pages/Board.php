<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Navigation\SideMenu\SideMenu;
use Nubesys\Core\Ui\Components\App\Top\TopBar\TopBar;
use Nubesys\Core\Ui\Components\App\Selectors\TableList\TableList;
use Nubesys\Core\Ui\Components\App\Editors\Form\Form;

//DATA SOURCE
use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Table as TableDataSource;

class Board extends VueUiService {

    //APP DATA SELECTOR VARS
    protected $selectorFields               = null;
    protected $selectorActions              = null;
    protected $selectorLinks                = null;
    protected $selectorData                 = null;

    protected $action                       = null;
    protected $baseUrlMaps                  = null;

    //MAIN ACTION
    public function mainAction(){

        $this->accessControl();

        $this->setTitle($this->getLocal("title"));

        $this->setDataSources();

        //TODO : VER LOGICA Y OPCIONES DE TIPO DE APPLICACION
        $this->generateSideMenu();
        
        $this->callServiceAction();

        $this->generateTopBar();
    }

    //LIST SERVICE ACTION
    protected function listAction(){

        $this->setViewVar("content", "LIST");

        $this->generateSelector();
    }

    //EDIT SERVICE ACTION
    protected function editAction(){

        $this->setViewVar("content", "EDIT");

        $this->generateEditor();
    }

    //AJAX REMOTE SERVICES
    public function loadDataService(){

        $this->setDataSources();

        //sleep(3);

        if($this->hasJsonParam()){

            $param      = $this->getJsonParam();

            if(isset($param["datasource"])){

                $objectsSelectorDataSource              = $this->getDataSource($param["datasource"]);

                $dataSourceQuery            = array();
                $dataSourceQuery['page']    = (isset($param["page"])) ? $param["page"] : 1;
                $dataSourceQuery['rows']    = (isset($param["rows"])) ? $param["rows"] : 10;

                $this->setServiceSuccess($objectsSelectorDataSource->getData($dataSourceQuery));
            }else{

                $this->setServiceError("Datasource param not Found");    
            }
        }else{

            $this->setServiceError("Invalid Params");
        }

    }

    //CALL SERVICE ACTION
    protected function callServiceAction(){

        $action             = ($this->getLocal("application"))['serviceActions']['default'];
        $paramNum           = ($this->getLocal("application"))['serviceActions']['paramNum'];
        
        if($this->hasUrlParam($paramNum)){

            if(!strstr(":", $this->getUrlParam($paramNum))){

                $action     = $this->getUrlParam($paramNum);
            }
        }

        $methodName         = $action . "Action";

        if(method_exists($this, $methodName)){

            $this->action   = $action;
            $this->$methodName();
        }else{

            exit("Method " . $methodName . " Not Found");
        }
    }

    //DATA SOURCES
    protected function setDataSources(){
        
        $this->dataSources = array();
        
        foreach($this->getLocal("application.dataSources") as $dataSourceName=>$dataSource){

            //TODO VER DE MODIFICAR EL DATASOURCE ADAPTER DINAMICAMENTE SEGUN OPCIONES
            
            switch ($dataSource["adapter"]) {
                case 'objects':
                    $this->setDataSource($dataSourceName, new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSource["options"])));
                    break;
                
                case 'table':
                    $this->setDataSource($dataSourceName, new DataSource($this->getDI(), new TableDataSource($this->getDI(), $dataSource["options"])));
                    break;
                default:
                    # code...
                    break;
            }
        }
        
    }

    //OBJECTS EDITOR
    protected function generateEditor(){
        
        //PONER LOGICA SEGUN TIPO DE EDITOR
        
        //FORM EDITOR
        $objectsEditorDataSource                = $this->getDataSource($this->getLocal("application.editor.objectsDataSource"));

        $objectsEditorParams                    = array();
        $objectsEditorParams['datasource']      = $this->getLocal("application.editor.objectsDataSource");
        $objectsEditorParams['fields']          = $this->getEditorFields($objectsEditorDataSource);
        $objectsEditorParams['fieldsGroups']     = $this->getEditorFieldsGroups($this->getDataSource($this->getLocal("application.editor.fgroupsDataSource")));
        $objectsEditorParams['data']            = $this->getEditorData($objectsEditorDataSource);

        $objectsEditor                          = new Form($this->getDI());
        $this->placeComponent("main", $objectsEditor, $objectsEditorParams);
        
    }

    //OBJECTS SELECTOR
    protected function generateSelector(){
        
        //PONER LOGICA SEGUN TIPO DE SELECTOR
        
        //TABLELIST SELECTOR
        $objectsSelectorDataSource              = $this->getDataSource($this->getLocal("application.selector.dataSource"));

        $objectsSelectorParams                  = array();
        $objectsSelectorParams['datasource']    = $this->getLocal("application.selector.dataSource");
        $objectsSelectorParams['fields']        = $this->getSelectorFields($objectsSelectorDataSource);
        $objectsSelectorParams['data']          = $this->getSelectorData($objectsSelectorDataSource);
        $objectsSelectorParams['paginator']     = $this->getLocal("application.selector.paginator");

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

        $links              = $this->getLocal("application.selector.links");
        $linkIndex          = 0;
        
        foreach($links as $link){

            $fieldTemp                  = array();
            $fieldTemp["type"]          = $link['type'];
            $fieldTemp["renderType"]    = "LINKBUTTON";
            $fieldTemp["label"]         = $link["label"];
            $fieldTemp["style"]         = $link["style"];

            if($link['type'] == 'link'){

                $fieldTemp["urlMap"]        = $link["urlMap"];
            }else if($link['type'] == 'actions'){

                $fieldTemp["actions"]       = $link["actions"];
            }

            $result["link" . $linkIndex] = $fieldTemp;
            $linkIndex++;
        }
        
        
        $actions            = $this->getLocal("application.selector.actions");
        $actionIndex        = 0;
        
        foreach($actions as $action){

            $fieldTemp                  = array();
            $fieldTemp["type"]          = $action['type'];
            $fieldTemp["renderType"]    = "ACTIONBUTTON";
            $fieldTemp["label"]         = $action["label"];
            $fieldTemp["urlMap"]        = $action["urlMap"];
            $fieldTemp["style"]         = $action["style"];

            if($action['type'] == 'link'){

                $fieldTemp["urlMap"]        = $action["urlMap"];
            }else if($action['type'] == 'actions'){

                $fieldTemp["actions"]       = $action["actions"];
            }

            $result["action" . $actionIndex] = $fieldTemp;
            $actionIndex++;
        }
        
        return $result;
    }

    protected function getSelectorData($p_dataSource){
        
        $dataSourceQuery            = array();
        $dataSourceQuery['page']    = ($this->hasUrlParam("page")) ? $this->getUrlParam("page") : 1;
        $dataSourceQuery['rows']    = ($this->hasUrlParam("rows")) ? $this->getUrlParam("rows") : 10;

        return $p_dataSource->getData($dataSourceQuery);
    }

    protected function getEditorFields($p_dataSource){
        
        //TODO : VER SI HACE FALTA LOGICA DISTINTA SEGUN TIPO DE EDITOR

        //TODO : VER LOGICA DE LAYOUT DE FORMULARIOS Y EDITORES
        $result             = array();
        $result['main']     = array();
        $result['info']     = array();
        $result['side']     = array();

        //FALTAN CARACTERISTICAS SEGUN MODEL (SHOW ID, SHOW NUM ROWS, SHOW DATEADD, SHOW DATE MODIFF, SHOW USER, ACTIVATABLE, DROPABLE,ETC)

        //TODO : VER LOGICA DE ID SOLO READONLY  EN EL CASO DE EDITAR
        
        //VER LOS CAMAPOR ESPECIALES ORDER, STATUS, ACTIVATABLE ETC
        //TODO: FALTAN LOS CAMPOS BASE
        //TODO: FALTAN LOS CAMPOS RELACION
        //TODO: FALTAN LOS CAMPOS TAGS
        //TODO: FALTAN LOS CAMPOS OBJECTSR
        //TODO: FALTAN LOS CAMPOS OBJECTR
        

        foreach($p_dataSource->getDataDefinitions() as $field=>$definition){

            $fieldTemp                  = array();

            $fieldTemp['id']            = $definition["id"];
            $fieldTemp['type']          = $definition["type"];
            $fieldTemp['group']         = $definition["group"];
            $fieldTemp['order']         = $definition["order"];
            $fieldTemp['default']       = $definition["defaultValue"];
            $fieldTemp["label"]         = $definition["uiOptions"]->label;
            $fieldTemp["icon"]          = $definition["uiOptions"]->icon;
            $fieldTemp["help"]          = $definition["uiOptions"]->help;
            $fieldTemp["info"]          = $definition["uiOptions"]->info;
            $fieldTemp["hidden"]        = $definition["uiOptions"]->hidden;
            $fieldTemp["required"]      = $definition["uiOptions"]->required;
            $fieldTemp["readOnly"]      = $definition["uiOptions"]->readOnly;

            $fieldTemp["options"]       = array();

            foreach($definition["typeOptions"] as $option=>$value){

                $fieldTemp["options"][$option]   = $value; 
            }
            
            $fieldTemp["options"]["validation"]  = $definition["validationOptions"];
            $fieldTemp["options"]["file"]        = $definition["attachFileOptions"];

            if(!isset($result['main'][$fieldTemp['group']])){

                $result['main'][$fieldTemp['group']] = array();
            }

            $result['main'][$fieldTemp['group']][$definition["id"]] = $fieldTemp;
            
        }

        //TODO: FALTA LOGICA DE COMO LAS RELACIONES
        
        return $result;
    }

    protected function getEditorFieldsGroups($p_dataSource){
        
        $result                     = array();

        $dataSourceQuery            = array();
        
        $queryResult                = $p_dataSource->getData($dataSourceQuery);

        foreach ($queryResult['objects'] as $object) {
            
            $result[$object['flgId']] = $object['flgName'];
        }

        return $result;
    }

    protected function getEditorData($p_dataSource){
        
        /*$dataSourceQuery            = array();
        $dataSourceQuery['page']    = ($this->hasUrlParam("page")) ? $this->getUrlParam("page") : 1;
        $dataSourceQuery['rows']    = ($this->hasUrlParam("rows")) ? $this->getUrlParam("rows") : 10;

        return $p_dataSource->getData($dataSourceQuery);*/

        return array();
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

        $topBar                     = new TopBar($this->getDI());
        $this->placeComponent("top", $topBar, $this->getLocal("application.topBar.{$this->action}"));
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