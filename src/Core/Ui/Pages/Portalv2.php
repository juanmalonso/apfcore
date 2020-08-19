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

        //EXPERIENCIAS
        $query                          = array();
        $query['rows']                  = 8;
        $query['filters']               = array();
        $query['filters']['parent']     = array("taxonomy_experiencias");

        $experiencias                           = new ImageCards($this->getDI());

        $experienciasParams                     = array();
        $experienciasParams['title']            = "Experiencias";
        $experienciasParams['urlLinkMap']       = $this->getLocal("experiencias.linkMap");
        $experienciasParams['bigImgSrcMap']     = $this->getLocal("experiencias.cardBigImgSrcMap");
        $experienciasParams['smallImgSrcMap']   = $this->getLocal("experiencias.cardSmallImgSrcMap");
        $experienciasParams['data']             = $this->getTopTaxonomies($query);
        $experienciasParams['size']             = "big";

        $this->addMainSection($experiencias, $experienciasParams);

        //PAQUETES
        $paquetes                       = new ContentCards($this->getDI());

        $paquetesParams                 = array();
        $paquetesParams['title']        = "Paquetes";
        $paquetesParams['urlLinkMap']   = $this->getLocal("paquetes.linkMap");
        $paquetesParams['imgSrcMap']    = $this->getLocal("paquetes.cardImgSrcMap");
        $paquetesParams['data']         = $this->getTopPaquetes();

        $this->addMainSection($paquetes, $paquetesParams);

        //DESTINOS
        $query                          = array();
        $query['filters']               = array();
        $query['filters']['parent']     = array("taxonomy_destinos");

        $destinos                       = new ImageCards($this->getDI());

        $destinosParams                     = array();
        $destinosParams['title']            = "Destinos";
        $destinosParams['urlLinkMap']       = $this->getLocal("destinos.linkMap");
        $destinosParams['bigImgSrcMap']     = $this->getLocal("destinos.cardBigImgSrcMap");
        $destinosParams['smallImgSrcMap']   = $this->getLocal("destinos.cardSmallImgSrcMap");
        $destinosParams['data']             = $this->getTopTaxonomies($query);
        $destinosParams['size']             = "big";

        //$this->addMainSection($destinos, $destinosParams);

        //ROBOTS
        $this->addMetaTag("robots", "index, follow");     
    }
}
