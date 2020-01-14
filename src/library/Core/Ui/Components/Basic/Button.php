<?php

namespace Nubesys\Core\Ui\Components\Basic;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class Button extends VueUiComponent {

    public function mainAction($p_params){

        $this->setJsDataVar("buttonClass", "primary");
    }

    public function testService($p_params){

        $buttonsClasses = array("orange", "red", "black", "grey", "yellow", "olive", "green", "blue", "violet", "purple", "pink", "brown");

        sleep(3);

        $result = new \stdClass();
        $result->buttonClass = $buttonsClasses[rand(0, 11)];

        $this->setServiceSuccess($result);
    }

    public function dataService($p_params){

        $buttonsClasses = array("orange", "red", "black", "grey", "yellow", "olive", "green", "blue", "violet", "purple", "pink", "brown");

        sleep(3);

        $result = new \stdClass();
        $result->buttonClass = $buttonsClasses[rand(0, 11)];

        $this->setServiceSuccess($result);
    }
}