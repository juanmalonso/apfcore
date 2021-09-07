<?php
namespace Nubesys\Core\Ui\Components\App\Importer;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class Importer extends VueUiComponent {

    private $fields;

    public function mainAction(){

        $this->registerReference("importer");

        $this->setJsDataVar("referenceName", "importer");

        $this->setJsDataVar("datasource", $this->getLocal("datasource"));
        $this->setJsDataVar("form_action", $this->getLocal("form_action"));

        /*
        /*$this->addJsSource("https://cdn.jsdelivr.net/npm/semantic-ui-calendar@0.0.8/dist/calendar.min.js");
        $this->addCssSource("https://cdn.jsdelivr.net/npm/semantic-ui-calendar@0.0.8/dist/calendar.min.css");*/
        
        /*
        $this->addJsSource("https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.js");
        $this->addCssSource("https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.css");
        
        $this->setJsDataVar("token", $this->getLocal("token"));
        $this->setJsDataVar("datasource", $this->getLocal("datasource"));
        $this->setJsDataVar("fields", $this->getLocal("fields"));
        $this->setJsDataVar("fieldsGroups", $this->getLocal("fieldsGroups"));
        $this->setJsDataVar("objectData", $this->getLocal("data"));
        
        $this->setJsDataVar("message", false);
        if($this->hasLocal("message")){

            $this->setJsDataVar("message", $this->getLocal("message"));
        }


        $this->placeVueCustomElement("text-box");
        $this->placeVueCustomElement("password-box");
        $this->placeVueCustomElement("number-box");
        $this->placeVueCustomElement("switcher-box");
        $this->placeVueCustomElement("options-selector");
        $this->placeVueCustomElement("json-editor");
        $this->placeVueCustomElement("tags-box");
        $this->placeVueCustomElement("date-box");
        $this->placeVueCustomElement("image-selector");
        $this->placeVueCustomElement("avatar-selector");
        $this->placeVueCustomElement("schema-form");
        */
    }
}