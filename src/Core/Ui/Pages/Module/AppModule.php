<?php

namespace Nubesys\Core\Ui\Pages\Module;

use Nubesys\Vue\Services\VueUiService;
use Nubesys\Data\DataSource\ModuleDataSource;

class AppModule extends VueUiService {

    //MAIN ACTION
    public function mainAction(){
        
        $this->setTitle($this->getLocal("title"));
        
        $this->callServiceAction();
    }

    //CALL SERVICE ACTION
    protected function callServiceAction(){

        $action             = $this->getLocal("actionDefault");
        $paramNum           = $this->getLocal("actionParamNum");
        
        if($this->hasUrlParam($paramNum)){

            if(!strstr(":", $this->getUrlParam($paramNum))){

                $action     = $this->getUrlParam($paramNum);
            }
        }
        
        //TODO BEFORE ACTION OVERWRITE METHOD $this->beforeAction($action) --> <action>BeforeAction()

        $this->placeActionComponents($action);

        //TODO AFFTER ACTION OVERWRITE METHOD $this->affterAction($action) --> <action>AffterAction()

        /*
        $methodName         = $action . "Action";
        
        if(method_exists($this, $methodName)){

            $this->action   = $action;
            $this->$methodName();
        }else{

            exit("Method " . $methodName . " Not Found");
        }
        */
    }

    protected function accessControl(){
        
        if($this->getLocal("accessControl")){

            $loginurl = $this->getDI()->get('config')->main->url->base . "login";
            
            if($this->hasSession("user_loged")){

                if(!$this->getSession("user_loged")){

                    header("Location: " . $loginurl);
                    exit();
                }else{
                    
                    $this->setGlobal("user_id", $this->getSession("user_data")['id']);
                }
            }else{
                
                header("Location: " . $loginurl);
                exit();
            }
        }
    }

    private function placeActionComponents($p_action){

        $layouts                                        = $this->getActionComponents($p_action);

        $this->setActionPageTitle($p_action);
        
        foreach($layouts as $layout=>$components){

            foreach($components as $componentIndex=>$component){
                
                if(class_exists($component["classPath"])){
                
                    $componentClassPath                 = $component["classPath"];
                    
                    $componentInstance                  = new $componentClassPath($this->getDI());
    
                    $componentParams                    = array();
                    $componentParams["moduleAction"]    = $p_action;
                    $componentParams["actionLayout"]    = $layout;
                    $componentParams["componentIndex"]  = $componentIndex;
                    $componentParams["referenceName"]   = $component["referenceName"];
                    $componentParams["dataService"]     = $component["dataService"];

                    if(isset($component["tabs"])){
                        
                        $componentParams["tabs"]        = $component["tabs"];
                    }

                    if(isset($component["sliders"])){

                        $componentParams["sliders"]     = $component["sliders"];
                    }

                    if(isset($component["customFields"])){

                        $componentParams["customFields"]= $component["customFields"];
                    }
                    
                    $this->appendComponent($layout, $componentInstance, $componentParams);
                }
            }   
        }
        
    }

    private function setActionPageTitle($p_action){
        
        $scopePath          = "actions." . $p_action . ".title";
        
        if($this->hasLocal($scopePath)){
            
            $this->setTitle($this->getLocal($scopePath));

            $this->logInfo("PAGE-VIEW", "NOSVAMOOS|EVENT", $this->getLocal($scopePath));
        }

        $sesid                                  = $this->getSessionId();

        $cacheKey                               = 'unique_visit_' . $sesid;
        $cacheLifetime                          = 180000;
        $cacheType                              = 'redis';

        if(!$this->hasCache($cacheKey)){

            $eventParams                                            = array();
            $eventParams['sesid']                                   = $sesid;

            $this->logInfo("UNIQUE-VISIT", "NOSVAMOOS|EVENT", $eventParams);

            $this->setCache($cacheKey, $eventParams['sesid'], $cacheLifetime);
        }
    }

