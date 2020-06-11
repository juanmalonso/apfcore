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

class Portalv2 extends PortalPage {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();
        
        $this->setTitle($this->getLocal("title"));

        $this->generateSlider();

        //DESTINOS
        $destinos                       = new ImageCards($this->getDI());

        $destinosParams                 = array();
        $destinosParams['urlLinkMap']   = $this->getLocal("destinos.linkMap");
        $destinosParams['imgSrcMap']    = $this->getLocal("destinos.cardImgSrcMap");
        $destinosParams['data']         = $this->getTopDestinos();

        $this->addMainSection($destinos, $destinosParams);

        //PAQUETES
        $paquetes                       = new ContentCards($this->getDI());

        $paquetesParams                 = array();
        $paquetesParams['urlLinkMap']   = $this->getLocal("paquetes.linkMap");
        $paquetesParams['imgSrcMap']    = $this->getLocal("paquetes.cardImgSrcMap");
        $paquetesParams['data']         = $this->getTopPaquetes();

        $this->addMainSection($paquetes, $paquetesParams);
        
    }

    protected function generateSlider(){

        $slider                         = new Slider($this->getDI());

        $sliderParams                   = array();

        $this->placeComponent("slider", $slider, $sliderParams);
    }
}
