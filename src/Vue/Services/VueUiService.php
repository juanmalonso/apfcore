<?php
namespace Nubesys\Vue\Services;

use Nubesys\Core\Services\UiService;

class VueUiService extends UiService {

    protected $vuetag;
    protected $jsdata;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->jsdata                   = new \stdClass();

        $this->setViewVar("tag", $this->getId());
        $this->setJsDataVar("id", $this->getId());
        $this->setJsDataVar("tag", $this->getId());
        $this->compileJsDataVar();
        //INITIAL JS AND CSS RESOURCES
        //CSS

        //JS
        $this->addJsSource("https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js");
    }

    //JSDATA REFERENCESNAMES
    protected function setJsReferencesNamesDataVar(){

        $globalScopeKey     = "references.scope";

        $this->initScope($globalScopeKey);

        $this->setJsDataVar("nameReferences", $this->getScope($globalScopeKey)->all());
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

        $this->addSnippet("jssnippets", $p_id, "js", $p_code);
    }

    protected function compileVueMixins(){

        $this->addVueMixin($this->getId(), $this->view->renderCode($this->view->getTemplate('mixins')));
    }

    //XTEMPLATE
    public function addVueXtemplate($p_id, $p_code){

        $this->addSnippet("xtemplates", $p_id, "html", $p_code);
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

    public function doPageRender($p_action, $p_params, $p_inherited = false){

        parent::doPageRender($p_action, $p_params, true);

        $this->setJsReferencesNamesDataVar();

        $this->loadVueTemplates();

        $this->compileVueMixins();

        $this->compileVueComponent();

        $this->compileVueXtemplate($this->view->renderCode($this->getViewVar("content")));

        $this->setViewVar("content", "<" . $this->getId() . " id=\"" . $this->getId() . "\"></" . $this->getId() . ">");

        if(!$p_inherited){

            return $this->view->renderTemplate("template");
        }else {

            return 0;
        }
    }
}