<?php
namespace Nubesys\Core\Ui\Components\Modules\Editors\Form;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class FormEditor extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();

        $this->placeVueCustomElement("hidden-field");
        $this->placeVueCustomElement("text-field");
        $this->placeVueCustomElement("integer-field");
        $this->placeVueCustomElement("date-field");
        $this->placeVueCustomElement("options-field");
        $this->placeVueCustomElement("switcher-field");
        $this->placeVueCustomElement("tags-field");
        $this->placeVueCustomElement("password-field");
        $this->placeVueCustomElement("schema-form-field");

        $newCustomFields        = array();
        
        if($this->hasLocal("customFields")){

            $customFields       = $this->getLocal("customFields");

            foreach($customFields as $field=>$fieldComponent){

                $componentParams                                = array();

                if(isset($fieldComponent['referenceName'])){

                    $componentParams['referenceName']           = $fieldComponent['referenceName'];
                }

                $this->placeVueCustomElement($fieldComponent['elementTag'], $fieldComponent['classPath'], $componentParams);

                $newCustomFields[$field]                        = $fieldComponent;
                $newCustomFields[$field]['camelizedTag']        = \Phalcon\Text::camelize($fieldComponent['elementTag']);
            }

        }

        $this->setViewVar('customFields', $newCustomFields);
        $this->setJsDataVar('customFields', array_keys($newCustomFields));
    }

    
}