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

class Portalv2lista extends PortalPage {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        //PAQUETES
        $paquetes                       = new ContentCards($this->getDI());

        $paquetesParams                 = array();
        $paquetesParams['urlLinkMap']   = $this->getLocal("paquetes.linkMap");
        $paquetesParams['imgSrcMap']    = $this->getLocal("paquetes.cardImgSrcMap");

        $filters                        = array();

        //DESTINOS FILTER
        if($this->hasUrlParam("destino")){

            $filters['destino']        = (array)$this->getUrlParam("destino");
        }
        
        //CATEGORIAS FILTER

        //CIUDADES FILTER

        //ECETERA FILTER

        $query                          = array();
        if(count($filters) > 0){

            $query['filters']           = $filters;
        }

        $paquetesParams['data']         = $this->getTopPaquetes($query);

        $this->addMainSection($paquetes, $paquetesParams);
        
    }
}
