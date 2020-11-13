<?php
namespace Nubesys\Core\Ui\Components\Modules\Selectors\Table;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class TableList extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();

        $this->placeVueCustomElement("data-value-cell");
    }
}