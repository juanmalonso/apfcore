<?php
namespace Nubesys\Core\Ui\Components\Modules\Boards\Kanban;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class Kanban extends ModuleComponent {

    public function mainAction(){

        parent::mainAction();

        $this->placeVueCustomElement("data-value-cell", "Nubesys\\Core\\Ui\\Components\\Modules\\Selectors\\Table\\TableListCustomElements");
    }
}