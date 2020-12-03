<?php
namespace Nubesys\Core\Ui\Components\Modules\Viewers\Table;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class TableViewer extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();
        
        $this->placeVueCustomElement("data-value-cell", "Nubesys\\Core\\Ui\\Components\\Modules\\Selectors\\Table\\TableListCustomElements");

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