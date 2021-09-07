<?php

namespace Nubesys\Core\Ui\Pages\Module;

use Nubesys\Vue\Services\VueUiService;
use Nubesys\Core\Register;
use Nubesys\Data\DataSource\ModuleDataSource;

class AppModule extends VueUiService {

    protected $componentsReferences;

    //MAIN ACTION
    public function mainAction(){

        $this->accessControl();

        $this->componentsReferences = new Register();
        
        $this->callServiceAction();
        
        //SLIDERSJS SERVICE VARS
        $this->setJsDataVar("maxzindex", 5000);
        $this->setJsDataVar("componentsReferences", $this->componentsReferences->all());
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

        $this->setActionPageTitle($action);

        $initialScope       = "actions." . $action;

        $actionTree         = $this->getScopeTree($initialScope);

        $this->buildTreeEntities($initialScope, $actionTree);
        
        //TODO AFFTER ACTION OVERWRITE METHOD $this->affterAction($action) --> <action>AffterAction()

    }

    protected function accessControl(){
        
        $accessControl      = true;

        if($this->getLocal("accessControl")){

            $accessControl  = $this->getLocal("accessControl");
        }

        if($accessControl){

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

    protected function setComponentParam($p_scopePath, $key, $p_value){

        $this->componentsReferences->setDot($p_scopePath . "." . $key, $p_value);
    }

    private function buildTreeEntities($p_lastScopePath, $p_entitiesTree, $p_componentParams = array()){
        $result = "";

        $index = 0;
        foreach($p_entitiesTree as $entityReference=>$entityDefinition){

            if(is_array($entityDefinition) && isset($entityDefinition["type"])){

                $active                     = true;

                if(isset($entityDefinition["active"])){

                    $active                 = $entityDefinition["active"];
                }

                if($active){

                    $scopePath                  = $p_lastScopePath . "." . $entityReference;

                    $this->setComponentParam($scopePath, "type", $entityDefinition["type"]);

                    //PAGE LAYOUT
                    if($entityDefinition["type"] == "pagelayout"){

                        $renderCode                         = $this->buildTreeEntities($scopePath, $entityDefinition);
                        
                        //AHORA COLOCA COMPONENTES RESULTANTES
                        //TODO: HACER QUE RETORNE CODIGO PARA QUE SEA MAS DINAMICO EL TEMPLATE
                        $this->buildPageLayout($entityReference, $renderCode);
                    }

                    //COMPONENT
                    if($entityDefinition["type"] == "component"){

                        $result                             .= $this->buildComponentCode($scopePath, $entityReference, $entityDefinition, $p_componentParams);
                    }

                    //SLIDER
                    if($entityDefinition["type"] == "slider"){

                        $this->setComponentParam($scopePath, "open", false);

                        $p_componentParams['sliderReference']           = $entityReference;

                        $renderCode                                     = $this->buildTreeEntities($scopePath, $entityDefinition, $p_componentParams);

                        $result                                         .= $this->buildSliderCode($entityReference, $renderCode);
                    }

                    //SLIDER LAYOUT
                    if($entityDefinition["type"] == "sliderlayout"){

                        $renderCode                                     = $this->buildTreeEntities($scopePath, $entityDefinition, $p_componentParams);

                        $result                                         .= $this->buildSliderLayoutCode($entityReference, $renderCode);
                    }

                    //TABS MENU
                    if($entityDefinition["type"] == "tabsmenu"){

                        $p_componentParams['tabReference']              = $entityReference;

                        $tabsArray                                      = $this->buildTreeEntities($scopePath, $entityDefinition, $p_componentParams);
                        
                        $result                                         .= $this->buildTabsMenuCode($entityReference, $tabsArray);
                    }

                    //TABS MENU ITEM
                    if($entityDefinition["type"] == "tabsmenuitem"){

                        if($index == 1){

                            $this->setComponentParam($scopePath, "active", true);
                        }else{

                            $this->setComponentParam($scopePath, "active", false);
                        }

                        if(!\is_array($result)){

                            $result                                     = array();
                        }

                        $p_componentParams['tabItemReference']          = $entityReference;

                        $result[$entityReference]                       = array();
                        $result[$entityReference]['label']              = $entityDefinition['label'];
                        $result[$entityReference]['content']            = $this->buildTreeEntities($scopePath, $entityDefinition, $p_componentParams);
                    }

                }
            }

            $index += 1;
        }

        return $result;
    }

    private function buildComponentCode($p_lastScopePath, $p_referenceName, $p_component, $p_aditionalParams = array()){
        $result = "";
        
        //TODO: BEFORE AFFTER CODE

        if(isset($p_component["classPath"])){
                    
            if(class_exists($p_component["classPath"])){

                $componentClassPath                         = $p_component["classPath"];
    
                $componentInstance                          = new $componentClassPath($this->getDI());
                
                $componentParams                            = \array_merge($p_aditionalParams, array());
                $componentParams["scopePath"]               = $p_lastScopePath;
                $componentParams["referenceName"]           = $p_referenceName;
                $componentParams["dataService"]             = $p_component["dataService"];

                $result .= $componentInstance->doComponentRender($componentParams, $this->getId());
            }
        }

        return $result;
    }

    private function buildSliderLayoutCode($p_layout, $p_code){

        $sliderLayoutCode           = '<!-- SLIDER ' . $p_layout . ' LAYOUT START -->';

        $sliderLayoutCode           .= $p_code;

        $sliderLayoutCode           .= '<!-- SLIDER ' . $p_layout . ' LAYOUT END -->';

        return $sliderLayoutCode;
    }

    private function buildSliderCode($p_sliderReferenceName, $p_code){

        $sliderCode                 = '<div class="ui right very wide sidebar" id="' . $p_sliderReferenceName . '" style="width: 920px; background-color: #ffffff; overflow: scroll;">';
        
        $sliderCode                 .= $p_code;

        $sliderCode                 .= '</div>';
        
        return $sliderCode;
    }

    private function buildTabsMenuCode($p_tabsReferenceName, $p_array){

        $tabsMenuCode               = '<div class="ui grid padded">';
        $tabsMenuCode               .= '<div class="column">';
        $tabsMenuCode               .= '<div class="ui basic segment loadingElement" style="padding: 0px;">';
        $tabsMenuCode               .= '<div class="ui top attached tabular menu" id="' . $p_tabsReferenceName . ' " style="padding-left: 14px; padding-right: 14px;">';
        
        $index = 0;
        foreach($p_array as $tabItemReference=>$tabItem){

            $active                 = ($index == 0) ? ' active' : '';

            $tabsMenuCode           .= '<div class="item' . $active . ' tabbuttom" id="' . $tabItemReference . '_item" data-tab="' . $tabItemReference . '" onclick="nbsApp._nbs_service.doActiveTabSlider({\'tab\':\'' . $tabItemReference . '\'},null);">' . $tabItem['label'] . '</div>';

            $index += 1;
        }

        $tabsMenuCode               .= '</div>';

        $index = 0;
        foreach($p_array as $tabItemReference=>$tabItem){

            $active                 = ($index == 0) ? ' active ' : ' ';
            
            $tabsMenuCode           .= '<div class="ui tab' . $active . 'segment" id="' . $tabItemReference . '_segment" data-tab="' . $tabItemReference . '" style="padding: 0px; margin-top: -2px;">' . $tabItem['content'] . '</div>';

            $index += 1;
        }

        $tabsMenuCode .=            '</div></div></div>';
        
        return $tabsMenuCode;
    }

    private function buildPageLayout($p_layout, $p_code){
        
        $this->setViewVar($p_layout, $p_code);
    }

    private function getScopeTree($p_scopePath){

        $result             = array();
        
        if($this->hasLocal($p_scopePath)){

            $result         = $this->getLocal($p_scopePath);

        }else{
            
            exit("Module -> " . $p_scopePath . " Not Found");
        }
        
        return $result;
    }

    private function setActionPageTitle($p_action){
        
        $scopePath          = "actions." . $p_action . ".title";
        
        if($this->hasLocal($scopePath)){
            
            $this->setTitle($this->getLocal($scopePath));

            //TODO LLEVAR A LA SUB CLASE DEL SERVICIO
            $this->logInfo("PAGE-VIEW", "NOSVAMOOS|EVENT", $this->getLocal($scopePath));
        }elseif($this->hasLocal("title")){

            $this->setTitle($this->getLocal("title"));
        }

        //TODO LLEVAR A LA SUB CLASE DEL SERVICIO
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
    
    //MODULE DATA SERVICES

    //SET RELATIONS
    public function moduleSetRelationService(){
        //\sleep(3);
        
        if($this->hasJsonParam()){

            $params                                 = $this->getJsonParam();
            
            $scopePath                              = (isset($params["scopePath"])) ? $params["scopePath"] : false;

            if(isset($params["model"]) && isset($params["relation"]) && isset($params["id"])){
                $resul                              = array();
                $result["dataActions"]              = array();

                $relData                            = array();

                if(isset($params["relData"])){

                    $relData    = $params["relData"];
                }

                //ADD
                if(isset($params["relAdd"])){

                    $relData[] = $params["relAdd"];
                }

                //TODO : EDIT/REMOVE
                
                $data                               = array();

                $data['rel_' . $params['relation']] = $relData;

                $editResult                         = $this->editModelObject($params['model'], $params["id"], $data);
                
                $this->setLocal("relData", $relData);
                
                //TODO CONTROL DE ERROR
                            
                //DATA ACTIONS
                if($scopePath !== false){

                    if(isset(($this->getLocal($scopePath))['onRelationActions'])){

                        $result["dataActions"] = ($this->getLocal($scopePath))['onAddRelationActions'];
                    }

                    if(isset(($this->getLocal($scopePath))['onRemoveRelationActions'])){

                        $result["dataActions"] = ($this->getLocal($scopePath))['onRemoveRelationActions'];
                    }

                    if(isset(($this->getLocal($scopePath))['onResponseActions'])){

                        $result["dataActions"] = ($this->getLocal($scopePath))['onResponseActions'];
                    }
                }
                
                if(isset($params['onAddRelationActions'])){
                    
                    $result["dataActions"] = array_merge($result["dataActions"], $this->parseData($params['onAddRelationActions']));
                }

                if(isset($params['onRemoveRelationActions'])){

                    $result["dataActions"] = array_merge($result["dataActions"], $this->parseData($params['onRemoveRelationActions']));
                }

                if(isset($params['onResponseActions'])){

                    $result["dataActions"] = array_merge($result["dataActions"], $this->parseData($params['onResponseActions']));
                }
                
                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Some required param not found");
            }

        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //MODULE COMPONETS ACTIONS

    //TOPBAR
    public function moduleTopBarService(){
        //\sleep(2);
        if($this->hasJsonParam()){

            $params                         = $this->getJsonParam();

            $scopePath                      = (isset($params["scopePath"])) ? $params["scopePath"] : false;

            if($scopePath !== false){

                $result                     = array();

                //TITLE
                //TODO: ver para que tome por defecto el titulo del mainmodel si no existe
                $result["title"]            = ($this->getLocal($scopePath))['title'];

                //ACTIONS
                $result["actions"]          = ($this->getLocal($scopePath))['actions'];

                //TODO: ADITIONAL ACTIONS EN SUB CLASE

                //REFERENCE NAME
                $result["referenceName"]    = $params["referenceName"];

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

            $scopePath                      = (isset($params["scopePath"])) ? $params["scopePath"] : false;

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
                                        $itemTmp['label']       = $item['name'];
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
                $result["referenceName"]    = $params["referenceName"];

                $this->setServiceSuccess($result);
            }else{

                $this->setServiceError("Invalid ScopePath: " . $scopePath);
            }

        }else{

            $this->setServiceError("Invalid Params");
        }
    }

    //SELECTOR
    public function moduleSelectorService(){
        \sleep(1);
        
        if($this->hasJsonParam()){
            
            $params                         = $this->getJsonParam();
            
            $scopePath                      = (isset($params["scopePath"])) ? $params["scopePath"] : false;
            
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

                    //HARD FILTERS
                    if(isset($params["hardFilters"])){

                        if(!isset($query["hardfilters"])){

                            $query["hardfilters"]       = array();
                        }

                        foreach($params["hardFilters"] as $filterIndex=>$filterData){

                            $query["hardfilters"][$filterIndex]     = $filterData;
                        }
                    }

                    if(isset(($this->getLocal($scopePath))['hardFilters'])){

                        if(!isset($query["hardfilters"])){

                            $query["hardfilters"]       = array();
                        }

                        foreach(($this->getLocal($scopePath))['hardFilters'] as $filterIndex=>$filterData){

                            $query["hardfilters"][$filterIndex]     = $filterData;
                        }
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
                    if(isset(($this->getLocal($scopePath))['notRenderedFields']) && isset($result["fields"])){

                        $notRenderedFields              = ($this->getLocal($scopePath))['notRenderedFields'];
                        
                        $newFieldsTmp                   = array();

                        foreach($result['fields'] as $field=>$definition){
                            
                            if(!in_array($field, $notRenderedFields)){
                                
                                $newFieldsTmp[$field]   = $definition;
                            }
                        }

                        $result['fields']               = $newFieldsTmp;
                    }

                    //HIDDEN FIELDS
                    if(isset(($this->getLocal($scopePath))['hiddenFields'])  && isset($result["fields"])){

                        $hiddenFields                   = ($this->getLocal($scopePath))['hiddenFields'];

                        foreach($result['fields'] as $field=>$definition){

                            if(in_array($field, $hiddenFields)){

                                $definition['uiOptions']->hidden = true;
                            }
                        }
                    }

                    //NOT HIDDEN FIELDS
                    if(isset(($this->getLocal($scopePath))['notHiddenFields']) && isset($result["fields"])){

                        $notHiddenFields                = ($this->getLocal($scopePath))['notHiddenFields'];

                        foreach($result['fields'] as $field=>$definition){

                            if(in_array($field, $notHiddenFields)){

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
                    $result["referenceName"]            = $params["referenceName"];
                    
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

                //HARD FILTERS
                if(isset($params["hardFilters"])){

                    if(!isset($query["hardfilters"])){

                        $query["hardfilters"]       = array();
                    }

                    foreach($params["hardFilters"] as $filterIndex=>$filterData){

                        $query["hardfilters"][$filterIndex]     = $filterData;
                    }
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

                        $objectNameFields               = $this->getModelObjectNameFields($params['model']);
                        $objectImageField               = $this->getModelObjectImageField($params['model']);
                        $objectIconField                = $this->getModelObjectIconField($params['model']);
                        

                        $objectDataTmp['id']            = $objectData["_id"];

                        //NAME
                        $objectDataTmp['name']          = "";
                        
                        if(\is_array($objectNameFields)){
                            
                            $objectDataNameValues       = array();

                            foreach($objectNameFields as $fieldId){

                                $objectDataNameValues[] = $objectData[$fieldId];
                            }

                            $objectDataTmp['name']      = \implode(" ", $objectDataNameValues);
                        }else{

                            $objectDataTmp['name']      = $objectDataTmp['id'];
                        }
                        
                        //IMAGE
                        if(isset($objectData[$objectImageField])){
    
                            $objectDataTmp['image']     = $objectData[$objectImageField];
                        }
                        
                        //ICON
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

            $scopePath                      = (isset($params["scopePath"])) ? $params["scopePath"] : false;
            
            if($scopePath !== false){

                if(isset($params["model"])){

                    $result                     = array();

                    $result["dataActions"]      = array();

                    //TODO CUSTOM FIELDS

                    //PRE DATA MANIPULATIONS
                    if(isset($params["data"]) && count($params["data"]) > 0){

                        //HIDDEN FIELDS
                        $dataTmp = array();
                        if(isset(($this->getLocal($scopePath))['hiddenFields'])){

                            foreach($params["data"] as $field=>$value){

                                if(!in_array($field, ($this->getLocal($scopePath))['hiddenFields'])){

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
                        if(isset($params["data"]) && count($params["data"]) > 0){
                            
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
                        if(isset($params["data"]) && count($params["data"]) > 0){
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

                        $notRenderedFields              = ($this->getLocal($scopePath))['notRenderedFields'];
                        
                        $newFieldsTmp                   = array();

                        foreach($result['fields'] as $field=>$definition){
                            
                            if(!in_array($field, $notRenderedFields)){
                                
                                $newFieldsTmp[$field]   = $definition;
                            }
                        }

                        $result['fields']               = $newFieldsTmp;
                    }

                    //HIDDEN FIELDS
                    if(isset(($this->getLocal($scopePath))['hiddenFields']) && isset($result["fields"])){

                        $hiddenFields                   = ($this->getLocal($scopePath))['hiddenFields'];
                        
                        foreach($result['fields'] as $field=>$definition){
                            
                            if(in_array($field, $hiddenFields)){
                                
                                $definition['uiOptions']->hidden = true;
                            }
                        }
                    }
                    
                    //NOT HIDDEN FIELDS
                    if(isset(($this->getLocal($scopePath))['notHiddenFields']) && isset($result["fields"])){

                        $notHiddenFields                = ($this->getLocal($scopePath))['notHiddenFields'];

                        foreach($result['fields'] as $field=>$definition){

                            if(in_array($field, $notHiddenFields)){

                                $definition['uiOptions']->hidden = false;
                            }else{

                                $definition['uiOptions']->hidden = true;
                            }
                        }
                    }

                    //VALIDATIONS
                    $result["validations"]      = array();
                    if(isset(($this->getLocal($scopePath))['validations'])){

                        $result['validations']  = ($this->getLocal($scopePath))['validations'];
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
                    $result["referenceName"]    = $params["referenceName"];

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
            
            $scopePath                      = (isset($params["scopePath"])) ? $params["scopePath"] : false;

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

                    $objectNameFields       = $this->getModelObjectNameFields($params['model']);
                    $objectImageField       = $this->getModelObjectImageField($params['model']);
                    $objectIconField        = $this->getModelObjectIconField($params['model']);

                    //NAME
                    $result['name']         = "";
                        
                    if(\is_array($objectNameFields)){

                        $objectDataNameValues       = array();

                        foreach($objectNameFields as $fieldId){

                            $objectDataNameValues[] = $objectData[$fieldId];
                        }

                        $result['name']      = \implode(" ", $objectDataNameValues);
                    }else{

                        $result['name']      = $objectDataTmp['id'];
                    }
                    
                    //IMAGE
                    if(isset($objectData[$objectImageField])){

                        $result['image']     = $objectData[$objectImageField];
                    }
                    
                    //ICON
                    if(isset($objectData[$objectIconField])){

                        $result['icon']      = $objectData[$objectIconField];
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
    
    protected function getModelObjectFirstNameField($p_model){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getFirstNameField($p_model);
    }

    protected function getModelObjectNameFields($p_model){

        $dataSource                         = new ModuleDataSource($this->getDI());

        return $dataSource->getNameFields($p_model);
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
