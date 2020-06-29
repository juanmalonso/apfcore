<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//PAGE
use Nubesys\Core\Ui\Pages\PortalPage;

//COMPONENTS
use Nubesys\Core\Ui\Components\Portal\V2\Slider;
use Nubesys\Core\Ui\Components\Portal\V2\ImageCards;
use Nubesys\Core\Ui\Components\Portal\V2\ContentCards;
use Nubesys\Core\Ui\Components\Portal\V2\ContentDetail;
use Nubesys\Core\Ui\Components\Portal\V2\ReservaForm;

class Portalv2reserva extends PortalPage {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();
        
        $this->setTitle($this->getLocal("title"));
        
        $reserva                                = new ReservaForm($this->getDI());

        $reservaParams                          = array();
        $reservaParams['imgSrcMap']             = $this->getLocal("detalles.sliderImgSrcMap");
        $reservaParams['data']                  = $this->getContenidoDetalle();

        $this->addMainSection($reserva, $reservaParams);
        
    }
}
