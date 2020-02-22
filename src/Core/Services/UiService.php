<?php

namespace Nubesys\Core\Services;

use Nubesys\Core\Common;
use Nubesys\Core\Ui\View\View;
use Phalcon\Mvc\View\Engine\Volt;
use Nubesys\Core\Register;

class UiService extends Common {

    protected $id;
    protected $view;

    protected $csssources;
    protected $jssources;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->setView(array());

        $this->view         = new View($p_di, $this->classDir . "/" . $this->className);

        $this->csssources   = new Register();
        $this->jssources    = new Register();

        $this->generateId();

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

    protected function setTitle($p_title){

        $this->view->set("pagetitle", $p_title);
    }

    //REDIRECTION
    protected function goToPage(){


    }

    protected function goToUrl(){


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

    protected function placeComponent($p_place, $p_instance, $p_params){

        $this->setViewVar($p_place, $p_instance->doComponentRender($p_params, $this));
    }

    public function doPageRender($p_action, $p_params, $p_inherited = false){

        if(method_exists($this,$p_action)){

            $this->$p_action($p_params);
        }

        $this->view->loadTemplates($this->getDI()->get('config')->main->view->template->templates);

        //COMPILE CSSSOURCES
        $this->compileCssSources();

        //COMPILE JSSOURCES
        $this->compileJsSources();

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