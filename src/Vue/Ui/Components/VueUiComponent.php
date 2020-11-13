<?php
namespace Nubesys\Vue\Ui\Components;

use Nubesys\Core\Ui\Components\UiComponent;
use Nubesys\Core\Register;

class VueUiComponent extends UiComponent {

    protected $elementsGlobalVars;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->jsdata                   = new \stdClass();

        $this->setTag($this->generateTag());

        $this->elementsGlobalVars       = array();
        
        $this->setViewVar("subcomponents", "{}");
        
        $this->setJsDataVar("id", $this->getId());
        $this->setJsDataVar("basepath", $this->getDI()->get('config')->main->url->base);
        $this->setJsDataVar("appid", $this->getDI()->get('config')->main->id);
        $this->setJsDataVar("serviceId", $this->serviceId);
        $this->setJsDataVar("dataCache", new \stdClass());
        
        $this->setJsDataVar("path", $this->getUrlClassPath());
        //var_dump();
        //exit();

        $this->compileJsDataVar();
    }

    private function generateTag(){

        $pathPartes     = explode("\\", $this->getClassPath());

        $className      = strtolower(array_pop($pathPartes));

        return          implode("-", array_map(function ($e){ return \Phalcon\Text::uncamelize($e);}, $pathPartes)) . "-" . $className;
    }

    //TAG
    public function setTag($p_tag){

        $this->setViewVar("tag", $p_tag);
        $this->setJsDataVar("tag", $p_tag);
    }

    public function getTag(){

        return $this->getViewVar("tag");
    }

    //VUEJSDATA
    protected function setJsDataVar($p_key, $p_value){

        $this->jsdata->$p_key = $p_value;

        $this->compileJsDataVar();
    }

    protected function compileJsDataVar(){

        $this->setViewVar("jsdata", json_encode($this->jsdata));
    }

    protected function getJsDataVar($p_key){

        return $this->jsdata->$p_key;
    }

    //MIXINS
    protected function compileVueMixins(){

        if($this->view->hasTemplate('mixins')){

            $this->addVueMixin($this->getId(), $this->view->renderCode($this->view->getTemplate('mixins')));
        }
    }

    public function addVueMixin($p_id, $p_code){

        $this->addJsSnippet($p_id, $p_code);
    }

    //XTEMPLATE
    protected function compileVueXtemplate($p_content, $p_id){

        $xtemplateCode = "\n<script type='text/x-template' id='" . $p_id . "-template'>\n";

        $xtemplateCode .= "<div>" . $p_content . "</div>";

        $xtemplateCode .= "\n</script>\n";

        $this->addVueXtemplate($p_id, $xtemplateCode);
    }

    public function addVueXtemplate($p_id, $p_code){

        $this->getDI()->get('global')->get('service')->addVueXtemplate($p_id, $p_code);
    }

    //COMPONENT
    protected function compileVueComponent(){

        if($this->view->hasTemplate('component')){
            
            $this->addJsComponent($this->getId(), "var _" . $this->getHashId() . " = " . $this->view->renderCode($this->view->getTemplate('component')));
        }
    }

    //CUSTOM ELEMENTS
    protected function placeVueCustomElement($p_tag, $p_classPath = null, $p_params = array()){
        
        $camelizedTag                   = \Phalcon\Text::camelize($p_tag);

        if($p_classPath != null){

            $subElementClassPath        = $p_classPath . "\\" . $camelizedTag;
        }else{

            $subElementClassPath        = $this->getClassPath() . "CustomElements\\" . $camelizedTag;
        }
        
        $instance                   = new $subElementClassPath($this->getDI());
        
        if(class_exists($subElementClassPath)){

            $instance                   = new $subElementClassPath($this->getDI());

            $this->setViewVar("Element" . $camelizedTag, $instance->getId());

            $this->elementsGlobalVars["_" . $instance->getHashId()]   = $instance->getId();

            $instance->doElementRender($p_params, $this->getId(),true);
        }

        $vars = array();

        foreach($this->elementsGlobalVars as $var=>$tag){

            $vars[] = "'" . $tag . "' : " . $var;
        }
        
        $this->setViewVar("subcomponents", "{" . implode(", ",$vars) . "}");
    }

    //LOAD VUE TEMPLATES
    protected function loadVueTemplates(){

        $vueTemplates = array();

        $vueMixinsTemplate                  = new \stdClass();
        $vueMixinsTemplate->name            = "mixins";
        $vueMixinsTemplate->extension       = "js";
        $vueTemplates[]                     = $vueMixinsTemplate;
        
        $vueComponentTemplate               = new \stdClass();
        $vueComponentTemplate->name         = "component";
        $vueComponentTemplate->extension    = "js";
        $vueTemplates[]                     = $vueComponentTemplate;

        $this->view->loadTemplates($vueTemplates);
    }

    public function doElementRender($p_params, $p_parent){

        parent::doComponentRender($p_params, $p_parent, true);

        $this->setJsDataVar("parentId", $this->parentComponentId);

        $this->loadVueTemplates();

        $this->compileVueMixins();

        $this->compileVueComponent();

        $this->compileVueXtemplate($this->view->renderTemplate("template"), $this->getId());
    }

    public function doComponentRender($p_params, $p_parent, $p_inherited = false){

        parent::doComponentRender($p_params, $p_parent, true);

        $this->setJsDataVar("parentId", $this->parentComponentId);

        $this->loadVueTemplates();

        $this->compileVueMixins();

        $this->compileVueComponent();

        $this->compileVueXtemplate($this->view->renderTemplate("template"), $this->getId());

        if(!$p_inherited){

            return "<" . $this->getId() . " id=\"" . $this->getId() . "\"></" . $this->getId() . ">";
        }else {

            return 0;
        }
    }
}