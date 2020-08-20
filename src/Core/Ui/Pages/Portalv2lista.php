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
        $paquetesParams['title']        = "Resultados de búsqueda";

        $sliderParams                   = array();
        $query                          = array();
        
        $filters                        = array();
        
        if($this->hasUrlParam("keyword")){

            $query['keyword']           = $this->getUrlParam("keyword");
            $sliderParams['keyword']    = $this->getUrlParam("keyword");
        }

        if($this->hasUrlParam("paises")){

            $filters['pais']            = (array)$this->getUrlParam("paises");
            $sliderParams['paises']     = $this->getUrlParam("paises");
        }

        if($this->hasUrlParam("mes")){

            $filters['mes']             = (array)$this->getUrlParam("mes");
            $sliderParams['mes']        = $this->getUrlParam("mes");
        }

        //CATEGORIAS FILTER
        /*
        if($this->hasUrlParam("categorias")){

            $taxonomyIdName             = $this->getTaxonomyIdName($this->getUrlParam("categorias")[0]);

            $filters['experiencias']    = (array)$this->getUrlParam("categorias");
            $paquetesParams['title']    = "<span style='color:#898A8D'>Destinos / </span>" . $taxonomyIdName['name'];
        }
        */

        //DESTINOS FILTER
        /*
        if($this->hasUrlParam("destino")){

            $taxonomyIdName             = $this->getTaxonomyIdName($this->getUrlParam("destino"));

            $filters['experiencias']    = (array)$this->getUrlParam("experiencias");
            $paquetesParams['title']    = "<span style='color:#898A8D'>Destinos / </span>" . $taxonomyIdName['name'];
        }
        */

        //EXPERIENCIAS FILTER
        if($this->hasUrlParam("experiencias")){

            $taxonomyIdName             = $this->getTaxonomyIdName($this->getUrlParam("experiencias"));

            $filters['experiencias']    = (array)$this->getUrlParam("experiencias");
            $paquetesParams['title']    = "<span style='color:#898A8D'>Experiencias / </span>" . $taxonomyIdName['name'];
        }
        
        //CATEGORIAS FILTER

        //CIUDADES FILTER

        //ECETERA FILTER

        if(count($filters) > 0){

            $query['filters']           = $filters;
        }

        $paquetesParams['data']         = $this->getTopPaquetes($query);
        
        $this->generateSlider($sliderParams);
        $this->addMainSection($paquetes, $paquetesParams);

        //ROBOTS
        $this->addMetaTag("robots", "index, follow");  
        
    }
}
