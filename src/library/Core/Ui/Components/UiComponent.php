<?php

namespace Nubesys\Core\Ui\Components;

use Nubesys\Core\Common;
use Nubesys\Core\Ui\View\View;
use Phalcon\Mvc\View\Engine\Volt;
use Nubesys\Core\Register;

class UiComponent extends Common {

    protected $id;
    protected $view;

    protected $csssources;
    protected $jssources;

    protected $pageService;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->setView(array());

        $this->view         = new View($p_di, $this->classDir . "/" . $this->className, false);

        $this->csssources   = new Register();
        $this->jssources    = new Register();

        $this->generateId();

        //INITIALS VIEW VARS
        $this->setViewVar("id", $this->getId());
    }

    //PAGE
    public function getPageService(){

        return $this->pageService;
    }

    public function setPageService($p_pageService){

        $this->pageService = $p_pageService;
    }

    //ID
    public function getId(){

        return $this->id;
    }

    private function generateId(){

        $pathPartes     = explode("\\", $this->getClassPath());

        $className      = strtolower(array_pop($pathPartes));

        $this->id = implode("-", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes)) . "-" . $className .  "-" . md5(time(true));
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

        $this->pageService->addCssSource($p_value);
    }

    //PAGE REMOTE JS
    public function addJsSource($p_value){

        $this->pageService->addJsSource($p_value);
    }

    //SNIPPETS
    protected function compileCssSnippets(){

        $this->addCssSnippet($this->getId(), $this->view->renderCode($this->view->getTemplate('styles')));
    }

    protected function addCssSnippet($p_id, $p_code){

        $this->pageService->addCssSnippet($p_id, $p_code);
    }

    protected function compileJsSnippets(){

        $this->addJsSnippet($this->getId(), $this->view->renderCode($this->view->getTemplate('script')));
    }

    protected function addJsSnippet($p_id, $p_code){
   
        $this->pageService->addJsSnippet($p_id, $p_code);
    }

    //COMPONENT
    public function addJsComponent($p_id, $p_code){

        $this->pageService->addJsComponent($p_id, $p_code);
    }

    //RENDER
    public function doComponentRender($p_params, $p_page, $p_inherited = false){

        $this->setPageService($p_page);

        if(method_exists($this,"mainAction")){

            $this->mainAction($p_params);
        }

        $this->view->loadTemplates($this->getDI()->get('config')->ui->defaulttemplates);

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

        $this->getDI()->get('responseObject')->setStatus($p_status);
    }

    public function setServiceInfo($p_info){

        $this->getDI()->get('responseObject')->setInfo($p_info);
    }

    public function setServiceData($p_data){

        $this->getDI()->get('responseObject')->setData($p_data);
    }

    public function setServiceDebug($p_debug){

        $this->getDI()->get('responseObject')->setDebug($p_debug);
    }

    public function setServiceError($p_message){

        $this->getDI()->get('responseObject')->setError($p_message);
    }

    public function setServiceSuccess($p_data){

        $this->getDI()->get('responseObject')->setSuccess($p_data);
    }

    public function doService($p_uiServiceName, $p_params){

        if(method_exists($this, $p_uiServiceName)){

            $this->$p_uiServiceName($p_params);
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
                "compiledPath"      => $this->getDI()->get('config')->volt->compilepath,
                "compiledExtension" => $this->getDI()->get('config')->volt->compileext
            )
        );

        $this->getDI()->set('voltService', $volt, true);
    }
}