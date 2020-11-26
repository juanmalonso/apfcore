<?php
namespace Nubesys\Core\Ui\Components\Modules\Selectors\Table;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class TableList extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();

        $this->placeVueCustomElement("filters", "Nubesys\\Core\\Ui\\Components\\Modules\\Filters");
        $this->placeVueCustomElement("search-box", "Nubesys\\Core\\Ui\\Components\\Modules\\Search");
        $this->placeVueCustomElement("data-value-cell");
    }
}