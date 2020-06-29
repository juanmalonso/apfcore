<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//COMPONENTS
use Nubesys\Core\Ui\Components\Portal\V2\TopBar;
use Nubesys\Core\Ui\Components\Portal\V2\Footer;

//DATA SOURCE
use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Table as TableDataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Custom as CustomDataSource;

class PortalPage extends VueUiService {

    //MAIN ACTION
    public function mainAction(){

        $this->setTitle($this->getLocal("title"));

        //DOSIS
        $this->addCssSource("https://fonts.googleapis.com/css?family=Dosis&display=swap");

        $this->generateTopBar();
        $this->generateFooter();
    }

    protected function generateTopBar(){

        $topBar                         = new TopBar($this->getDI());

        $topBarParams                   = array();

        $this->placeComponent("top", $topBar, $topBarParams);
    }

    protected function generateFooter(){

        $footer                         = new Footer($this->getDI());

        $footerParams                   = array();
        
        $this->placeComponent("bottom", $footer, $footerParams);
    }

    protected function addMainSection($p_component, $p_componentParams){

        $this->appendComponent("main", $p_component, $p_componentParams);
    }

    public function getContenidoDetalle(){

        $result             = false;
        
        if($this->hasUrlParam(5)){

            $contenidoId = $this->getUrlParam(5);

            $dataSourceOptions              = array();
            $dataSourceOptions['model']     = "contenidoturismo";

            $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));
            
            $queryResul                     = $dataSource->getData($contenidoId);
            
