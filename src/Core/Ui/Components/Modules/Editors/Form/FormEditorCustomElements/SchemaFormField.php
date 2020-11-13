<?php
namespace Nubesys\Core\Ui\Components\Modules\Editors\Form\FormEditorCustomElements;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class SchemaFormField extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();
        
        $this->addJsSource($this->getGlobal('urlbase') . "web/js/jsoneditor.min.js");
        
        $this->setJsDataVar("jsoneditor", new \stdClass());

        $this->setJsDataVar("jsonfieldschanged", new \stdClass());
    }
}