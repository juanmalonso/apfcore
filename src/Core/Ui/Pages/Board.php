<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS

class Board extends VueUiService {

    public function mainAction($p_params){

        $this->accessControl(true);

        $this->setTitle("BOARD - Tablero Principal");

        $this->setViewVar("content", "Panel Principal de Prueba");
        
        
    }

    protected function generateSideMenu($p_params){

        
    }
}