    private function getActionComponents($p_action){
        
        $result             = array();
        $scopePath          = "actions." . $p_action . ".layout";
        
        if($this->hasLocal($scopePath)){
            
            foreach($this->getLocal($scopePath) as $layout=>$layoutData){
                
                if(isset($layoutData['components'])){

                    $result[$layout] = $layoutData['components'];

                    //TODO ACTION/LAYOUT COMPONENTS PROSPROCESSOR METHOD $this->actionLayoutComponentsPostprocessor($action, $layout, $result[$layout]) --> <Action><Layout>ComponentsPostProcessor($p_data)
                }

                if(isset($layoutData['tabs'])){

                    $result[$layout] = $layoutData['tabs'];

                    //TODO ACTION/LAYOUT COMPONENTS PROSPROCESSOR METHOD $this->actionLayoutComponentsPostprocessor($action, $layout, $result[$layout]) --> <Action><Layout>ComponentsPostProcessor($p_data)
                }

                if(isset($layoutData['sliders'])){

                    $result[$layout] = $layoutData['sliders'];

                    //TODO ACTION/LAYOUT COMPONENTS PROSPROCESSOR METHOD $this->actionLayoutComponentsPostprocessor($action, $layout, $result[$layout]) --> <Action><Layout>ComponentsPostProcessor($p_data)
                }
            }
        }else{
            
            exit("Module -> " . $scopePath . " Not Found");
        }
        
        return $result;
    }

    //MODULE COMPONETS ACTIONS
    protected function getScopePath($p_params){
        $result = false;

        if(isset($p_params["moduleAction"]) && isset($p_params["actionLayout"]) && isset($p_params["componentIndex"])){

            $scopePath                  = "actions." . $p_params["moduleAction"] . ".layout." . $p_params["actionLayout"] . ".components." . $p_params["componentIndex"];

            if(isset($p_params['tabIndex'])){

                $scopePath              .= ".tabs." . $p_params['tabIndex'];
            }

            if(isset($p_params['sliderIndex'])){

                $scopePath              .= ".sliders." . $p_params['sliderIndex'];
            }

            if($this->hasLocal($scopePath)){

                $result                 = $scopePath;
            }
        }
        
        return $result;
    }

