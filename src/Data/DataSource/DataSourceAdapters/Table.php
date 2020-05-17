<?php

namespace Nubesys\Data\DataSource\DataSourceAdapters;

use Nubesys\Data\DataSource\DataSourceAdapters\DataSourceAdapter;

//NUBESYS DATA ENGINE
use Nubesys\Data\Objects\Adapters\Mysql;

class Table extends DataSourceAdapter {

    protected $database;
    protected $options;

    public function __construct($p_di, $p_options = array())
    {
        parent::__construct($p_di, $p_options);

        $this->options      = $p_options;

        //TODO: VER PARA CAMBIAR POR OTROS MOTORES DE BASES DE DATOS
        $this->database     = new Mysql($p_di);

        /*
        $this->setModelData();
        $this->setModelDataDefinitions();
        */
    }

    public function getData($p_query = array()){

        $result                             = array();
        $result['page']                     = 1;
        $result['rows']                     = 100;

        $selectOptions                      = array();
        
        if(isset($p_query['fields'])){

            $selectOptions['fields']        = $p_query['fields'];
        }

        if(isset($p_query['conditions'])){

            $selectOptions['conditions']    = $p_query['conditions'];
        }

        if(isset($p_query['order'])){

            $selectOptions['order']         = $p_query['order'];
        }

        if(isset($p_query['rows']) && isset($p_query['page'])){

            $selectOptions['rows']          = $p_query['rows'];
            $selectOptions['offset']        = $p_query['rows'] * ( $p_query['page'] - 1 );

            $result['page']                 = $p_query['page'];
            $result['rows']                 = $p_query['rows'];

            $totalResult                    = $this->database->select($this->options['table'], $selectOptions); 

            $result['totals']               = 0;
            $result['pages']                = 1;

            if($totalResult){

                $result['totals']           = $totalResult;
                $result['pages']            = ceil($result['totals']/$result['rows']);
            }
            
        }

        $selectResult = $this->database->select($this->options['table'],$selectOptions);

        if(is_array($selectResult)){

            $result['objects']              = array();

            foreach($selectResult as $object){

                $result['objects'][] = $object;
            }

        }
        
        return $result;
    }

    public function rawQuery($p_rawquery){
        $result                             = array();

        $queryResult                        = $this->database->query($p_rawquery);

        if(is_array($queryResult)){

            $result                         = $queryResult;
        }
        
        return $result;
    }
}