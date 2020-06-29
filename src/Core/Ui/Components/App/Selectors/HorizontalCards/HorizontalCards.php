<?php
namespace Nubesys\Core\Ui\Components\App\Selectors\HorizontalCards;

use Nubesys\Vue\Ui\Components\VueUiComponent;

class HorizontalCards extends VueUiComponent {

    public function mainAction(){

        $this->registerReference("selector");
        
        $this->setJsDataVar("datasource", $this->getLocal("datasource"));
        $this->setJsDataVar("tableFields", $this->getTableFields($this->getLocal("fields")));
        $this->setJsDataVar("hiddenFields", $this->getHiddenFields($this->getLocal("fields")));
        $this->setJsDataVar("linksFields", $this->getLinksFields($this->getLocal("fields")));
        $this->setJsDataVar("actionsFields", $this->getActionsFields($this->getLocal("fields")));
        
        $this->setJsDataVar("data", $this->getLocal("data"));

        $this->setJsDataVar("paginator", $this->getLocal("paginator"));
    }

    protected function getHiddenFields($p_fields){

        $result                 = array();
        foreach($p_fields as $key=>$value){

            if($value['renderType'] == "HIDDEN"){

                $result[$key]   = $value;
            }
        }

        return $result;
    }

    protected function getTableFields($p_fields){

        $result                 = array();
        foreach($p_fields as $key=>$value){

            if($value['renderType'] != "LINKBUTTON" && $value['renderType'] != "ACTIONBUTTON" && $value['renderType'] != "HIDDEN"){

                $result[$key]   = $value;
            }
        }

        return $result;
    }

    protected function getLinksFields($p_fields){

        $result                 = array();
        foreach($p_fields as $key=>$value){

            if($value['renderType'] == "LINKBUTTON"){

                $result[$key]   = $value;
            }else if ($value['renderType'] == "SELECTOR"){

                $result[$key]   = $value;
            }
        }

        return $result;
    }

    protected function getActionsFields($p_fields){

        $result                 = array();
        foreach($p_fields as $key=>$value){

            if($value['renderType'] == "ACTIONBUTTON"){

                $result[$key]   = $value;
            }
        }

        return $result;
    }
}