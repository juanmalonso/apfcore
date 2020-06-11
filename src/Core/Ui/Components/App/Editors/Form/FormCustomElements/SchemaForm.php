<?php
namespace Nubesys\Core\Ui\Components\App\Editors\Form\FormCustomElements;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class SchemaForm extends VueUiComponent {

    public function mainAction(){
        
        $this->addJsSource($this->getGlobal('urlbase') . "web/js/jsoneditor.min.js");
        
        $this->setJsDataVar("jsoneditor", new \stdClass());

        $this->setJsDataVar("jsonfieldschanged", new \stdClass());
    }
}