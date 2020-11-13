<?php
namespace Nubesys\Core\Ui\Components\Modules\Manager\ManagerCustomElements;

use Nubesys\Core\Ui\Components\Modules\ModuleComponent;

class TableManagerHeader extends ModuleComponent {

    public function mainAction(){
        
        parent::mainAction();

        $this->placeVueCustomElement("data-value-cell", "Nubesys\\Core\\Ui\\Components\\Modules\\Selectors\\Table\\TableListCustomElements");
    }
}