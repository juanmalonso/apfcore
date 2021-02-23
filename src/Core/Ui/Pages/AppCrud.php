<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Navigation\SideMenu\SideMenu;
use Nubesys\Core\Ui\Components\App\Top\TopBar\TopBar;
use Nubesys\Core\Ui\Components\App\Selectors\TableList\TableList;
use Nubesys\Core\Ui\Components\App\Selectors\HorizontalCards\HorizontalCards;
use Nubesys\Core\Ui\Components\App\Editors\Form\Form;
use Nubesys\Core\Ui\Components\App\Viewer\Table\TableViewer;
use Nubesys\Core\Ui\Components\App\Editors\Managers\MasterManager;
use Nubesys\Core\Ui\Components\App\Importer\Importer;
use Nubesys\Core\Ui\Components\App\Filters\FiltersForm\FiltersForm;

//DATA SOURCE
use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Table as TableDataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Custom as CustomDataSource;

//SPREADSHEET
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        
        $this->callServiceAction();
    }

    //SERVICE ACTIONS
    //LIST SERVICE ACTION
    protected function listAction(){
        $this->generateSideMenu();
        $this->generateSelector();
        $this->generateTopBar();
    }

    //ADD SERVICE ACTION
    protected function addAction(){
        
        $this->generateSideMenu();

        $editorAditionalParams      = array();

        if($this->hasPostParam("datasource")){

            $toSaveData             = $this->getEditorAddData($this->allPostParams());
            
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

        $this->generateTopBar();
    }

    //EDIT SERVICE ACTION
    protected function editAction(){

        $this->generateSideMenu();
        
        $editorAditionalParams      = array();
        
        if($this->hasPostParam("datasource")){
            
            $toEditData             = $this->getEditorEditData($this->allPostParams());
            
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
                        \sleep(1);
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

        $this->generateTopBar();
    }

    //EDIT SERVICE ACTION
    protected function viewAction(){

        $this->generateSideMenu();
        
        $viewerAditionalParams      = array();
        
        $this->generateViewer($viewerAditionalParams);

        $this->generateTopBar();
    }

    //MANAGER SERVICE ACTION
    protected function managerAction(){

        $this->generateSideMenu();
        
        $managerAditionalParams      = array();
        
        $this->generateMasterManager($managerAditionalParams);

        $this->generateTopBar();
    }

    //ADD SERVICE ACTION
    protected function importAction(){
        set_time_limit(0);
        
        if($this->hasFilesParam("importer_file")){

            $file = $this->getFilesParam("importer_file");
            
            if(!$file['error']){

                $inputFileType      = IOFactory::identify($file['tmpPath']);

                $reader             = IOFactory::createReader($inputFileType);
                $reader->setReadDataOnly(true);

                $spreadsheet        = $reader->load($file['tmpPath']);

                $sheetData          = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                
                $importData         = array();

                $index              = 2;
                while($index < count($sheetData)){

                    $importDataRow = array();

                    foreach($sheetData[1] as $column=>$field){

                        $importDataRow[$field] = $sheetData[$index][$column];
                    }

                    $importData[]   = $importDataRow;

                    $index++;
                }
                
                $objectsEditorDataSource        = $this->getDataSource($this->getLocal("application.importer.dataSource"));

                $importDataResult                 = $objectsEditorDataSource->importData($importData);
            }
        }
        
        header("Location: " . $this->getLocal("application.urlMaps.LIST"));
        exit();
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
                $dataSourceQuery['filters'] = (isset($param['filters'])) ? $param['filters'] : array();
                $dataSourceQuery['page']    = (isset($param["page"])) ? $param["page"] : 1;
                $dataSourceQuery['rows']    = (isset($param["rows"])) ? $param["rows"] : 10;

                $this->setServiceSuccess($objectsSelectorDataSource->getData($dataSourceQuery));

            }else if(isset($param["model"])){

                $dataSourceOptions                  = array();
                $dataSourceOptions['model']         = $param["model"];

                $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

                $dataSourceQuery            = array();
                $dataSourceQuery['filters'] = (isset($param['filters'])) ? $param['filters'] : array();
                $dataSourceQuery['page']    = (isset($param["page"])) ? $param["page"] : 1;
                $dataSourceQuery['rows']    = (isset($param["rows"])) ? $param["rows"] : 100;
                
                $this->setServiceSuccess($dataSource->getData($dataSourceQuery));
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

    //IMPORTER
    protected function generateImporter(){
        
        $objectImporterDataSource                       = $this->getDataSource($this->getLocal("application.selector.dataSource"));
        
        $objectImporterParams                           = array();
        $objectImporterParams['datasource']             = $this->getLocal("application.selector.dataSource");
        $objectImporterParams['form_action']            = $this->getLocal("application.urlMaps.IMPORT");
        
        $objectImporter                                 = new Importer($this->getDI());
        $this->appendComponent("tools", $objectImporter, $objectImporterParams);
        
    }

    //FILTERS
    protected function generateFilters(){

        //TODO: MEGA FILTER (PASAR COMO PARAMETRO LOS FIELDS)
        //TODO: FILTERS TOGGLE BUTTONS (PHILS)
        //TODO: CHECKBOS FILTERS (SWITCHER)
        //TODO: DATE RANGE
        //TODO: TAB FILTERS (SELE PASA POR PARAMETRO EL FIELD y LAS OPCIONES (UNICO))
        //TODO: TAB STATUS FILTERS

        $objectsFiltersDataSource               = $this->getDataSource($this->getLocal("application.selector.dataSource"));
        
        $objectsFiltersParams                   = array();
        $objectsFiltersParams['datasource']     = $this->getLocal("application.selector.dataSource");
        $objectsFiltersParams['filters']        = $this->getFiltersFields($objectsFiltersDataSource);

        $objectsFiltersParams['keyword']        = "*";
        if($this->hasUrlParam("keyword")){

            $objectsFiltersParams['keyword']    = $this->getUrlParam("keyword");
        }
        
        $objectsFilters                     = new FiltersForm($this->getDI());
        $this->appendComponent("tools", $objectsFilters, $objectsFiltersParams);
    }

    protected function getFiltersFields($p_dataSource){

        $result                         = array();

        $disableAllFilters              = false;

        if($this->hasLocal("application.selector.disableAllFilters")){

            $disableAllFilters          = $this->getLocal("application.selector.disableAllFilters");
        }

        if(!$disableAllFilters){

            //TODO: FILTROS SEGUN RELACIONES
            //TODO: FILTROS DE TAGS
            //TODO: FILTROS DE objActive
            //TODO: FILTROS DE objState
            //TODO: FILTROS DE objDateAdd
            
            foreach($p_dataSource->getDataRenderableDefinitions() as $field=>$definition){

                $disabledFilters                        = array();

                if($this->hasLocal("application.selector.disabledFilters")){

                    $disabledFilters          = $this->getLocal("application.selector.disabledFilters");
                }

                if(!in_array($field, $disabledFilters)){

                    if($definition["uiOptions"]->filterable && $definition['isRelation'] == false){
                        
                        $filterTemp                     = array();

                        $filterTemp['id']               = $field;
                        
                        if($definition['type'] == "options"){
                            
                            $filterTemp['type']             = "dropdown";
                            $filterTemp['multiple']         = false;
                            $filterTemp['label']            = $definition["uiOptions"]->label;
                            $filterTemp['icon']             = $definition["uiOptions"]->icon;
                            $filterTemp['data']             = array();
                            
                            if(property_exists($definition['typeOptions'], 'data')){

                                foreach($definition['typeOptions']->data as $option){
                                    
                                    $optionTemp             = array();
                                    $optionTemp['value']    = $option->value;
                                    $optionTemp['label']    = $option->label;
                                    $optionTemp['icon']     = (property_exists($option, 'icon')) ? $option->icon : "";
                                    $optionTemp['image']    = (property_exists($option, 'image')) ? $option->image : "";

                                    $filterTemp['data'][]   = $optionTemp;
                                }
                            }

                            if(property_exists($definition['typeOptions'], 'multiple') && $definition['typeOptions']->multiple == true){

                                $filterTemp['multiple']         = true;
                            }

                            if($this->hasUrlParam($field)){

                                $filterTemp['selected']     = (array)$this->getUrlParam($field);
                            }
                            
                            $result[$field] = $filterTemp;
                        }
                        
                        if($definition['type'] == "objectr" || $definition['type'] == "objectsr"){

                            //TODO: USAR EL NOMBRE DEL MODEL como LABEL
                            $filterTemp['type']             = "dropdown";
                            $filterTemp['multiple']         = true;
                            $filterTemp['label']            = $definition["uiOptions"]->label;
                            $filterTemp['icon']             = $definition["uiOptions"]->icon;
                            $filterTemp['data']             = array();
                            
                            if(property_exists($definition['typeOptions'], 'model')){
                                
                                $modelDataIdNamesParams                     = array();
                                if(property_exists($definition['typeOptions'], 'hardfilters')){

                                    $modelDataIdNamesParams['hardfilters']  = (array)$definition['typeOptions']->hardfilters;
                                }

                                $modelDataIdNames                       = $this->getModelDataIdNames($definition['typeOptions']->model, $modelDataIdNamesParams);
                                
                                foreach($modelDataIdNames['objects'] as $object){
                                    
                                    $optionTemp             = array();
                                    $optionTemp['value']    = $object['id'];
                                    $optionTemp['label']    = $object['name'];
                                    $optionTemp['icon']     = (isset($object['icon'])) ? $object['icon'] : "";
                                    $optionTemp['image']    = (isset($object['image'])) ? $object['image'] : "";

                                    $filterTemp['data'][]   = $optionTemp;
                                }
                                
                            }
                            
                            if($this->hasUrlParam($field)){

                                $filterTemp['selected']     = (array)$this->getUrlParam($field);
                            }
                            
                            $result[$field] = $filterTemp;
                        }
                    }
                }
            }
        }

        ///CUSTOM ADITIONAL FILTERS METHOD
        if(method_exists($this, "getCustomAditionalFilters")){

            $result         = $this->getCustomAditionalFilters($result);
        }

        if($this->hasLocal("application.selector.customAditionalFilters")){

            $result         = array_merge($result, $this->getLocal("application.selector.customAditionalFilters"));
        }
        
        return $result;
    }

    /*
     ___   ______   _        ______    _____   _______    ____    _____  
    / ____| |  ____| | |      |  ____|  / ____| |__   __|  / __ \  |  __ \ 
    | (___   | |__    | |      | |__    | |         | |    | |  | | | |__) |
    \___ \  |  __|   | |      |  __|   | |         | |    | |  | | |  _  / 
    ____) | | |____  | |____  | |____  | |____     | |    | |__| | | | \ \ 
    |_____/  |______| |______| |______|  \_____|    |_|     \____/  |_|  \_\

    */
    protected function generateSelector(){
        
        $this->generateImporter();
        
        $this->generateFilters();
        
        $selectorType                           = $this->getLocal("application.selector.type");

        if($selectorType == "tablelist"){

            //TABLELIST SELECTOR
            $objectsSelectorDataSource              = $this->getDataSource($this->getLocal("application.selector.dataSource"));
            
            $objectsSelectorParams                  = array();
            $objectsSelectorParams['datasource']    = $this->getLocal("application.selector.dataSource");
            $objectsSelectorParams['fields']        = $this->getSelectorFields($objectsSelectorDataSource);
            $objectsSelectorParams['data']          = $this->getSelectorData($objectsSelectorDataSource);
            $objectsSelectorParams['paginator']     = $this->getLocal("application.selector.paginator");

            $objectsSelector                        = new TableList($this->getDI());
            $this->placeComponent("main", $objectsSelector, $objectsSelectorParams);

        }else if($selectorType == "hcards"){

            //HCARDS SELECTOR
            $objectsSelectorDataSource              = $this->getDataSource($this->getLocal("application.selector.dataSource"));
            
            $objectsSelectorParams                  = array();
            $objectsSelectorParams['datasource']    = $this->getLocal("application.selector.dataSource");
            $objectsSelectorParams['fields']        = $this->getSelectorFields($objectsSelectorDataSource);
            $objectsSelectorParams['data']          = $this->getSelectorData($objectsSelectorDataSource);
            $objectsSelectorParams['paginator']     = $this->getLocal("application.selector.paginator");
            
            $objectsSelector                        = new HorizontalCards($this->getDI());
            $this->placeComponent("main", $objectsSelector, $objectsSelectorParams);
        }
        //exit();
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
        
        foreach($p_dataSource->getDataRenderableDefinitions() as $field=>$definition){
            
            $fieldTemp                  = array();
            $fieldTemp['renderType']    = "VALUE";
            
            if($definition["uiOptions"]->listable){

                if($definition["uiOptions"]->hidden){

                    $fieldTemp["renderType"]            = "HIDDEN";
                }else{
                    
                    if($definition["isName"]){

                        $fieldTemp["renderType"]        = "LINK";
                        $fieldTemp["urlMap"]            = $this->getLocal("application.urlMaps.EDIT");

                    }else if($definition["isImage"] && $definition['typeOptions']->model == "image"){

                        $fieldTemp["renderType"]        = "IMAGE";
                        $fieldTemp["imgSrcMap"]         = $this->getLocal("application.urlMaps.IMAGE");

                    }else if($definition["isImage"] && $definition['typeOptions']->model == "avatar"){

                        $fieldTemp["renderType"]        = "AVATAR";
                        $fieldTemp["imgSrcMap"]         = $this->getLocal("application.urlMaps.AVATAR");

                    }else if($definition["isState"] && $field == "objState"){

                        $fieldTemp["renderType"]        = "STATE";

                    }else{

                        if($definition["type"] == "json"){

                            $fieldTemp["renderType"]            = "JSON";
                        }

                        if($definition["type"] == "tags"){

                            $fieldTemp["renderType"]            = "TAGS";
                        }

                        if($definition['type'] == "options"){

                            //$fieldTemp["renderType"]            = "TAG";

                            if(property_exists($definition['typeOptions'], 'multiple') && $definition['typeOptions']->multiple == true){

                                $fieldTemp["renderType"]            = "TAGS";
                            }
                        }

                        if($definition['type'] == "objectr"){

                            $fieldTemp["renderType"]            = "TAG";

                            if(property_exists($definition['typeOptions'], 'model')){

                                if($definition['typeOptions']->model == "image" || $definition['typeOptions']->model == "avatar"){

                                    $fieldTemp["renderType"]    = "IMAGETAG";
                                }
                            }
                        }

                        if($definition['type'] == "objectsr"){

                            $fieldTemp["renderType"]            = "TAGS";

                            if(property_exists($definition['typeOptions'], 'model')){

                                if($definition['typeOptions']->model == "image" || $definition['typeOptions']->model == "avatar"){

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

        ///PRE SELECTOR FIELDS
        if(method_exists($this, "preProcessSelectorFields")){

            $result         = $this->preProcessSelectorFields($result);
        }
        
        return $result;
    }

    //SELECTOR DATOS
    protected function getSelectorData($p_dataSource){

        $definitions                        = $p_dataSource->getDataRenderableDefinitions();
        
        $dataSourceQuery                    = array();
        $dataSourceQuery['page']            = ($this->hasUrlParam("page")) ? $this->getUrlParam("page") : 1;
        $dataSourceQuery['rows']            = ($this->hasUrlParam("rows")) ? $this->getUrlParam("rows") : 10;

        foreach($definitions as $definition){

            if($definition['uiOptions']->filterable == true){

                if($this->hasUrlParam($definition['id'])){

                    if(!isset($dataSourceQuery['filters'])){

                        $dataSourceQuery['filters']         = array();
                    }

                    $dataSourceQuery['filters'][$definition['id']]           = (array)$this->getUrlParam($definition['id']);
                }
            }
        }

        if($this->hasUrlParam('keyword')){

            $dataSourceQuery['keyword']     = $this->getUrlParam('keyword');
        }
        
        $data                               = $p_dataSource->getData($dataSourceQuery);
        
        if(isset($data['objects']) && is_array($data['objects'])){

            foreach($data['objects'] as $row=>$object){
                
                foreach($object as $field=>$value){
                    
                    if(isset($definitions[$field])){

                        if($definitions[$field]['type'] == "options" && $definitions[$field]['isName'] == false && $definitions[$field]['isRelation'] != true){
                            
                            if($value != ""){
                                
                                if(property_exists($definitions[$field]['typeOptions'], 'data')){
                                    
                                    $valueIdNames                                   = array();
                                    
                                    foreach($definitions[$field]['typeOptions']->data as $option){
                                        
                                        if(in_array($option->value, (array)$value)){

                                            if(property_exists($definitions[$field]['typeOptions'], 'multiple') && $definitions[$field]['typeOptions'] == true){

                                                $objectTmp                          = array();
                                                $objectTmp['id']                    = $option->value;
                                                $objectTmp['name']                  = $option->label;
                                                $objectTmp['image']                 = (property_exists($option, "image")) ? $option->image : "";
                                                $objectTmp['icon']                  = (property_exists($option, "icon")) ? $option->icon : "";

                                                $valueIdNames[$objectTmp['id']]     = $objectTmp;
                                            }else{

                                                $objectTmp                          = array();
                                                $objectTmp['id']                    = $option->value;
                                                $objectTmp['name']                  = $option->label;
                                                $objectTmp['image']                 = (property_exists($option, "image")) ? $option->image : "";
                                                $objectTmp['icon']                  = (property_exists($option, "icon")) ? $option->icon : "";

                                                $valueIdNames                       = $objectTmp;
                                                break;
                                            }
                                        }
                                    }
                                }

                                $data['objects'][$row][$field]  = $valueIdNames;
                            }
                        }
                        
                        if($definitions[$field]['type'] == "objectr"){
                            
                            if(!empty($value) && property_exists($definitions[$field]['typeOptions'], 'model')){
                                
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
                        
                        if(!empty($value) && $definitions[$field]['isState'] == true){
                            
                            $modelData                          = $p_dataSource->getModelData();

                            if(property_exists($modelData['statesOptions'], 'stateable') && $modelData['statesOptions']->stateable == true){

                                
                                $stateIdNames                   = array();

                                $stateIdNames['id']             = $value;
                                $stateIdNames['name']           = $value;
                                $stateIdNames['style']          = "blue";
                                $stateIdNames['image']          = "";
                                $stateIdNames['icon']           = "";


                                if(property_exists($modelData['statesOptions'], 'states')){

                                    foreach($modelData['statesOptions']->states as $state){
                                        
                                        if($state->id == $value){

                                            $stateIdNames['id']     = $state->id;
                                            $stateIdNames['name']   = $state->name;
                                            $stateIdNames['style']  = $state->style;
                                            $stateIdNames['image']  = "";
                                            $stateIdNames['icon']   = ""; 

                                            break;
                                        }
                                    }
                                }

                                $data['objects'][$row][$field]  = $stateIdNames;
                            }
                        }
                    }
                }
                
                $data['objects'][$row]                          = $this->getSelectorPreProcessDataRow($data['objects'][$row]);
            }
        }
        
        return                      $data;
    }

    protected function getSelectorPreProcessDataRow($p_row){

        $result             = $p_row;

        if(method_exists($this, "preProcessSelectorDataRow")){

            $result         = $this->preProcessSelectorDataRow($p_row);
        }

        return $result;
    }

    ///////////////////////////////////VIEWER/////////////////////////////////////////////
    //EDITOR
    protected function generateViewer($p_aditionalParams = array()){
        
        //PONER LOGICA SEGUN TIPO DE EDITOR

        //FORM EDITOR
        $objectsViewerDataSource                = $this->getDataSource($this->getLocal("application.editor.objectsDataSource"));

        $objectsViewerParams                    = array();
        
        $objectsViewerParams['datasource']      = $this->getLocal("application.editor.objectsDataSource");
        $objectsViewerParams['fields']          = $this->getEditorFields($objectsViewerDataSource);
        $objectsViewerParams['fieldsGroups']    = $this->getEditorFieldsGroups($this->getDataSource($this->getLocal("application.editor.fgroupsDataSource")));
        $objectsViewerParams['data']            = $this->getEditorData($objectsViewerDataSource);
        
        $objectsViewer                          = new TableViewer($this->getDI());
        $this->placeComponent("main", $objectsViewer, array_merge($objectsViewerParams, $p_aditionalParams));
        
    }

    //MASTERMANAGER
    protected function generateMasterManager($p_aditionalParams = array()){

        //FORM EDITOR
        $masterManagerDataSource                = $this->getDataSource($this->getLocal("application.manager.objectsDataSource"));

        $masterManagerParams                    = array();
        
        $masterManagerParams['datasource']      = $this->getLocal("application.editor.objectsDataSource");
        
        /*
        $masterManagerParams['fields']          = $this->getEditorFields($objectsViewerDataSource);
        $masterManagerParams['fieldsGroups']    = $this->getEditorFieldsGroups($this->getDataSource($this->getLocal("application.editor.fgroupsDataSource")));
        */

        $masterManagerParams['data']            = $this->getManagerData($masterManagerDataSource);
        $masterManagerParams['fields']          = $this->getManagerFields($masterManagerDataSource, $masterManagerParams['data']);

        //$masterManagerParams['actions']         = $this->getManagerActions($masterManagerDataSource, $masterManagerParams['fields'], $masterManagerParams['data']);

        $masterManager                          = new MasterManager($this->getDI());
        $this->placeComponent("main", $masterManager, array_merge($masterManagerParams, $p_aditionalParams));
        
    }

    private function getManagerData($p_dataSource){

        $result                                 = array();

        //SE RECUPERA EL ID SEGUN PARAMETRO URL
        $objectId                               = false;
        $paramNum                               = ($this->getLocal("application.serviceActions.paramNum")) + 1;
        
        if($this->hasUrlParam($paramNum)){

            if(!strstr(":", $this->getUrlParam($paramNum))){

                $objectId   = $this->getUrlParam($paramNum);
            }
        }
        
        //SE COMPRUEBA SI NO EXISTE EL METODO MANUAL DE RECUPERACION DE DATOS DEL OBJETO
        if($this->hasLocal("application.editor.objectGetMethod")){

            $methodName = $this->getLocal("application.editor.objectGetMethod");

            if(method_exists($this, $methodName)){

                //SE RECUPERA DESDE EL METODO MANUAL
                $result     = $this->$methodName($objectId);
                
            }else{

                //TODO: METHOS NOT FOUND
            }
        }else{

            if($objectId !== false){
                
                //SE RECUPERA DESDE EL DATA SOURCE
                $result     = $p_dataSource->getData($objectId);
            }
        }

        //SE REEMPLAZAN LOS DATOS SEGUN PARAMETROS URL
        foreach($p_dataSource->getDataRenderableDefinitions() as $field=>$definition){
            
            if($this->hasUrlParam($field)){

                $result[$field]     = array($this->getUrlParam($field));
            }
        }

        return $result;
    }

    protected function getManagerFields($p_dataSource, $p_data){
        
        $result                         = array();
        
        foreach($p_dataSource->getDataRenderableDefinitions() as $field=>$definition){
            
            $fieldTemp                  = array();

            $fieldTemp['id']            = $definition["id"];
            $fieldTemp['type']          = $definition["type"];
            $fieldTemp['group']         = $definition["group"];
            $fieldTemp['order']         = $definition["order"];
            $fieldTemp['default']       = $definition["defaultValue"];

            //UI OPTIONS
            foreach($definition["uiOptions"] as $option=>$value){

                $fieldTemp[$option]     = $value; 
            }

            //FILE AND VALIDATIONS OPTIONS
            $fieldTemp["options"]                   = array();
            $fieldTemp["options"]["validation"]     = $definition["validationOptions"];
            $fieldTemp["options"]["file"]           = $definition["attachFileOptions"];

            //OTHER OPTIONS
            foreach($definition["typeOptions"] as $option=>$value){

                $fieldTemp["options"][$option]      = $value; 
            }

            //RELATION
            if(!$definition["isRelation"]){

                $fieldTemp["label"]         = $definition["uiOptions"]->label;
                $fieldTemp["icon"]          = $definition["uiOptions"]->icon;

                if($fieldTemp['type'] == "objectr" || $fieldTemp['type'] == "objectsr"){

                    if(isset($fieldTemp['options']['model']) && ( !isset($fieldTemp['options']['isAsync']) || $fieldTemp['options']['isAsync'] == false)){

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
                    }

                    $fieldTemp["options"]["multiple"]           = false;

                    if($fieldTemp['type'] == "objectsr"){

                        $fieldTemp["options"]["multiple"]       = true;
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

            $result[$definition["id"]] = $fieldTemp;
        }
        
        return $result;
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
        $result                         = array();
        $result['statesActionFields']   = array(); 
        $result['hidden']               = array();
        $result['main']                 = array();
        $result['info']                 = array();
        $result['side']                 = array();

        //FALTAN CARACTERISTICAS SEGUN MODEL (SHOW ID, SHOW NUM ROWS, SHOW DATEADD, SHOW DATE MODIFF, SHOW USER, ACTIVATABLE, DROPABLE,ETC)

        //TODO : VER LOGICA DE ID SOLO READONLY  EN EL CASO DE EDITAR
        
        //VER LOS CAMAPOR ESPECIALES ORDER, STATUS, ACTIVATABLE ETC
        //TODO: FALTAN LOS CAMPOS BASE
        
        foreach($p_dataSource->getDataRenderableDefinitions() as $field=>$definition){
            
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

            //FIELD OPTIONS
            $fieldTemp["options"]                   = array();
            $fieldTemp["options"]["validation"]     = $definition["validationOptions"];
            $fieldTemp["options"]["file"]           = $definition["attachFileOptions"];

            foreach($definition["typeOptions"] as $option=>$value){

                $fieldTemp["options"][$option]      = $value; 
            }

            //STATE
            if($definition["isState"]){



                $fieldTemp["formplace"]             = (property_exists($definition["uiOptions"], "formPlace")) ? $definition["uiOptions"]->formPlace : "info" ;
                $fieldTemp["editAs"]                = (property_exists($definition["uiOptions"], "editAs")) ? $definition["uiOptions"]->editAs : "HIDDEN" ;

                $modelData                          = $p_dataSource->getModelData();

                if(property_exists($modelData['statesOptions'], 'stateable') && $modelData['statesOptions']->stateable == true){

                    if(property_exists($modelData['statesOptions'], 'states')){

                        $toStateActionsTemp                 = array();
                        
                        foreach($modelData['statesOptions']->states as $stateIndex=>$state){
                            
                            //TODO: MEJORAR SOLO PASANDO LOS CAMPOS DEL ESTADO ACTUAL
                            
                            if(property_exists($state, 'toStateActions')){

                                $toStateActionsTemp = array();
                                
                                foreach($state->toStateActions as $toStateAction){
                                    
                                    if(property_exists($toStateAction, 'conditions')){

                                        $isValidAction = false;

                                        foreach($toStateAction->conditions as $condition){

                                            //ROLES
                                            if($condition->type == "roles"){

                                                if(count(array_intersect($condition->roles, $this->getUserRoles())) > 0){

                                                    $isValidAction = true;
                                                    break;
                                                }
                                            }
                                        }

                                        if($isValidAction){

                                            $toStateActionsTemp[] = $toStateAction;
                                        }
                                    }else{

                                        $toStateActionsTemp[] = $toStateAction;
                                    }

                                    $toStateActionLabel         = $toStateAction->label;
                                    $toStateActionType          = $toStateAction->type;
                                    $toStateActionState         = (property_exists($toStateAction, 'toState')) ? $toStateAction->toState : null;

                                    if(!isset($result["statesActionFields"][$toStateActionState])){

                                        $result["statesActionFields"][$toStateActionState]  = array();
                                    }

                                    if(property_exists($toStateAction, 'toStateInputData')){

                                        $inputDataOrder = 1;
                                        foreach($toStateAction->toStateInputData as $inputData){

                                            $inputDataDefinition                            = $p_dataSource->getDataFieldDefinitions($inputData->data_field);

                                            $inputDataFieldTemp                             = array();
                                            $inputDataFieldTemp['id']                       = $inputDataDefinition["dafId"];
                                            $inputDataFieldTemp['type']                     = $inputDataDefinition["typId"];
                                            $inputDataFieldTemp['group']                    = "data";
                                            $inputDataFieldTemp['order']                    = $inputDataOrder;
                                            $inputDataFieldTemp['default']                  = $inputDataDefinition["dafDefaultValue"];
                                            $inputDataFieldTemp["help"]                     = $inputDataDefinition["dafUiOptions"]->help;
                                            $inputDataFieldTemp["info"]                     = $inputDataDefinition["dafUiOptions"]->info;
                                            $inputDataFieldTemp["hidden"]                   = $inputDataDefinition["dafUiOptions"]->hidden;
                                            $inputDataFieldTemp["required"]                 = $inputDataDefinition["dafUiOptions"]->required;
                                            $inputDataFieldTemp["readOnly"]                 = $inputDataDefinition["dafUiOptions"]->readOnly;

                                            $inputDataFieldTemp["options"]                  = array();
                                            $inputDataFieldTemp["options"]["validation"]    = $inputDataDefinition["dafTypValidationOptions"];
                                            $inputDataFieldTemp["options"]["file"]          = $inputDataDefinition["dafAttachFileOptions"];

                                            foreach($inputDataDefinition["dafTypOptions"] as $option=>$value){
                                                
                                                $inputDataFieldTemp["options"][$option]     = $value; 
                                            }

                                            $inputDataFieldTemp["formplace"]                = "statesActionFields" ;
                                            $inputDataFieldTemp["editAs"]                   = "FIELD" ;
                                            $inputDataFieldTemp["component"]                = (property_exists($inputDataDefinition["dafUiOptions"], "component")) ? $inputDataDefinition["dafUiOptions"]->component : null ;
                                            $inputDataFieldTemp["label"]                    = $inputDataDefinition["dafUiOptions"]->label;
                                            $inputDataFieldTemp["icon"]                     = $inputDataDefinition["dafUiOptions"]->icon;

                                            if($inputDataFieldTemp['type'] == "objectr" || $inputDataFieldTemp['type'] == "objectsr"){


                                                if(isset($inputDataFieldTemp['options']['model'])){

                                                    $modelDataIdNamesParams                 = array();
                                                    if(isset($inputDataFieldTemp['options']['hardfilters'])){

                                                        $modelDataIdNamesParams['hardfilters']  = $inputDataFieldTemp['options']['hardfilters'];
                                                    }

                                                    $modelDataIdNames                       = $this->getModelDataIdNames($inputDataFieldTemp['options']['model'], $modelDataIdNamesParams);
                                                    
                                                    $inputDataFieldTempData                 = array();
                                
                                                    foreach($modelDataIdNames['objects'] as $object){
                                
                                                        $inputDataFieldTempData[]           = array('label' => $object['name'], 'value' => $object['id'], 'image' => $object['image'], 'icon' => $object['icon']);
                                                    }
                                
                                                    $inputDataFieldTemp["options"]["data"]  = $fieldTempData;
                                                }
                                            }

                                            $inputDataFieldTemp["options"]["multiple"]      = false;

                                            if($inputDataFieldTemp['type'] == "objectsr"){

                                                $inputDataFieldTemp["options"]["multiple"]       = true;
                                            }

                                            $result["statesActionFields"][$toStateActionState][$inputDataFieldTemp['id']]      = $inputDataFieldTemp;
                                            $inputDataOrder++;
                                        }
                                    }
                                }
                            }

                            $modelData['statesOptions']->states[$stateIndex]->toStateActions = $toStateActionsTemp;
                        }                        

                        $fieldTemp["options"]["states"] = $modelData['statesOptions']->states;
                    }
                }
                
            }else{

                $fieldTemp["formplace"]     = (property_exists($definition["uiOptions"], "formPlace")) ? $definition["uiOptions"]->formPlace : "main" ;
                $fieldTemp["editAs"]        = (property_exists($definition["uiOptions"], "editAs")) ? $definition["uiOptions"]->editAs : "FIELD" ;
            }
            
            $fieldTemp["component"]     = (property_exists($definition["uiOptions"], "component")) ? $definition["uiOptions"]->component : null ;

            if(!$definition["isRelation"]){

                $fieldTemp["label"]         = $definition["uiOptions"]->label;
                $fieldTemp["icon"]          = $definition["uiOptions"]->icon;

                if($fieldTemp['type'] == "objectr" || $fieldTemp['type'] == "objectsr"){

                    if(isset($fieldTemp['options']['model']) && ( !isset($fieldTemp['options']['isAsync']) || $fieldTemp['options']['isAsync'] == false)){

                        
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
                    }

                    $fieldTemp["options"]["multiple"]           = false;

                    if($fieldTemp['type'] == "objectsr"){

                        $fieldTemp["options"]["multiple"]       = true;
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
            
            //TODO : Si hace falta recibir por parametro adicionales para el query
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

        foreach($p_dataSource->getDataRenderableDefinitions() as $field=>$definition){
            
            if($this->hasUrlParam($field)){

                $result[$field]     = array($this->getUrlParam($field));
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
    protected function getEditorAddData($p_data){

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

                    if(method_exists($this, "preProccessEditorAddData")){

                        $result         = $this->preProccessEditorAddData($result);
                    }
                }
            }
        }

        return $result;
    }

    protected function getEditorEditData($p_data){

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

                    if(method_exists($this, "preProccessEditorEditData")){

                        $result         = $this->preProccessEditorEditData($result);
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

        if($this->hasSession("user_loged") && $this->getSession("user_loged") == true){

            $userData               = $this->getSession("user_data");

            $user                   = array();
            $user['label']          = $userData['login'];
            $user['url']            = $this->getDI()->get('config')->main->url->base . "profile/";
            $user['avatar']         = $this->getDI()->get('config')->main->url->base . "avatar/sq100/" . $userData['avatar'] . ".jpg";

            $items                  = array();

            $userData['roles'][]    = $userData['role'];
            
            foreach($userData['roles'] as $role){
                
                if(isset($role['menus'])){

                    foreach($role['menus'] as $menu){

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
            }

            
            $sideMenuParams             = array();
            $sideMenuParams['user']     = $user;
            $sideMenuParams['items']    = $items;

            $sideMenu                   = new SideMenu($this->getDI());
            $this->placeComponent("side", $sideMenu, $sideMenuParams);
        }
    }

    protected function getUserRoles(){
        $result = false;

        if($this->hasSession("user_loged") && $this->getSession("user_loged") == true){

            $userData               = $this->getSession("user_data");
            
            $result                 = array_merge(array($userData['role']['id']), array_keys($userData['roles']));
        }

        return $result;
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
                }else{
                    
                    $this->setGlobal("user_id", $this->getSession("user_data")['id']);
                }
            }else{
                
                header("Location: " . $loginurl);
                exit();
            }
        }
    }
}
