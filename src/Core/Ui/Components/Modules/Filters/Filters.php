<?php
namespace Nubesys\Core\Ui\Components\Modules\Filters;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class Filters extends ModuleComponent {

    public function mainAction(){
        
        parent::mainAction();

        $this->placeVueCustomElement("dropdown-filter");
    }
}