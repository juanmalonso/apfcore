<?php

namespace Nubesys\Data\DataSource\DataSourceAdapters;

use Nubesys\Data\DataSource\DataSourceAdapters\DataSourceAdapter;

//NUBESYS DATA ENGINE
use Nubesys\Data\Objects\Adapters\Mysql;

class Custom extends DataSourceAdapter {

    protected $options;
    protected $data;
    protected $definitions;

    public function __construct($p_di, $p_options)
    {
        parent::__construct($p_di, $p_options);

        $this->options      = $p_options;
    }

    public function getData($p_query){
        
        $result                             = array();
        $result['page']                     = 1;
        $result['rows']                     = 1000;
        $result['pages']                    = 1;
        $result['totals']                   = 0;
        $result['objects']                  = array();

        if(isset($this->options['data']) && is_array($this->options['data'])){

            if(isset($p_query['order'])){

                //$selectOptions['order']         = $p_query['order'];

                //TODO : AGREGAR ORDERS
            }

            //TODO : AGREGAR FILTROS
            
            if(isset($p_query['rows']) && isset($p_query['page'])){

                $result['page']                 = $p_query['page'];
                $result['rows']                 = $p_query['rows'];
                
            }

            $result['totals']           = count($this->options['data']);
            $result['pages']            = ceil($result['totals']/$result['rows']);
            
            //TODO : MEJORAR, QUE SOLO CONSULTE DIRECTAMENTE LOS SROWS DE LA PAGINA, INDEXE DIRECTO Y NO SE TRAGUE TODO EL ARRAY
            $row = 1;
            foreach($this->options['data'] as $object){

                $offset = (($result['rows'] * $result['page']) - $result['rows'] ) + 1;

                if($row >= $offset && $row < ($offset + $result['rows'])){

                    $result['objects'][] = $object;
                }

                $row++;
            }

        }
        
        return $result;
    }

    public function getDataDefinitions(){

        return $this->options['definitions'];
    }
}