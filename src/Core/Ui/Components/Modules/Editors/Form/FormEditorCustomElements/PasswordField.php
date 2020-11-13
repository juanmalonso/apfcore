<?php
namespace Nubesys\Core\Ui\Components\Modules\Editors\Form\FormEditorCustomElements;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class PasswordField extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();

        $this->addJsSource("https://cdn.jsdelivr.net/crypto-js/3.1.2/rollups/sha1.js");
    }
}