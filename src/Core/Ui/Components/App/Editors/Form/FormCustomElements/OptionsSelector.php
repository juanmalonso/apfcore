<?php
namespace Nubesys\Core\Ui\Components\App\Editors\Form\FormCustomElements;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class OptionsSelector extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("items", array());
    }
}