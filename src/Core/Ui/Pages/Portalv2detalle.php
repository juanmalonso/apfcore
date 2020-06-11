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

class Portalv2detalle extends PortalPage {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        //SWIPER
        $this->addCssSource("https://unpkg.com/swiper/css/swiper.min.css");
        $this->addJsSource("https://unpkg.com/swiper/js/swiper.min.js");
        
        $this->setTitle($this->getLocal("title"));
        
        $detalle                                = new ContentDetail($this->getDI());

        $detalleParams                          = array();
        $detalleParams['sliderImgSrcMap']       = $this->getLocal("detalles.sliderImgSrcMap");
        $detalleParams['data']                  = $this->getContenidoDetalle();

        $this->addMainSection($detalle, $detalleParams);
        
    }
}