            if(\is_array($queryResul) && isset($queryResul['_id'])){

                $result                     = array();

                $result['id']               = $queryResul['_id'];
                $result['label']            = $queryResul['name'];
                $result['description']      = $queryResul['description'];
                $result['imagen']           = $queryResul['imagen'];
                $result['imagenes']         = $queryResul['imagenes'];
                $result['observaciones']    = $queryResul['observaciones'];
                $result['promocion']        = $queryResul['promocion'];
                $result['travel_period']    = $queryResul['travel_period'];
                $result['booking_period']   = $queryResul['booking_period'];
                
                //DESTINO
                $result['destino']          = $this->getTaxonomyIdName($queryResul['destino']);

                //TEMPORADA
                $result['destino']          = $this->getTaxonomyIdName($queryResul['temporada']);


                //SERVICIOS
                $result['servicios']        = array();

                foreach($queryResul['servicios'] as $servicio){


                    $result['servicios'][$servicio]    = $this->getTaxonomyIdName($servicio);
                }

                //CATEGORIAS
                $result['categorias']        = array();

                foreach($queryResul['categorias'] as $categoria){


                    $result['categorias'][$categoria]    = $this->getTaxonomyIdName($categoria);
                }

                //PRECIO
                $result['moneda']                           = $queryResul['moneda'];

                if($queryResul['precio'] == ""){

                    $result['preciodesde']      = false;

                    $result['precio']           = "";

                    foreach($queryResul['variaciones']->tabla as $variacion){
                        
                        if($result['precio'] == ""){

                            $result['precio']   = $variacion->col2;
                            
                            //COL 3
                            if($variacion->col3 > 0 && $variacion->col3 < $result['precio']){

                                $result['precio']   = $variacion->col3;
                            }
                            
                            $result['preciodesde']  = true;
                        }else{
                            
                            //COL 2
                            if($variacion->col2 > 0 && $variacion->col2 < $result['precio']){

                                $result['precio']   = $variacion->col2;
                            }

                            //COL 3
                            if($variacion->col3 > 0 && $variacion->col3 < $result['precio']){

                                $result['precio']   = $variacion->col3;
                            }

                            $result['preciodesde']  = true;
                        }
                    }
                }else{

                    $result['preciodesde']      = false;
                    $result['precio']           = $queryResul['precio'];
                }

                $result['variaciones']               = $queryResul['variaciones'];
            }
        }
        
        return $result;
    }

    public function getTopPaquetes($p_params = array()){

        $result             = false;

        $dataSourceOptions              = array();
        $dataSourceOptions['model']     = "contenidoturismo";

        $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $query                          = array();
        $query['page']                  = (isset($p_params['page'])) ? $p_params['page'] : 1;
        $query['rows']                  = (isset($p_params['rows'])) ? $p_params['rows'] : 16;

        if(isset($p_params['filters'])){

            $query['filters']           = $p_params['filters'];
        }
        
        if(isset($p_params['orders'])){

            $query['orders']           = $p_params['orders'];
        }
        
        $queryResul                     = $dataSource->getData($query);
        
        if(\is_array($queryResul) && isset($queryResul['objects'])){

            $result                     = array();
            
            foreach($queryResul['objects'] as $paqueteData){
                //var_dump($paqueteData);
                $paqueteDataTmp                         = array();
                $paqueteDataTmp['id']                   = $paqueteData['_id'];
                $paqueteDataTmp['label']                = $paqueteData['name'];
                $paqueteDataTmp['description']          = $paqueteData['description'];
                $paqueteDataTmp['imagen']               = $paqueteData['imagen'];
                $paqueteDataTmp['imagenes']             = $paqueteData['imagenes'];

                if($paqueteData['precio'] == ""){

                    $paqueteDataTmp['preciodesde']      = false;

                    $paqueteDataTmp['precio']           = "";

                    foreach($paqueteData['variaciones']->tabla as $variacion){
                        
                        if($paqueteDataTmp['precio'] == ""){

                            $paqueteDataTmp['precio']   = $variacion->col2;
                            
                            //COL 3
                            if($variacion->col3 > 0 && $variacion->col3 < $paqueteDataTmp['precio']){

                                $paqueteDataTmp['precio']   = $variacion->col3;
                            }
                            
                            $paqueteDataTmp['preciodesde']  = true;
                        }else{
                            
                            //COL 2
                            if($variacion->col2 > 0 && $variacion->col2 < $paqueteDataTmp['precio']){

                                $paqueteDataTmp['precio']   = $variacion->col2;
                            }

                            //COL 3
                            if($variacion->col3 > 0 && $variacion->col3 < $paqueteDataTmp['precio']){

                                $paqueteDataTmp['precio']   = $variacion->col3;
                            }

                            $paqueteDataTmp['preciodesde']  = true;
                        }
                    }
                }else{

                    $paqueteDataTmp['preciodesde']      = false;
                    $paqueteDataTmp['precio']           = $paqueteData['precio'];
                }

                $paqueteDataTmp['moneda']               = $paqueteData['moneda'];
                
                $paqueteDataTmp['variaciones']          = $paqueteData['variaciones'];
                
                $result[$paqueteDataTmp['id']]          = $paqueteDataTmp;
            }
        }
        
        return $result;
    }

    public function getTopDestinos($p_params = array()){

        $result             = false;

        $dataSourceOptions              = array();
        $dataSourceOptions['model']     = "taxonomy";

        $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $query                          = array();
        $query['page']                  = (isset($p_params['page'])) ? $p_params['page'] : 1;
        $query['rows']                  = (isset($p_params['rows'])) ? $p_params['rows'] : 8;
        $query['filters']               = array();
        $query['filters']['parent']     = array("taxonomy_destinos");

        if(isset($p_params['filters'])){

            foreach($p_params['filters'] as $filter=>$terms){

                $query['filters'][$filter] = $terms;
            }
        }
        
        if(isset($p_params['orders'])){

            $query['orders']           = $p_params['orders'];
        }        
        
        $queryResul                     = $dataSource->getData($query);
        
        if(\is_array($queryResul) && isset($queryResul['objects'])){

            $result                     = array();
            
            foreach($queryResul['objects'] as $destinoData){

                $destinoDataTemp                        = array();
                $destinoDataTemp['id']                  = $destinoData['_id'];
                $destinoDataTemp['label']               = $destinoData['name'];
                $destinoDataTemp['description']         = $destinoData['description'];
                $destinoDataTemp['imagen']              = $destinoData['imagen'];
                
                $result[$destinoDataTemp['id']]       = $destinoDataTemp;
            }
        }

        return $result;
    }

    protected function getTaxonomyIdName($p_taxonomy){

        $result                         = false;

        $dataSourceOptions              = array();
        $dataSourceOptions['model']     = "taxonomy";
         
        $dataSource                     = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        return                          $dataSource->getDataIdNames($p_taxonomy);
    }
}
