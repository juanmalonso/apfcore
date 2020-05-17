<?php
namespace Nubesys\Core\Ui\Components\App\Editors\Form\FormCustomElements;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class JsonEditor extends VueUiComponent {

    public function mainAction(){
        
        $this->setJsDataVar("feditor", new \stdClass());
        $this->setJsDataVar("ceditor", new \stdClass());
        $this->setJsDataVar("mode", "code");
    }
}