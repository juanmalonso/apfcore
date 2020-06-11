<?php
namespace Nubesys\Core\Ui\Components\App\Editors\Form\FormCustomElements;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class JsonEditor extends VueUiComponent {

    public function mainAction(){

        $this->addJsSource("https://cdn.jsdelivr.net/npm/jsoneditor@5.24.0/dist/jsoneditor.min.js");
        $this->addCssSource("https://cdn.jsdelivr.net/npm/jsoneditor@5.24.0/dist/jsoneditor.min.css");
        
        $this->setJsDataVar("editor", new \stdClass());
        $this->setJsDataVar("mode", "code");
    }
}