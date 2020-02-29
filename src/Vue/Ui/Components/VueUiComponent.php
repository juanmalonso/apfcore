<?php
namespace Nubesys\Vue\Ui\Components;

use Nubesys\Core\Ui\Components\UiComponent;

class VueUiComponent extends UiComponent {

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->jsdata                   = new \stdClass();

        $this->setViewVar("tag", $this->getId());
        $this->setJsDataVar("id", $this->getId());
        $this->setJsDataVar("tag", $this->getId());
        $this->setJsDataVar("basepath", $this->getDI()->get('config')->main->url->base);
        
        $this->setJsDataVar("path", $this->getUrlClassPath());
        //var_dump();
        //exit();

        $this->compileJsDataVar();
    }

    //VUEJSDATA
    function setJsDataVar($p_key, $p_value){

        $this->jsdata->$p_key = $p_value;

        $this->compileJsDataVar();
    }

    function compileJsDataVar(){

        $this->setViewVar("jsdata", json_encode($this->jsdata));
    }

    function getJsDataVar($p_key){

        return $this->jsdata->$p_key;
    }

    //MIXINS
    public function addVueMixin($p_id, $p_code){

        $this->addJsSnippet($p_id, $p_code);
    }

    protected function compileVueMixins(){

        $this->addVueMixin($this->getId(), $this->view->renderCode($this->view->getTemplate('mixins')));
    }

    //XTEMPLATE
    public function addVueXtemplate($p_id, $p_code){

        $this->getGlobal("service")->addVueXtemplate($p_id, $p_code);
    }

    protected function compileVueXtemplate($p_content){

        $xtemplateCode = "\n<script type='text/x-template' id='" . $this->getId() . "-template'>\n";

        $xtemplateCode .= "<div>" . $p_content . "</div>";

        $xtemplateCode .= "\n</script>\n";

        $this->addVueXtemplate($this->getId(), $xtemplateCode);
    }

    //COMPONENT
    protected function compileVueComponent(){

        $this->addJsComponent($this->getId(), $this->view->renderCode($this->view->getTemplate('component')));
    }

    //LOAD VUE TEMPLATES
    protected function loadVueTemplates(){

        $this->view->setTemplate("mixins","");
        $this->view->setTemplate("component","");

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

    public function doComponentRender($p_params, $p_parent, $p_inherited = false){

        parent::doComponentRender($p_params, $p_parent, true);

        $this->loadVueTemplates();

        $this->compileVueMixins();

        $this->compileVueComponent();

        $this->compileVueXtemplate($this->view->renderTemplate("template"));

        if(!$p_inherited){

            return "<" . $this->getId() . " id=\"" . $this->getId() . "\"></" . $this->getId() . ">";
        }else {

            return 0;
        }
    }
}