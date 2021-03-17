<?php

namespace Nubesys\Analytics\Ws\Elasticsearch;

use Nubesys\Core\Services\WsService;
use Nubesys\Data\Objects\Adapters\Elastic;
use Flow\JSONPath\JSONPath;

class Proxy extends WsService {

    public function queryMethod(){

        $elastic        = new Elastic($this->getDI());

        if($elastic->setClient()) {

            if($this->hasJsonParam("index")){

                $index          = $elastic->setIndex($this->getJsonParam("index"), array());

                if($this->hasJsonParam("type")){

                    if($index !== false){

                        if($this->hasJsonParam("query")){

                            $type                           = $elastic->setType($index, $this->getJsonParam("type"), array());

                            if($type !== false) {
                                
                                $queryResult                = $elastic->rawQuery($type, $this->getJsonParam("query"));
                                
                                if($this->hasJsonParam("jsonPathRows")){

                                    $result                 = array();

                                    $queryResultRows        = (new JSONPath($queryResult))->find($this->getJsonParam("jsonPathRows"))->getData()[0];

                                    foreach($queryResultRows as $row){

                                        if($this->hasJsonParam("jsonPathFields")){
                                            
                                            $rowTmp         = array();

                                            foreach($this->getJsonParam("jsonPathFields") as $fieldLabel=>$fieldJsonPath){

                                                $rowTmp[$fieldLabel]     = (new JSONPath($row))->find($fieldJsonPath)->getData()[0];
                                            }

                                            $result[]       = $rowTmp;
                                        }else{

                                            $result[]       = $row;
                                        }
                                    }

                                }else{

                                    $result = $queryResult;
                                }
                            
                                $this->setServiceSuccess($result);
                            }else{

                                $this->setServiceError("Elasticsearch select type ERROR");
                            }

                        }else{

                            $this->setServiceError("Elasticsearch query not found");
                        }
                        
                    }else{

                        $this->setServiceError("Elasticsearch select index ERROR");
                    }

                }else{

                    $this->setServiceError("Elasticsearch type param not found");
                }

            }else{

                $this->setServiceError("Elasticsearch index param not found");
            }

        }else{

            $this->setServiceError("Elasticsearch conection error");
        }
    }
}