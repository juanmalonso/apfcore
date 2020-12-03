<?php
namespace Nubesys\Core\Ui\Components\Modules\Manager\ManagerCustomElements;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class ToStateAction extends ModuleComponent {

    public function mainAction(){
        
        parent::mainAction();
        
        $this->placeVueCustomElement("hidden-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("text-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("integer-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("date-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("options-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("switcher-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("tags-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("password-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");
        $this->placeVueCustomElement("schema-form-field", "Nubesys\\Core\\Ui\\Components\\Modules\\Editors\\Form\\FormEditorCustomElements");

        $this->setJsDataVar("maxzindex", 1000);
    }
}