    //TOPBAR
    public function moduleTopBarService(){
        //\sleep(1);
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            $scopePath                      = $this->getScopePath($params);

            if($scopePath !== false){

                $result                     = array();

                //TITLE
                //TODO: ver para que tome por defecto el titulo del mainmodel si no existe
                $result["title"]        = ($this->getLocal($scopePath))['title'];

                //ACTIONS
                $result["actions"]      = ($this->getLocal($scopePath))['actions'];

                //TODO: ADITIONAL ACTIONS EN SUB CLASE

                //REFERENCE NAME
                $result["referenceName"]    = ($this->getLocal($scopePath))['referenceName'];

                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Invalid ScopePath: " . $scopePath);
            }

        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //SIDEMENU
    public function moduleSideMenuService(){
        //\sleep(1);
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            $scopePath                      = $this->getScopePath($params);

            if($scopePath !== false){

                $result                     = array();

                //USER
                $user                       = false;
                $items                      = array();

                if($this->hasSession("user_loged") && $this->getSession("user_loged") == true){

                    $userData               = $this->getSession("user_data");

                    $user                   = array();
                    $user['label']          = $userData['login'];
                    $user['url']            = $this->getDI()->get('config')->main->url->base . "profile/";
                    $user['avatar']         = $this->getDI()->get('config')->main->url->base . "avatar/sq100/" . $userData['avatar'] . ".jpg";


                    $items                  = array();

                    if(isset($userData['role']['menus'])){

                        foreach($userData['role']['menus'] as $menu){

                            if(isset($menu['items'])){

                                foreach($menu['items'] as $item){

                                    if(!isset($items[$item['id']])){

                                        $itemTmp                = array();
                                        $itemTmp['label']       = $item['label'];
                                        $itemTmp['icon']        = $item['icon'];
                                        $itemTmp['url']         = $this->getDI()->get('config')->main->url->base . $item['path'];
                                        $itemTmp['order']       = $item['order'];

                                        $items[$item['id']]     = $itemTmp;
                                    }
                                }
                            }
                        }
                    }

                    /*
                    MULTI ROLE
                    $userData['roles'][]    = $userData['role'];
                    
                    foreach($userData['roles'] as $role){
                        
                        if(isset($role['menus'])){

                            foreach($role['menus'] as $menu){

                                if(isset($menu['items'])){

                                    foreach($menu['items'] as $item){

                                        if(!isset($items[$item['id']])){

                                            $itemTmp                = array();
                                            $itemTmp['label']       = $item['label'];
                                            $itemTmp['icon']        = $item['icon'];
                                            $itemTmp['url']         = $this->getDI()->get('config')->main->url->base . $item['path'];
                                            $itemTmp['order']       = $item['order'];

                                            $items[$item['id']]     = $itemTmp;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    */
                }

                $result["user"]         = $user;
                $result["items"]        = $items;

                //REFERENCE NAME
                $result["referenceName"]    = ($this->getLocal($scopePath))['referenceName'];

                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Invalid ScopePath: " . $scopePath);
            }

        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //BOARD
    public function moduleBoardService(){
        //\sleep(3);

        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            $scopePath                      = $this->getScopePath($params);

            if($scopePath !== false){

                if(isset($params["model"])){
                            
                    $result                     = array();
                    
                    //OBJECTS
                    $query                      = array();

                    //PAGE
                    if(isset($params["page"])){

                        $query["page"]          = $params["page"];
                    }
                    
                    //ROWS
                    if(isset($params["rows"])){

                        $query["rows"]          = $params["rows"];
                    }

                    //FILTERS
                    if(isset($params["filters"])){

                        $query["filters"]               = $params["filters"];
                    }

                    //KEYWORD
                    if(isset($params["keyword"])){

                        $query["keyword"]               = $params["keyword"];
                    }

                    //ORDERS
                    if(isset($params["orders"])){

                        $query["orders"]                = $params["orders"];
                    }

                    //RANGES
                    if(isset($params["ranges"])){

                        $query["ranges"]                = $params["ranges"];
                    }

                    //AGGREGATIONS
                    if(isset($params["aggregations"])){

                        $query["aggregations"]          = $params["aggregations"];
                    }

                    $result                             = $this->getModelObjects($params['model'], $query);

                    //FILTERS
                    $result["filters"]                  = array();

                    //KEYWORD
                    $result["keyword"]                              = "*";

                    if(isset($params["keyword"])){

                        $result["keyword"]                          = $params["keyword"];
                    }

                    //ROW LINKS
                    $result["rowLinks"]              = array();
                    if(isset(($this->getLocal($scopePath))['rowLinks'])){

                        $result["rowLinks"]            = ($this->getLocal($scopePath))['rowLinks'];
                    }

                    //ROW ACTIONS
                    $result["rowActions"]               = array();
                    if(isset(($this->getLocal($scopePath))['rowActions'])){
                        
                        $result["rowActions"]           = ($this->getLocal($scopePath))['rowActions'];
                    }

                    //COMPONENT ACTIONS
                    $result["actions"]                  = array();
                    if(isset(($this->getLocal($scopePath))['actions'])){
                        
                        $result["actions"]              = ($this->getLocal($scopePath))['actions'];
                    }

                    //URL MAPS & LINK ACTION
                    $result["urlMaps"]          = $this->getLocal("urlMaps");

                    $result["linkAction"]       = ($this->getLocal($scopePath))['linkAction'];

                    //OPTIONS
                    $result["options"]                  = array();
                    if(isset(($this->getLocal($scopePath))['options'])){

                        $result['options']              = ($this->getLocal($scopePath))['options'];
                    }

                    //REFERENCE NAME
                    $result["referenceName"]            = ($this->getLocal($scopePath))['referenceName'];
                    
                    $this->setServiceSuccess($result);
                }else{

                    $this->setServiceError("Model Param Not Found");
                }
            }else{

                $this->setServiceError("Invalid ScopePath: " . $scopePath);
            }
        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //SELECTOR
    public function moduleSelectorService(){
        //\sleep(3);
        
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            $scopePath                      = $this->getScopePath($params);

            if($scopePath !== false){

                if(isset($params["model"])){
                    
                    $result                             = array();
                    
                    //OBJECTS
                    $query                              = array();

                    //PAGE
                    if(isset($params["page"])){

                        $query["page"]                  = $params["page"];
                    }
                    
                    //ROWS
                    if(isset($params["rows"])){

                        $query["rows"]                  = $params["rows"];
                    }

                    //FILTERS
                    if(isset($params["filters"])){

                        $query["filters"]               = $params["filters"];
                    }

                    //KEYWORD
                    if(isset($params["keyword"])){

                        $query["keyword"]               = $params["keyword"];
                    }

                    //ORDERS
                    if(isset($params["orders"])){

                        $query["orders"]                = $params["orders"];
                    }

                    //RANGES
                    if(isset($params["ranges"])){

                        $query["ranges"]                = $params["ranges"];
                    }

                    //AGGREGATIONS
                    if(isset($params["aggregations"])){

                        $query["aggregations"]          = $params["aggregations"];
                    }

                    $result                             = $this->getModelObjects($params['model'], $query);

                    //NOT RENDERED FIELDS
                    if(isset(($this->getLocal($scopePath))['notRenderedFields'])){

                        $notRenderedFields             = ($this->getLocal($scopePath))['notRenderedFields'];

                        foreach($result['fields'] as $field=>$definition){

                            if(in_array($field, $notRenderedFields)){

                                $definition['uiOptions']->hidden = true;
                            }
                        }
                    }

                    //RENDERED FIELDS
                    if(isset(($this->getLocal($scopePath))['renderedFields'])){

                        $renderedFields             = ($this->getLocal($scopePath))['renderedFields'];

                        foreach($result['fields'] as $field=>$definition){

                            if(in_array($field, $renderedFields)){

                                $definition['uiOptions']->hidden = false;
                            }else{

                                $definition['uiOptions']->hidden = true;
                            }
                        }
                    }

                    //FILTERS
                    $result["filters"]                                  = array();
                    //MODEL FILTERS
                    foreach($result['fields'] as $field=>$definition){

                        if($definition['uiOptions']->filterable && !$definition['uiOptions']->hidden){

                            if(property_exists($definition['typeOptions'],"model")){

                                $filterTmp                              = array();
                                $filterTmp['type']                      = (property_exists($definition['typeOptions'],"filterType")) ? $definition['typeOptions']->filterType : "dropdown";
                                $filterTmp['multiple']                  = (property_exists($definition['typeOptions'],"filterMultiple")) ? $definition['typeOptions']->filterMultiple : true;
                                $filterTmp['label']                     = $definition['uiOptions']->label;
                                $filterTmp['model']                     = $definition['typeOptions']->model;

                                $result["filters"][$definition['id']]   = $filterTmp;
                            }
                        }
                    }
                    
                    //HARD FILTERS
                    if(isset(($this->getLocal($scopePath))['hardFilters'])){

                        foreach(($this->getLocal($scopePath))['hardFilters'] as $filterIndex=>$filterData){

                            $result["filters"][$filterIndex]        = $filterData;
                        }
                    }

                    if(isset($params['filters'])){

                        foreach($params['filters'] as $filter=>$value){

                            if($result['filters'][$filter]){

                                $result['filters'][$filter]['value'] = $value;
                            }
                        }
                    }

                    //KEYWORD
                    $result["keyword"]                              = "*";

                    if(isset($params["keyword"])){

                        $result["keyword"]                          = $params["keyword"];
                    }

                    //ROW LINKS
                    $result["rowLinks"]              = array();
                    if(isset(($this->getLocal($scopePath))['rowLinks'])){

                        $result["rowLinks"]            = ($this->getLocal($scopePath))['rowLinks'];
                    }

                    //ROW ACTIONS
                    $result["rowActions"]               = array();
                    if(isset(($this->getLocal($scopePath))['rowActions'])){
                        
                        $result["rowActions"]           = ($this->getLocal($scopePath))['rowActions'];
                    }

                    //COMPONENT ACTIONS
                    $result["actions"]                  = array();
                    if(isset(($this->getLocal($scopePath))['actions'])){
                        
                        $result["actions"]              = ($this->getLocal($scopePath))['actions'];
                    }

                    //URL MAPS & LINK ACTION
                    $result["urlMaps"]                  = $this->getLocal("urlMaps");
                    $result["linkAction"]               = ($this->getLocal($scopePath))['linkAction'];

                    //OPTIONS
                    $result["options"]                  = array();
                    if(isset(($this->getLocal($scopePath))['options'])){

                        $result['options']              = ($this->getLocal($scopePath))['options'];
                    }

                    //REFERENCE NAME
                    $result["referenceName"]            = ($this->getLocal($scopePath))['referenceName'];
                    
                    $this->setServiceSuccess($result);
                }else{

                    $this->setServiceError("Model Param Not Found");
                }
            }else{

                $this->setServiceError("Invalid ScopePath: " . $scopePath);
            }
        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //OBJECT LIST
    public function objectsListService(){
        //\sleep(3);
        
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            if(isset($params["model"])){
                        
                $result                     = array();
                $result['objects']          = array();
                
                //OBJECTS
                $query                      = array();

                //PAGE
                if(isset($params["page"])){

                    $query["page"]          = $params["page"];
                }
                
                //ROWS
                if(isset($params["rows"])){

                    $query["rows"]          = $params["rows"];
                }

                //FILTERS
                if(isset($params["filters"])){

                    $query["filters"]               = $params["filters"];
                }

                //ORDERS
                if(isset($params["orders"])){

                    $query["orders"]                = $params["orders"];
                }

                //RANGES
                if(isset($params["ranges"])){

                    $query["ranges"]                = $params["ranges"];
                }

                //AGGREGATIONS
                if(isset($params["aggregations"])){

                    $query["aggregations"]          = $params["aggregations"];
                }

                $queryResult                = $this->getModelObjects($params['model'], $query);

                if(isset($queryResult['objects'])){

                    foreach($queryResult['objects'] as $objectData){

                        $objectDataTmp                  = array();

                        $objectNameField                = $this->getModelObjectNameField($params['model']);
                        $objectImageField               = $this->getModelObjectImageField($params['model']);
                        $objectIconField                = $this->getModelObjectIconField($params['model']);

                        $objectDataTmp['id']            = $objectData["_id"];

                        if(isset($objectData[$objectNameField])){

                            $objectDataTmp['name']      = $objectData[$objectNameField];
                        }
    
                        if(isset($objectData[$objectImageField])){
    
                            $objectDataTmp['image']     = $objectData[$objectImageField];
                        }
    
                        if(isset($objectData[$objectIconField])){
    
                            $objectDataTmp['icon']      = $objectData[$objectIconField];
                        }

                        $result['objects'][]            = $objectDataTmp; 
                    }
                }
                
                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Model Param Not Found");
            }
            
        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //EDITOR
    public function moduleEditorService(){
        //\sleep(3);
        
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            $scopePath                      = $this->getScopePath($params);
            
            if($scopePath !== false){

                if(isset($params["model"])){

                    $result                     = array();

                    $result["dataActions"]      = array();

                    //TODO CUSTOM FIELDS

                    //PRE DATA MANIPULATIONS
                    if(isset($params["data"])){

                        //NOT RENDERED FIELDS
                        $dataTmp = array();
                        if(isset(($this->getLocal($scopePath))['notRenderedFields'])){

                            foreach($params["data"] as $field=>$value){

                                if(!in_array($field, ($this->getLocal($scopePath))['notRenderedFields'])){

                                    $dataTmp[$field] = $value;
                                }
                            }

                            $params["data"] = $dataTmp;
                        }
                        
                        //HARD FIELDS VALUE
                        if(isset(($this->getLocal($scopePath))['hardDefaultData'])){

                            $hardDefaultData = ($this->getLocal($scopePath))['hardDefaultData'];
                            
                            foreach($hardDefaultData as $field=>$value){

                                $params["data"][$field] = $value;
                            }
                        }
                    }
                    
                    if(isset($params["id"])){
                        //EDIT
                        if(isset($params["data"])){
                            
                            //EDIT DATA
                            $editResult                 = $this->editModelObject($params['model'], $params["id"], $params["data"]);
                            
                            //TODO CONTROL DE ERROR
                            
                            //DATA ACTIONS
                            if(isset(($this->getLocal($scopePath))['onEditActions'])){

                                $result["dataActions"]  = ($this->getLocal($scopePath))['onEditActions'];
                            }

                        }else{

                            //GET DATA TO EDIT 
                            $result                     = $this->getModelObject($params['model'], $params["id"]);
                        }
                    }else{

                        //ADD
                        if(isset($params["data"])){
                            //ADD DATA
                            $addResult                  = $this->addModelObject($params['model'], $params["data"]);
                            
                            //TODO CONTROL DE ERROR

                            //DATA ACTIONS
                            if(isset(($this->getLocal($scopePath))['onAddActions'])){

                                $result["dataActions"]  = ($this->getLocal($scopePath))['onAddActions'];
                            }

                        }else{

                            //GET DATA TO ADD
                            $result["model"]            = $this->getModelData($params['model']);
                            $result["fields"]           = $this->getModelDefinitions($params['model'], $result['model']);
                            $result['fieldsGroups']     = $this->getModelDefinitionsGroups($params['model'], $result['model']);
                            $result['totals']           = 1;
                            $result['pages']            = 1;
                            $result['objects']          = array(new \stdClass());
                            $result['facets']           = array();
                        }
                    }

                    $result["urlMaps"]                  = $this->getLocal("urlMaps");

                    //NOT RENDERED FIELDS
                    if(isset(($this->getLocal($scopePath))['notRenderedFields']) && isset($result["fields"])){

                        $notRenderedFields             = ($this->getLocal($scopePath))['notRenderedFields'];
                        
                        foreach($result['fields'] as $field=>$definition){
                            
                            if(in_array($field, $notRenderedFields)){
                                
                                $definition['uiOptions']->hidden = true;
                            }
                        }
                    }
                    
                    //RENDERED FIELDS
                    if(isset(($this->getLocal($scopePath))['renderedFields'])){

                        $renderedFields             = ($this->getLocal($scopePath))['renderedFields'];

                        foreach($result['fields'] as $field=>$definition){

                            if(in_array($field, $renderedFields)){

                                $definition['uiOptions']->hidden = false;
                            }else{

                                $definition['uiOptions']->hidden = true;
                            }
                        }
                    }

                    //ACTIONS
                    $result["actions"]          = array();
                    if(isset(($this->getLocal($scopePath))['actions'])){

                        $result['actions']      = ($this->getLocal($scopePath))['actions'];
                    }

                    //OPTIONS
                    $result["options"]          = array();
                    if(isset(($this->getLocal($scopePath))['options'])){

                        $result['options']      = ($this->getLocal($scopePath))['options'];
                    }

                    //REFERENCE NAME
                    $result["referenceName"]    = ($this->getLocal($scopePath))['referenceName'];

                    $this->setServiceSuccess($result);
                }else{

                    $this->setServiceError("Model Param Not Found");
                }
            }else{

                $this->setServiceError("Invalid ScopePath: " . $scopePath);
            }
        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //MANAGER
    public function moduleManagerService(){
        
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();
            
            $scopePath                      = $this->getScopePath($params);

            if($scopePath !== false){

                $result                     = array();

                if(isset($params["model"])){

                    if(isset($params["id"])){

                        $result                 = $this->getModelObject($params['model'], $params['id']);

                        //NOT RENDERED FIELDS
                        if(isset(($this->getLocal($scopePath))['notRenderedFields'])){

                            $notRenderedFields             = ($this->getLocal($scopePath))['notRenderedFields'];

                            foreach($result['fields'] as $field=>$definition){

                                if(in_array($field, $notRenderedFields)){

                                    $definition['uiOptions']->hidden = true;
                                }
                            }
                        }

                        //RENDERED FIELDS
                        if(isset(($this->getLocal($scopePath))['renderedFields'])){

                            $renderedFields             = ($this->getLocal($scopePath))['renderedFields'];

                            foreach($result['fields'] as $field=>$definition){

                                if(in_array($field, $renderedFields)){

                                    $definition['uiOptions']->hidden = false;
                                }else{

                                    $definition['uiOptions']->hidden = true;
                                }
                            }
                        }
                    }

                    $result["stateActions"]     = $this->getStatesActions($result['objects'][0], $result['model'], $this->getLocal($scopePath));
                    
                }

                //URL MAPS & LINK ACTION
                $result["urlMaps"]          = $this->getLocal("urlMaps");

                $result["renderedFields"]   = ($this->getLocal($scopePath))['renderedFields'];

                if($this->hasLocal($scopePath . ".tabs")){
                            
                    $result["tabs"]         = $this->getModuleManagerTabs($params, ($this->getLocal($scopePath))['tabs']);
                }

                if($this->hasLocal($scopePath . ".sliders")){
                    
                    $result["sliders"]      = $this->getModuleManagerSliders($params, ($this->getLocal($scopePath))['sliders']);
                }

                //REFERENCE NAME
                $result["referenceName"]    = ($this->getLocal($scopePath))['referenceName'];

                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Invalid ScopePath: " . $scopePath);
            }

        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    protected function getModuleManagerTabs($p_params, $p_tabs){

        $result                                     = array();
        $scopeParams                                = array();

        if(isset($p_params["moduleAction"]) && isset($p_params["actionLayout"]) && isset($p_params["componentIndex"])){

            $scopeParams["moduleAction"]            = $p_params["moduleAction"];
            $scopeParams["actionLayout"]            = $p_params["actionLayout"];
            $scopeParams["componentIndex"]          = $p_params["componentIndex"];
        }

        foreach($p_tabs as $tabIndex=>$tabData){

            $scopeParams["tabIndex"]                = $tabIndex;

            if(isset($tabData['dataService'])){

                if(isset($tabData['dataService']['params'])){

                    $tabData['dataService']['params']       = array_merge($tabData['dataService']['params'], $scopeParams);
                }else{

                    $tabData['dataService']                 = array();
                    $tabData['dataService']['params']       = $scopeParams;
                }
            }

            $result[$tabIndex]                      = $tabData;
        }

        return $result;
    }

    protected function getModuleManagerSliders($p_params, $p_sliders){

        $result                                     = array();
        $scopeParams                                = array();

        if(isset($p_params["moduleAction"]) && isset($p_params["actionLayout"]) && isset($p_params["componentIndex"])){

            $scopeParams["moduleAction"]            = $p_params["moduleAction"];
            $scopeParams["actionLayout"]            = $p_params["actionLayout"];
            $scopeParams["componentIndex"]          = $p_params["componentIndex"];
        }

        foreach($p_sliders as $sliderIndex=>$sliderData){

            $scopeParams["sliderIndex"]                     = $sliderIndex;

            if(isset($sliderData['dataService'])){

                if(isset($sliderData['dataService']['params'])){

                    $sliderData['dataService']['params']    = array_merge($sliderData['dataService']['params'], $scopeParams);
                }else{

                    $sliderData['dataService']              = array();
                    $sliderData['dataService']['params']    = $scopeParams;
                }
            }

            $result[$sliderIndex]                           = $sliderData;
        }

        return $result;
    }

    public function toStateService(){
        //\sleep(3);
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();
            
            if(isset($params["model"]) && isset($params["id"]) && isset($params["state"])){
                
                $result['toStateResult']    = $this->setModelObjectState($params["model"], $params["id"], $params["state"], $params["data"]);
                
                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Model Param Not Found");
            }

        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //OBJECT NAME AND IMAGE
    public function objectNameAndImageService(){
        //\sleep(3);
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            if(isset($params["model"]) && isset($params["id"])){
                
                $result                     = array();
                $result['value']            = $params["id"];
                $result['name']             = "";
                $result['image']            = "";
                $result['icon']             = "";

                $objectResult               = $this->getModelObject($params['model'], $params["id"]);

                if(isset($objectResult['objects'][0])){

                    $objectData             = $objectResult['objects'][0];
                }else{

                    $objectData             = false;
                }

                if($objectData){

                    $objectNameField        = $this->getModelObjectNameField($params['model']);
                    $objectImageField       = $this->getModelObjectImageField($params['model']);
                    $objectIconField        = $this->getModelObjectIconField($params['model']);

                    if(isset($objectData[$objectNameField])){

                        $result['name']     = $objectData[$objectNameField];
                    }

                    if(isset($objectData[$objectImageField])){

                        $result['image']     = $objectData[$objectImageField];
                    }

                    if(isset($objectData[$objectIconField])){

                        $result['icon']     = $objectData[$objectIconField];
                    }
                }
                
                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Model Param Not Found");
            }

        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //STATES ACTIONS
    protected function getStatesActions($p_objectData, $p_modelData, $p_localData){
        $result = array();

        if(isset($p_objectData['objState'])){
            
            if(isset($p_modelData['statesOptions']) && is_object($p_modelData['statesOptions'])){

                if(property_exists($p_modelData['statesOptions'], "stateable") && property_exists($p_modelData['statesOptions'], "states")){

                    if($p_modelData['statesOptions']->stateable == true){
                        
                        $states = $p_modelData['statesOptions']->states;
                        
                        foreach($states as $id=>$stateData){
                            
                            if($id == $p_objectData['objState']){

                                if(property_exists($stateData, "toStateActions") && \is_array($stateData->toStateActions)){

                                    foreach($stateData->toStateActions as $stateAction){
                                        
                                        $conditionPass = true;

                                        if(property_exists($stateAction, "conditions")){

                                            $conditionPass = $this->checkConditions($stateAction->conditions, $p_localData);
                                        }
                                        
                                        if($conditionPass){
                                            
                                            //SE BIFURCA SEGUN TIPO DE ACCION
                                            if($stateAction->type == "toState"){

                                                $toState                        = $stateAction->toState;

                                                $stateActionTmp                 = $stateAction;
                                                $stateActionTmp->style          = ($states->$toState)->style;

                                                if(property_exists($stateActionTmp, "toStateInputData")){

                                                    $newToStateInputData        = new \stdClass();

                                                    foreach($stateActionTmp->toStateInputData as $fieldName=>$fieldData){

                                                        if(property_exists($fieldData, "data_field")){

                                                            $newToStateInputData->$fieldName    = $this->getFieldData($fieldData->data_field);
                                                        }
                                                    }

                                                    $stateActionTmp->toStateInputData = $newToStateInputData;
                                                }

                                                $result[]                       = $stateActionTmp;

                                            }else if($stateAction->type == "actions"){

                                                $result[]                       = $stateAction;
                                            }
                                        }
                                    }
                                }

                                break;
                            }
                        }
                    }
                }
            }
        }
        
        return $result;
    }

    protected function checkConditions($p_conditions, $p_localData){

        $result             = true;
        //TODO IMPLEMENTAR OPERATOR
        
        $data               = array();
        $data['global']     = $this->allGlobal();
        $data['local']      = $this->allLocal();
        $data['session']    = $_SESSION;
        $data['scope']      = $p_localData;

        foreach($p_conditions as $condition){
            
            if($result && $condition->type == "expressions"){

                $result = \Nubesys\Core\Utils\Expressions::evaluateDataExpression($data, $condition->expression);
            }
        }
        
        return $result;
    }

    //DATA SOURCE METHODS
    protected function setModelObjectState($p_model, $p_id, $p_state, $p_data){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->setModelObjectState($p_model, $p_id, $p_state, $p_data);
    }

    protected function getFieldData($p_field){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getDataFieldDefinitions($p_field);
    }
    
    protected function getModelObjectNameField($p_model){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getNameField($p_model);
    }

    protected function getModelObjectImageField($p_model){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getImageField($p_model);
    }

    protected function getModelObjectIconField($p_model){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getIconField($p_model);
    }

    protected function getModelObjects($p_model, $p_query){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getModelObjects($p_model , $p_query);
    }

    protected function editModelObject($p_model, $p_id, $p_data){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->editModelObjectData($p_model, $p_id, $p_data);
    }

    protected function addModelObject($p_model, $p_data){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->addModelObjectData($p_model, $p_data);
    }

    protected function getModelObject($p_model, $p_id){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getModelObject($p_model , $p_id);
    }

    protected function getModelData($p_model){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getModelData($p_model);
    }

    protected function getModelDefinitions($p_model, $p_modelData){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getModelDefinitions($p_model, $p_modelData);
    }

    protected function getModelDefinitionsGroups(){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getModelDefinitionsGroups();
    }
}
