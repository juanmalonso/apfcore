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
use Nubesys\Data\DataSource\DataSourceAdapters\Custom as CustomDataSource;

class AppCrud extends VueUiService {

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

        //MAIN MODEL
        //TODO : validar posibles errores aqui!
        $this->setJsDataVar("model", $this->getLocal("application.dataSources." . $this->getLocal("application.editor.objectsDataSource") . ".options.model"));
        
        //TODO : VER LOGICA Y OPCIONES DE TIPO DE APPLICACION
        $this->generateSideMenu();
        
        $this->callServiceAction();

        $this->generateTopBar();
    }

    //SERVICE ACTIONS
    //LIST SERVICE ACTION
    protected function listAction(){

        $this->generateSelector();
    }

    //ADD SERVICE ACTION
    protected function addAction(){
        
        $editorAditionalParams      = array();

        if($this->hasPostParam("datasource")){

            $toSaveData             = $this->getEditorSaveData($this->allPostParams());
            
            if($toSaveData !== false){

                $saveDataResult     = false;

                if($this->hasLocal("application.editor.objectsSaveMethod")){

                    $methodName = $this->getLocal("application.editor.objectsSaveMethod");

                    if(method_exists($this, $methodName)){

                        $saveDataResult                     = $this->$methodName($toSaveData);
                        
                    }else{

                        //TODO: METHOS NOT FOUND
                    }
                }else{

                    $objectsEditorDataSource                = $this->getDataSource($this->getLocal("application.editor.objectsDataSource"));

                    $saveDataResult                         = $this->addEditorData($objectsEditorDataSource, $toSaveData);
                }
                
                if($saveDataResult !== false){

                    //TODO: REDIRECT TO LIST

                    header("Location: " . $this->getLocal("application.urlMaps.LIST"));
                    exit();
                }else{

                    $editorAditionalParams['message'] = "No se pudieron cargar los datos!";
                }
                
            }else{

                $editorAditionalParams['message'] = "Datos Invalidos!";
            }
        }

        $this->generateEditor($editorAditionalParams);
    }

    //EDIT SERVICE ACTION
    protected function editAction(){
        
        $editorAditionalParams      = array();
        
        if($this->hasPostParam("datasource")){
           
            $toEditData             = $this->getEditorSaveData($this->allPostParams());
            
            if($toEditData !== false){

                $objectId           = false;

                if($this->hasLocal("application.editor.objectToSaveIdMethod")){
                    
                    $objectIdMethodName                 = $this->getLocal("application.editor.objectToSaveIdMethod");

                    if(method_exists($this, $objectIdMethodName)){

                        $objectId                       = $this->$objectIdMethodName($toEditData);
                    }else{

                        //TODO: METHODS NOT FOUND
                    }
                }else{

                    if($this->hasLocal("application.editor.objectToSaveIdParamNum")){

                        $paramNum       = $this->getLocal("application.editor.objectToSaveIdParamNum");
    
                        $objectId       = ($this->hasUrlParam($paramNum)) ? $this->getUrlParam($paramNum) : false;
                    }else{
    
                        $objectId       = (isset($toEditData["_id"])) ? $toEditData["_id"] : false;
                    }
                }
                
                if($objectId != false){

                    $editDataResult     = false;

                    if($this->hasLocal("application.editor.objectsEditMethod")){

                        $methodName = $this->getLocal("application.editor.objectsEditMethod");

                        if(method_exists($this, $methodName)){

                            $editDataResult                     = $this->$methodName($objectId, $toEditData);
                        }else{

                            //TODO: METHODS NOT FOUND
                        }

                    }else{

                        $objectsEditorDataSource                = $this->getDataSource($this->getLocal("application.editor.objectsDataSource"));

                        $editDataResult                         = $this->editEditorData($objectsEditorDataSource, $objectId, $toEditData);
                    }

                    if($editDataResult !== false){

                        //TODO: REDIRECT TO LIST

                        header("Location: " . $this->getLocal("application.urlMaps.LIST"));
                        exit();
                    }else{

                        $editorAditionalParams['message'] = "No se pudieron cargar los datos!";
                    }
                }else{

                    $editorAditionalParams['message'] = "Datos Invalidos! ID FIELD not FOUND";
                }
            }else{

                $editorAditionalParams['message'] = "Datos Invalidos!";
            }
        }
        
        $this->generateEditor($editorAditionalParams);
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

    //AJAX REMOTE SERVICES
    public function loadDataService(){

        $this->setDataSources();

        //sleep(3);

        //TODO : AGREGAR CONDICIONENATES PARA LA SELECCION DE METODOS EN EL DATASOURCE CUSTOM

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

                case 'custom':
                    $this->setDataSource($dataSourceName, new DataSource($this->getDI(), new CustomDataSource($this->getDI(), $dataSource["options"])));
                    break;
                default:
                    # code...
                    break;
            }
        }
    }

     /*___   ______   _        ______    _____   _______    ____    _____  
    / ____| |  ____| | |      |  ____|  / ____| |__   __|  / __ \  |  __ \ 
    | (___   | |__    | |      | |__    | |         | |    | |  | | | |__) |
    \___ \  |  __|   | |      |  __|   | |         | |    | |  | | |  _  / 
    ____) | | |____  | |____  | |____  | |____     | |    | |__| | | | \ \ 
    |_____/  |______| |______| |______|  \_____|    |_|     \____/  |_|  \_\                                                                 
    */
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

    //SELECTOR FIELDS
    protected function getSelectorFields($p_dataSource){
        
        //TODO : VER SI HACE FALTA LOGICA DISTINTA SEGUN TIPO DE SELECTOR
        $result                     = array();

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
                        $fieldTemp["urlMap"]            = $this->getLocal("application.urlMaps.EDIT");

                    }else if($definition["isImage"]){

                        $fieldTemp["renderType"]        = "IMAGE";
                        $fieldTemp["imgSrcMap"]         = $this->getLocal("application.urlMaps.IMAGE");;

                    }else{

                        if($definition["type"] == "json"){

                            $fieldTemp["renderType"]            = "JSON";
                        }

                        if($definition["type"] == "tags"){

                            $fieldTemp["renderType"]            = "TAGS";
                        }

                        if($definition['type'] == "objectr" || $definition['type'] == "objectsr"){

                            //TODO: Configurar si el tag se vera como link o enlace a detalles o imagenes etc!
                            $fieldTemp["renderType"]            = "TAGS";

                            if(property_exists($definition['typeOptions'], 'model')){

                                if($definition['typeOptions']->model == "image"){

                                    $fieldTemp["renderType"]    = "IMAGETAGS";
                                }
                            }
                        }
                    }

                    if(!$definition['isRelation']){

                        $fieldTemp["label"]                 = $definition["uiOptions"]->label;
                        $fieldTemp["icon"]                  = $definition["uiOptions"]->icon;
                    }else{

                        $fieldTemp["label"]                 = "Relation x";
                        $fieldTemp["icon"]                  = "caret right";
                    }
                    
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
            $fieldTemp["label"]         = $link["label"];

            if($link['type'] == 'link'){
                
                $fieldTemp["style"]         = $link["style"];
                $fieldTemp["renderType"]    = "LINKBUTTON";
                $fieldTemp["urlMap"]        = $link["urlMap"];
            }else if($link['type'] == 'actions'){
                
                $fieldTemp["style"]         = $link["style"];
                $fieldTemp["renderType"]    = "LINKBUTTON";
                $fieldTemp["actions"]       = array();
                
                $linkActionsIndex          = 0;
                foreach($link["actions"] as $action){

                    $fieldTemp["actions"]["action" . $linkActionsIndex] = $action;
                    $linkActionsIndex++;
                }

            }else if($link['type'] == 'selector'){

                $fieldTemp["renderType"]    = "SELECTOR";
                $fieldTemp["options"]       = array();

                $otionsIndex                = 0;
                foreach($link["options"] as $option){

                    if($option['type'] == "link"){

                        $fieldTemp["options"]["options" . $otionsIndex] = $option;
                    }else if($option['type'] == 'actions'){

                        $optionTemp             = array();
                        $optionTemp['type']     = $option['type'];
                        $optionTemp['label']    = $option['label'];

                        $optionTemp['actions']  = array();
                        $optionActionIndex      = 0;
                        foreach($option['actions'] as $action){

                            $optionTemp['actions']['option' . $optionActionIndex] = $action;
                            $optionActionIndex++;
                        }

                        $fieldTemp["options"]["options" . $otionsIndex] = $optionTemp;
                    }

                    $otionsIndex++;
                }
            }

            $fieldTemp['condition']             = null;
            if(isset($link['condition'])){

                if($link['condition']["type"] == "jsexpression"){

                    $fieldTemp['condition']     = $link['condition']['expression'];
                }
            }

            //TODO CONDITIONS BACKEND

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

                $fieldTemp["actions"]       = array();
                
                $actionActionsIndex         = 0;
                foreach($action["actions"] as $action){

                    $fieldTemp["actions"]["action" . $actionActionsIndex] = $action;
                    $actionActionsIndex++;
                }

            }

            $fieldTemp['condition']             = null;
            if(isset($action['condition'])){

                if($action['condition']["type"] == "jsexpression"){

                    $fieldTemp['condition']     = $action['condition']['expression'];
                }
            }

            //TODO CONDITIONS BACKEND

            $result["action" . $actionIndex] = $fieldTemp;
            $actionIndex++;
        }
        
        return $result;
    }

    //SELECTOR DATOS
    protected function getSelectorData($p_dataSource){
        
        $dataSourceQuery            = array();
        $dataSourceQuery['page']    = ($this->hasUrlParam("page")) ? $this->getUrlParam("page") : 1;
        $dataSourceQuery['rows']    = ($this->hasUrlParam("rows")) ? $this->getUrlParam("rows") : 10;
        
        $data                       = $p_dataSource->getData($dataSourceQuery);
        
        $definitions                = $p_dataSource->getDataDefinitions();
        
        foreach($data['objects'] as $row=>$object){
            
            foreach($object as $field=>$value){
                
                if(isset($definitions[$field])){
                    
                    if($definitions[$field]['type'] == "objectr"){

                        if($value != "" && property_exists($definitions[$field]['typeOptions'], 'model')){

                            $valueIdNames                   = $this->getModelDataIdNames($definitions[$field]['typeOptions']->model, $value);

                            $data['objects'][$row][$field]  = $valueIdNames;
                        }
                    }
                    
                    if($definitions[$field]['type'] == "objectsr"){

                        if(is_array($value) && property_exists($definitions[$field]['typeOptions'], 'model')){

                            $valueIdNamesTemp               = array();
                            
                            foreach($value as $objectId){
                                
                                
                                $valueIdNames               = $this->getModelDataIdNames($definitions[$field]['typeOptions']->model, $objectId);

                                $valueIdNamesTemp[$objectId]= $valueIdNames;
                            }

                            $data['objects'][$row][$field]  = $valueIdNamesTemp;
                        }
                    }
                    
                }
            }
        }

        return                      $data;
    }

    /*______   _____    _____   _______    ____    _____  
    |  ____| |  __ \  |_   _| |__   __|  / __ \  |  __ \ 
    | |__    | |  | |   | |      | |    | |  | | | |__) |
    |  __|   | |  | |   | |      | |    | |  | | |  _  / 
    | |____  | |__| |  _| |_     | |    | |__| | | | \ \ 
    |______| |_____/  |_____|    |_|     \____/  |_|  \_\
    */                                                     
                                                         

    //EDITOR
    protected function generateEditor($p_aditionalParams = array()){
        
        //PONER LOGICA SEGUN TIPO DE EDITOR

        //FORM EDITOR
        $objectsEditorDataSource                = $this->getDataSource($this->getLocal("application.editor.objectsDataSource"));

        $objectsEditorParams                    = array();
        
        $objectsEditorParams['datasource']      = $this->getLocal("application.editor.objectsDataSource");
        $objectsEditorParams['token']           = $this->getEditorToken($objectsEditorParams['datasource']);
        $objectsEditorParams['fields']          = $this->getEditorFields($objectsEditorDataSource);
        $objectsEditorParams['fieldsGroups']    = $this->getEditorFieldsGroups($this->getDataSource($this->getLocal("application.editor.fgroupsDataSource")));
        $objectsEditorParams['data']            = $this->getEditorData($objectsEditorDataSource);
        
        $objectsEditor                          = new Form($this->getDI());
        $this->placeComponent("main", $objectsEditor, array_merge($objectsEditorParams, $p_aditionalParams));
        
    }

    //EDITOR FIELDS
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
            $fieldTemp["help"]          = $definition["uiOptions"]->help;
            $fieldTemp["info"]          = $definition["uiOptions"]->info;
            $fieldTemp["hidden"]        = $definition["uiOptions"]->hidden;
            $fieldTemp["required"]      = $definition["uiOptions"]->required;
            $fieldTemp["readOnly"]      = $definition["uiOptions"]->readOnly;
            $fieldTemp["formplace"]     = (property_exists($definition["uiOptions"], "formPlace")) ? $definition["uiOptions"]->formPlace : "main" ;
            $fieldTemp["editAs"]        = (property_exists($definition["uiOptions"], "editAs")) ? $definition["uiOptions"]->editAs : "FIELD" ;
            $fieldTemp["component"]     = (property_exists($definition["uiOptions"], "component")) ? $definition["uiOptions"]->component : null ;

            $fieldTemp["options"]       = array();
            $fieldTemp["options"]["validation"]  = $definition["validationOptions"];
            $fieldTemp["options"]["file"]        = $definition["attachFileOptions"];

            foreach($definition["typeOptions"] as $option=>$value){

                $fieldTemp["options"][$option]   = $value; 
            }

            if(!$definition["isRelation"]){

                $fieldTemp["label"]         = $definition["uiOptions"]->label;
                $fieldTemp["icon"]          = $definition["uiOptions"]->icon;

                if($fieldTemp['type'] == "objectr" || $fieldTemp['type'] == "objectsr"){

                    if(isset($fieldTemp['options']['model'])){

                        
                        $modelDataIdNamesParams                     = array();
                        if(isset($fieldTemp['options']['hardfilters'])){

                            $modelDataIdNamesParams['hardfilters']  = $fieldTemp['options']['hardfilters'];
                        }

                        $modelDataIdNames                       = $this->getModelDataIdNames($fieldTemp['options']['model'], $modelDataIdNamesParams);
                        
                        $fieldTempData                          = array();
    
                        foreach($modelDataIdNames['objects'] as $object){
    
                            $fieldTempData[]                    = array('label' => $object['name'], 'value' => $object['id'], 'image' => $object['image'], 'icon' => $object['icon']);
                        }
    
                        $fieldTemp["options"]["data"]           = $fieldTempData;

                        $fieldTemp["options"]["multiple"]           = false;
                        if($fieldTemp['type'] == "objectsr"){

                            $fieldTemp["options"]["multiple"]       = true;
                        }
                    }
                }

            }else{

                $rightModelData                     = $this->getModelData($definition["rightModId"]);

                $fieldTemp["icon"]                  = $rightModelData["uiOptions"]->icon;

                if($definition['cardinality'] == "1:n"){

                    $fieldTemp["label"]                 = $rightModelData["uiOptions"]->pluralName;
                    $fieldTemp["options"]["multiple"]   = true;
                }else {

                    $fieldTemp["label"]                 = $rightModelData["uiOptions"]->name;
                    $fieldTemp["options"]["multiple"]   = false;
                }

                $modelDataIdNames                       = $this->getModelDataIdNames($definition["rightModId"]);
                        
                $fieldTempData                          = array();

                foreach($modelDataIdNames['objects'] as $object){

                    $fieldTempData[]                    = array('label' => $object['name'], 'value' => $object['id'], 'image' => $object['image'], 'icon' => $object['icon']);
                }

                $fieldTemp["options"]["data"]           = $fieldTempData;
            }

            if(!isset($result[$fieldTemp["formplace"]][$fieldTemp['group']])){

                $result[$fieldTemp["formplace"]][$fieldTemp['group']] = array();
            }

            $result[$fieldTemp["formplace"]][$fieldTemp['group']][$definition["id"]] = $fieldTemp;
            
        }
        
        return $result;
    }

    //MODEL ID AND NAMES FIELDS
    protected function getModelDataIdNames($p_model, $p_params = array()){
        $result                         = false;

        $dataSourceOptions              = array();
        $dataSourceOptions['model']     = $p_model;

        if(\is_array($p_params)){

            //TODO : Si hace falta recivir por parametro adicionales para el query
            if(isset($p_params['hardfilters'])){

                $dataSourceOptions["hardfilters"] = (array)$p_params['hardfilters'];
            }

            $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

            $query                          = array();
            $query['page']                  = 1;
            $query['rows']                  = 1000;

            $result                         = $dataSource->getDataIdNames($query);
        }else{
            
            
            $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

            $result                         = $dataSource->getDataIdNames($p_params);
        }
        
        return                              $result;
    }

    //MODEL DATA
    protected function getModelData($p_model){

        $dataSourceOptions              = array();
        $dataSourceOptions['model']     = $p_model;

        $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        return                          $dataSource->getModelData();
    }

    //EDITORS DATA
    protected function getEditorData($p_dataSource){
        $result             = array();

        $objectId           = false;
        $paramNum           = ($this->getLocal("application.serviceActions.paramNum")) + 1;
        
        if($this->hasUrlParam($paramNum)){

            if(!strstr(":", $this->getUrlParam($paramNum))){

                $objectId   = $this->getUrlParam($paramNum);
            }
        }
        
        if($this->hasLocal("application.editor.objectGetMethod")){

            $methodName = $this->getLocal("application.editor.objectGetMethod");

            if(method_exists($this, $methodName)){

                $result     = $this->$methodName($objectId);
                
            }else{

                //TODO: METHOS NOT FOUND
            }
        }else{

            if($objectId !== false){
                
                $result     = $p_dataSource->getData($objectId);

            }
        }
        
        return $result;
    }

    //EDITOR FIELDS GROUPS
    protected function getEditorFieldsGroups($p_dataSource){
        
        $result                         = array();

        $dataSourceQuery                = array();
        
        $queryResult                    = $p_dataSource->getData($dataSourceQuery);

        foreach ($queryResult['objects'] as $object) {
            
            $result[$object['flgId']]   = $object['flgName'];
        }

        return $result;
    }
    
    //VALIDATE EDITOR TOKEN
    protected function getEditorToken($p_dataSourceName){
        $result                         = false;

        $tokenData                      = array();
        $tokenData['sid']               = $this->getSessionId();
        $tokenData['user_login']        = $this->getSession("user_login");
        $tokenData['user_role']         = $this->getSession("user_role");
        $tokenData['dataSource']        = $p_dataSourceName;
        
        $secret                         = $this->getDI()->get('config')->crypt->privatekey;

        //TODO: Hacer que live sea configurado desde el service json

        return \Nubesys\Core\Utils\Utils::jwtGenerate($secret, $tokenData, 50);
    }

    //EDITOR TOKEN
    protected function validateEditorToken($p_token, $p_dataSourceName){
        
        $result                         = false;

        $tokenData                      = array();
        $tokenData['sid']               = $this->getSessionId();
        $tokenData['user_login']        = $this->getSession("user_login");
        $tokenData['user_role']         = $this->getSession("user_role");
        $tokenData['dataSource']        = $p_dataSourceName;

        $secret                         = $this->getDI()->get('config')->crypt->privatekey;
        
        return \Nubesys\Core\Utils\Utils::jwtValidate($secret, $tokenData, $p_token);
    }

    //VALIDATE AND ZANITIZE EDITOR DATA
    protected function getEditorSaveData($p_data){

        $result = false;

        //DATASOURCE VALIDATION
        if($p_data["datasource"] == $this->getLocal("application.editor.objectsDataSource")){

            //TOKEN PARAM
            if(isset($p_data["token"])){
                
                //VALIDATE TOKEN
                if($this->validateEditorToken($p_data["token"], $this->getLocal("application.editor.objectsDataSource"))){
                    
                    //TODO: VALIDATE DATA
                    //TODO: SANITIZE DATA
                    $result             = array();

                    foreach($p_data as $key=>$value){

                        if($key != 'token' && $key != 'datasource'){

                            $result[$key] = $value;
                        }
                    }
                }
            }
        }

        return $result;
    }

    //SAVE EDITOR DATA
    protected function editEditorData($p_dataSource, $p_id, $p_data){

        return $p_dataSource->editData($p_id, $p_data);
    }

    //EDIT EDITOR DATA
    protected function addEditorData($p_dataSource, $p_data){

        return $p_dataSource->addData($p_data);
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