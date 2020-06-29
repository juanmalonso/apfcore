<?php
namespace Nubesys\Core\Ui\Components\App\Viewer\Table;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class TableViewer extends VueUiComponent {

    private $fields;

    public function mainAction(){

        $this->registerReference("viewer");
        
        /*$this->addJsSource("https://cdn.jsdelivr.net/npm/semantic-ui-calendar@0.0.8/dist/calendar.min.js");
        $this->addCssSource("https://cdn.jsdelivr.net/npm/semantic-ui-calendar@0.0.8/dist/calendar.min.css");*/
        
        /*$this->addJsSource("https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.js");
        $this->addCssSource("https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.css");*/
        
        $this->setJsDataVar("datasource", $this->getLocal("datasource"));
        $this->setJsDataVar("fields", $this->getLocal("fields"));
        $this->setJsDataVar("fieldsGroups", $this->getLocal("fieldsGroups"));
        $this->setJsDataVar("objectData", $this->getLocal("data"));
        
        $this->setJsDataVar("message", false);

        if($this->hasLocal("message")){

            $this->setJsDataVar("message", $this->getLocal("message"));
        }
    }
}