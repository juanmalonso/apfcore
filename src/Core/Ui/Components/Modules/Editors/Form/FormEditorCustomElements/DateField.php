<?php
namespace Nubesys\Core\Ui\Components\Modules\Editors\Form\FormEditorCustomElements;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class DateField extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();

        $this->addJsSource("https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.js");
        $this->addCssSource("https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.css");
    }
}