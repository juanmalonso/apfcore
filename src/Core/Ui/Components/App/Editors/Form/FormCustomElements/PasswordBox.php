<?php
namespace Nubesys\Core\Ui\Components\App\Editors\Form\FormCustomElements;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class PasswordBox extends VueUiComponent {

    public function mainAction(){
        
        $this->addJsSource("https://cdn.jsdelivr.net/crypto-js/3.1.2/rollups/sha1.js");
    }
}