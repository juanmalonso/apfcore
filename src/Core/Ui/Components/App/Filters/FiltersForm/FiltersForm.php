<?php
namespace Nubesys\Core\Ui\Components\App\Filters\FiltersForm;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class FiltersForm extends VueUiComponent {

    public function mainAction(){

        $this->registerReference("filters");
        
        $this->setJsDataVar("datasource", $this->getLocal("datasource"));
        $this->setJsDataVar("filters", $this->getLocal("filters"));
        $this->setJsDataVar("keyword", \urldecode($this->getLocal("keyword")));
    }
}