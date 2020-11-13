<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Services\Service;
use Nubesys\Core\Ui\View\View;
use Phalcon\Mvc\View\Engine\Volt;
use Nubesys\Core\Register;

class UiService extends Service {

    protected $id;
    protected $view;

    protected $csssources;
    protected $jssources;
    protected $metatags;

    protected $snippedsk;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->setView(array());

        $this->view         = new View($p_di, $this->classDir . "/" . $this->className);

        $this->csssources   = new Register();
        $this->jssources    = new Register();
        $this->metatags    = new Register();

        $this->snippedsk    = array();

        $this->generateId();

        //GLOBAL SERVICE INSTANCE
        $this->getDI()->get('global')->set('service', $this);

        //INITIAL GLOBAL VARS
        $this->setGlobal("serviceId", $this->getId());
        $this->setGlobal("urlbase", $this->getDI()->get('config')->main->url->base);
        $this->setGlobal("appid", $this->getDI()->get('config')->main->id);

        //INITIALS VIEW VARS
        $this->setViewVar("id", $this->getId());
        $this->setViewVar("splashurl", $this->getDI()->get('config')->ui->splash->url);

        //INITIAL JS AND CSS RESOURCES
        //CSS
        $this->addCssSource("https://cdn.jsdelivr.net/npm/fomantic-ui@2.7.7/dist/semantic.min.css");

        //JS
        $this->addJsSource("https://cdn.jsdelivr.net/jquery/3.0.0/jquery.min.js");
        $this->addJsSource("https://cdn.jsdelivr.net/npm/underscore@1.9.1/underscore.min.js");
        $this->addJsSource("https://cdn.jsdelivr.net/npm/axios@0.18.0/dist/axios.min.js");
        $this->addJsSource("https://cdn.jsdelivr.net/npm/fomantic-ui@2.7.7/dist/semantic.min.js");
        $this->addJsSource("https://cdn.jsdelivr.net/momentjs/2.15.1/moment.min.js");
        $this->addJsSource("https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js");
    }

    //ID
    public function getId(){

        return $this->id;
    }

    private function generateId(){

        $pathPartes     = explode("\\", $this->getClassPath());

        $className      = strtolower(array_pop($pathPartes));

        $this->id = implode("-", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes)) . "-" . $className .  "-" . md5(microtime(true));
    }

    //LOCALSCOPE
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
        
        foreach($p_values as $key=>$value){
            
            $this->setLocal($key, $value);
        }
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

        return "uid/" . implode("_", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes));
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

    protected function strAppendViewVar($p_key, $p_value){

        $this->view->strAppend($p_key, $p_value);
    }

    //TITLE
    protected function setTitle($p_title){

        $this->view->set("pagetitle", $p_title);
    }

    //REDIRECTION
    protected function goToPage(){


    }

    protected function goToUrl($p_url){

        
    }

    //PAGE REMOTE CSS
    public function addCssSource($p_value){

        $key = md5($p_value);

        if(!$this->csssources->has($key)){

            $htmlCode = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $p_value . "\" />";

            $this->csssources->set($key, $htmlCode);
        }
    }

    protected function compileCssSources(){
        
        $this->view->strAppend("csssources", "");
        foreach($this->csssources->all() as $csssource){
            
            $this->view->strAppend("csssources", $csssource);
        }
    }

    //PAGE REMOTE JS
    public function addJsSource($p_value){

        $key = md5($p_value);

        if(!$this->jssources->has($key)){

            $htmlCode = "<script type=\"text/javascript\" src=\"" . $p_value . "\" ></script>";

            $this->jssources->set($key, $htmlCode);
        }
    }

    protected function compileJsSources(){

        $this->view->strAppend("jssources", "");
        foreach($this->jssources->all() as $jssource){

            $this->view->strAppend("jssources", $jssource);
        }
    }

    //PAGE META TAGS
    public function addMetaTag($p_name, $p_content){

        $key = md5($p_name);

        if(!$this->metatags->has($key)){

            $htmlCode = "<meta name=\"" . $p_name . "\" content=\"" . $p_content . "\" />";

            $this->metatags->set($key, $htmlCode);
        }
    }

    protected function compileMetaTags(){
        
        $this->view->strAppend("metatags", "");
        foreach($this->metatags->all() as $metatag){
            
            $this->view->strAppend("metatags", $metatag);
        }
    }

    //PAGE SNIPPETS
    protected function addSnippet($p_group, $p_id, $p_type, $p_code){

        $_code                  = "";

        if($p_code != ""){
            
            switch ($p_type) {
                case 'css':
                    
                    $_code          .= "\n/* START " . $p_id . " style */\n";
                    $_code          .= $p_code;
                    $_code          .= "\n/* END " . $p_id . " style */\n";

                    break;

                case 'js':
                    
                    $_code          .= "\n/* START " . $p_id . " script */\n";
                    $_code          .= $p_code;
                    $_code          .= "\n/* END " . $p_id . " script */\n";

                    break;

                case 'html':
                    
                    $_code          .= "\n<!-- START " . $p_id . " html -->\n";
                    $_code          .= $p_code;
                    $_code          .= "\n<!-- END " . $p_id . " html -->\n";

                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $this->view->strAppend($p_group, $_code);
        
    }

    //SNIPPETS
    protected function compileCssSnippets(){

        $this->addCssSnippet($this->getId(), $this->view->renderCode($this->view->getTemplate('styles')));
    }

    public function addCssSnippet($p_id, $p_code){

        $this->addSnippet("csssnippets", $p_id, "css", $p_code);
    }

    protected function compileJsSnippets(){

        $this->addJsSnippet($this->getId(), $this->view->renderCode($this->view->getTemplate('script')));
    }

    public function addJsSnippet($p_id, $p_code){
   
        $this->addSnippet("jssnippets", $p_id, "js", $p_code);
    }

    //COMPONENTS
    public function addJsComponent($p_id, $p_code){

        $this->addSnippet("jscomponents", $p_id, "js", $p_code);
    }

    protected function placeComponent($p_place, $p_instance, $p_params = array()){
        
        $this->setViewVar($p_place, $p_instance->doComponentRender($p_params, $this->getId()));
    }

    protected function appendComponent($p_place, $p_instance, $p_params = array()){
        
        $this->strAppendViewVar($p_place, $p_instance->doComponentRender($p_params, $this->getId()));
    }

    public function doPageRender($p_action, $p_params, $p_inherited = false){
        
        $this->setParams($p_params);
        
        $this->loadJsonTree();
        
        if(method_exists($this,$p_action)){

            $this->$p_action();
        }

        //TODO : MEJORAR LA LOGICA DE LAS TEMPLATES PARA HACER TEMPLATE GLOBAL Y CONTENT TEMPLATE
        $this->view->loadTemplates($this->getDI()->get('config')->main->view->template->templates);
        
        //COMPILE CSSSOURCES
        $this->compileCssSources();

        //COMPILE JSSOURCES
        $this->compileJsSources();

        //COMPILE METATAGS
        $this->compileMetaTags();

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