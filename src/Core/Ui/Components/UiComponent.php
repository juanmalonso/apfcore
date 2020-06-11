<?php

namespace Nubesys\Core\Ui\Components;

use Nubesys\Core\Common;
use Nubesys\Core\Ui\View\View;
use Phalcon\Mvc\View\Engine\Volt;
use Nubesys\Core\Register;

class UiComponent extends Common {

    protected $id;
    protected $serviceId;
    protected $parentComponentId;

    protected $view;

    protected $csssources;
    protected $jssources;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->setView(array());

        $this->view         = new View($p_di, $this->classDir . "/" . $this->className, false);

        $this->csssources   = new Register();
        $this->jssources    = new Register();

        $this->generateId();

        //SERVICE ID
        $this->setServiceId($this->getGlobal("serviceId"));

        //INITIALS VIEW VARS
        $this->setViewVar("id", $this->getId());
    }

    //COMPONENTS REFERENCE NAMES
    protected function registerReference($p_name){

        $globalScopeKey     = "references.scope";

        $this->initScope($globalScopeKey);

        $this->getScope($globalScopeKey)->set($p_name, $this->getId());
    }

    //ID
    public function getHashId(){

        return md5($this->id);
    }

    public function getId(){

        return $this->id;
    }

    //SERVICE ID
    public function getServiceId(){

        return $this->serviceId;
    }

    public function setServiceId($p_serviceId){

        $this->serviceId = $p_serviceId;
    }

    //PARENT ID
    public function getParentId(){

        return $this->parentComponentId;
    }

    public function setParentId($p_parentComponentId){

        $this->parentComponentId = $p_parentComponentId;
    }

    private function generateId(){

        $pathPartes     = explode("\\", $this->getClassPath());

        $className      = strtolower(array_pop($pathPartes));

        $this->id = implode("-", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes)) . "-" . $className .  "-" . md5(microtime(true));
    }

    //LOCAL SCOPE
    protected function hasLocal($p_key){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->has($p_key);
        }else{

            return $this->getScope($globalScopeKey)->hasDot($p_key);
        }
    }

    protected function getLocal($p_key){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->get($p_key);
        }else{

            return $this->getScope($globalScopeKey)->getDot($p_key);
        }
    }

    protected function allLocal(){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    protected function setLocal($p_key, $p_value){

        $globalScopeKey     = "local." . $this->getId() . ".scope";

        $this->initScope($globalScopeKey);

        if(!strpos($p_key, '.')){

            return $this->getScope($globalScopeKey)->set($p_key, $p_value);
        }else{

            return $this->getScope($globalScopeKey)->setDot($p_key, $p_value);
        }
    }

    protected function setAllLocals($p_values){
        
        //if(! is_null($p_values)){
            
            foreach($p_values as $key=>$value){
                
                $this->setLocal($key, $value);
            }
        //}
    }

    //PARENT SCOPE
    protected function hasParent($p_key){

        $globalScopeKey     = "local." . $this->getParentId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getParent($p_key){

        $globalScopeKey     = "local." . $this->getParentId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function allParent(){

        $globalScopeKey     = "local." . $this->getParentId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    //SERVICE SCOPE
    protected function hasService($p_key){
        
        $globalScopeKey     = "local." . $this->getServiceId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->has($p_key);
    }

    protected function getService($p_key){

        $globalScopeKey     = "local." . $this->getServiceId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->get($p_key);
    }

    protected function allService(){

        $globalScopeKey     = "local." . $this->getServiceId() . ".scope";

        $this->initScope($globalScopeKey);

        return $this->getScope($globalScopeKey)->all();
    }

    //PARAMS
    protected function setParams($p_params){

        foreach($p_params as $key=>$value){

            switch ($key) {
                case 'URL':
                    $this->setAllUrlParams($value);
                    break;

                case 'GET':
                    $this->setAllGetParams($value);
                    break;

                case 'POST':
                    $this->setAllPostParams($value);
                    break;

                case 'FILES':
                    $this->setAllFilesParams($value);
                    break;
                
                case 'JSON':
                    $this->setJsonParam($value);
                    break;

                default:
                    break;
            }
        }
    }

    //URL CLASS PATH
    protected function getUrlClassPath(){

        $pathPartes     = explode("\\", $this->getClassPath());

        return "uid/" . implode("_", array_map(function ($e){ return \Phalcon\Text::uncamelize($e, "-");}, $pathPartes));
    }

    //VIEW VARS
    protected function setViewVar($p_key, $p_value){

        $this->view->set($p_key, $p_value);
    }

    protected function hasViewVar($p_key){

        return $this->view->has($p_key);
    }

    protected function getViewVar($p_key){

        return $this->view->get($p_key);
    }

    //PAGE REMOTE CSS
    public function addCssSource($p_value){

        $this->getDI()->get('global')->get('service')->addCssSource($p_value);
    }

    //PAGE REMOTE JS
    public function addJsSource($p_value){

        $this->getDI()->get('global')->get('service')->addJsSource($p_value);
    }

    //SNIPPETS
    protected function compileCssSnippets(){

        if($this->view->hasTemplate('styles')){
            
            $this->addCssSnippet($this->getId(), $this->view->renderCode($this->view->getTemplate('styles')));
        }
    }

    protected function addCssSnippet($p_id, $p_code){
        
        $this->getDI()->get('global')->get('service')->addCssSnippet($p_id, $p_code);
    }

    protected function compileJsSnippets(){

        if($this->view->hasTemplate('script')){
        
            $this->addJsSnippet($this->getId(), $this->view->renderCode($this->view->getTemplate('script')));
        }
    }

    protected function addJsSnippet($p_id, $p_code){
        
        $this->getDI()->get('global')->get('service')->addJsSnippet($p_id, $p_code);
    }

    //COMPONENT
    public function addJsComponent($p_id, $p_code){

        $this->getDI()->get('global')->get('service')->addJsComponent($p_id, $p_code);
    }

    protected function placeComponent($p_place, $p_instance, $p_params = array()){

        $this->setViewVar($p_place, $p_instance->doComponentRender($p_params, $this->getId()));
    }

    //RENDER
    public function doComponentRender($p_params, $p_parent, $p_inherited = false){

        $this->setParentId($p_parent);
        
        $this->setAllLocals($p_params);

        $this->loadJsonTree();

        if(method_exists($this,"mainAction")){

            $this->mainAction($p_params);
        }

        $this->view->loadTemplates($this->getDI()->get('config')->main->view->template->templates);

        //RENDER PAGE CSSSNIPPEDS
        $this->compileCssSnippets();

        //RENDER PAGE JSSNIPPEDS
        $this->compileJsSnippets();

        if(!$p_inherited){

            return $this->view->renderTemplate("template");
        }else{

            return 0;
        }
    }

    //SERVICES (AJAX)
    protected function setServiceStatus($p_status){

        $this->getDI()->get("responseManager")->setStatus($p_status);
    }

    public function setServiceInfo($p_info){

        $this->getDI()->get("responseManager")->setInfo($p_info);
    }

    public function setServiceData($p_data){

        $this->getDI()->get("responseManager")->setData($p_data);
    }

    public function setServiceDebug($p_debug){

        $this->getDI()->get("responseManager")->setDebug($p_debug);
    }

    public function setServiceError($p_message){

        $this->getDI()->get("responseManager")->setError($p_message);
    }

    public function setServiceSuccess($p_data){

        $this->getDI()->get("responseManager")->setSuccess($p_data);
    }

    public function doService($p_uiServiceName, $p_params){
        
        $this->setParams($p_params);

        $this->loadJsonTree();
        
        if(method_exists($this, $p_uiServiceName)){

            $this->$p_uiServiceName();
        }

        return 0;
    }

    private function setView($p_vars){

        $view = new \Phalcon\Mvc\View\Simple();
        //TODO : Que sea configurable las extensiones de volt
        $view->registerEngines(array(
            ".phtml"    => 'voltService',
            ".js"       => 'voltService',
            ".css"      => 'voltService'
        ));

        $this->getDI()->set('view', $view, true);

        $volt = new \Phalcon\Mvc\View\Engine\Volt($this->getDI()->get('view'), $this->getDI());
        
        $volt->setOptions(
            array(
                "compiledPath"      => $this->getDI()->get('config')->main->view->compile->path,
                "compiledExtension" => $this->getDI()->get('config')->main->view->compile->extension
            )
        );

        $this->getDI()->set('voltService', $volt, true);
    }
}