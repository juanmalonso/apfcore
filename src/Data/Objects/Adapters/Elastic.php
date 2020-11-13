<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 26/07/16
 * Time: 06:03 PM
 */

namespace Nubesys\Data\Objects\Adapters;

use Nubesys\Core\Common;

class Elastic extends Common
{
    public function setClient($p_params = array()){

        $result = false;

        try {
            
            $config = array_merge($this->getDI()->get('config')->connections->elasticsearch->toArray(), $p_params);

            $client = new \Elastica\Client($config);

            $client->connect();

            if ($client->hasConnection()) {

                $result = $client;
            }

        }catch (\Elastica\Exception\Connection\GuzzleException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getErrorMessage();
            $exceptionData['request']   = $e->getRequest();
            $exceptionData['response']  = $e->getResponse();
            $exceptionData['trace']     = $e->getTraceAsString();

            $this->logError("Elastic GuzzleException " . $e->getErrorMessage(),"Elastic", $exceptionData);

        }catch (\Elastica\Exception\Connection\HttpException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getErrorMessage();
            $exceptionData['request']   = $e->getRequest();
            $exceptionData['response']  = $e->getResponse();
            $exceptionData['error']     = $e->getError();
            $exceptionData['trace']     = $e->getTraceAsString();

            $this->logError("Elastic HttpException " . $e->getErrorMessage(),"Elastic", $exceptionData);

        }catch (\Elastica\Exception\ConnectionException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getMessage();
            $exceptionData['request']   = $e->getRequest();
            $exceptionData['response']  = $e->getResponse();
            $exceptionData['trace']     = $e->getTraceAsString();


            $this->logError("Elastic ConnectionException " . $e->getMessage(),"Elastic", $exceptionData);

        }catch (\Elastica\Exception\ClientException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getMessage();
            $exceptionData['trace']     = $e->getTraceAsString();


            $this->logError("Elastic ClientException " . $e->getMessage(),"Elastic", $exceptionData);
        }


        return $result;
    }

    public function setIndex($p_indexName, $p_args){

        $result = false;

        $client = $this->setClient();

        if($client !== false){
            
            try{

                $index = new \Elastica\Index($client, $p_indexName);
    
                if(!$index->exists()){
    
                    $index->create($p_args);
                }
    
                $result = $index;
                
            }catch (\Elastica\Exception\InvalidException $e){

                $exceptionData              = array();
                $exceptionData['name']      = $e->getErrorMessage();
    
                $this->logError("Elastic InvalidException " . $e->getErrorMessage(),"Elastic", $exceptionData);

            }catch (\Elastica\Exception\Connection\ResponseException $e){

                $exceptionData              = array();
                $exceptionData['name']      = $e->getErrorMessage();
                $exceptionData['request']   = $e->getRequest();
                $exceptionData['response']  = $e->getResponse();
    
                $this->logError("Elastic HttpException " . $e->getErrorMessage(),"Elastic", $exceptionData);

            }
        }

        return $result;
    }

    public function deleteIndex($p_indexName){

        $result = false;

        $client = $this->setClient();

        if($client !== false){

            $index = new \Elastica\Index($client, $p_indexName);

            if(!$index->exists()) {

                $result = true;

            }else{

                $esResult = $index->delete();

                if($esResult->isOk()){

                    $result = true;
                }
            }
        }
        
        return $result;
    }

    public function setType(\Elastica\Index $p_index, $p_typeName, $p_mappingProperties = array(), $p_mappingParams = array()){
            $result = false;

            $type = $p_index->getType($p_typeName);
            
            if (count($p_mappingProperties) > 0) {
                
                $mapping = new \Elastica\Type\Mapping();
                $mapping->setType($type);
                
                foreach ($p_mappingParams as $param => $value) {

                    $mapping->setParam($param, $value);
                }

                $mapping->setProperties($p_mappingProperties);
                
                $esResult = $mapping->send();
                
                if ($esResult->isOk()) {

                    $result = $type;
                } else {

                    //TODO: ERROR EN MAPEO
                }
            } else {

                $result = $type;
            }
            

        return $result;
    }

    public function searchDocs(\Elastica\Type $p_type, $p_keyword, $p_fields, $p_sorts, $p_page, $p_rows, $p_facets, $p_filters, $p_ranges){
        
        $result = false;

        $query = new \Elastica\Query();
        
        $query->setFrom(($p_page*$p_rows)-$p_rows);
        $query->setSize($p_rows);

        //QUERYSTRING
        $queryString    = new \Elastica\Query\QueryString();
        //$queryString    = new \Elastica\Query\SimpleQueryString();

        //$queryString->setFields($p_fields);
        
        $queryString->setQuery($p_keyword);
        $queryString->setDefaultOperator("OR");

        //AND
        $queryAND       = new \Elastica\Query\BoolQuery();
        $queryAND->addMust($queryString);

        /*if(isset($p_filters['ranges'])){

            foreach ($p_filters['ranges'] as $field=>$ranges){

                $queryRangeTmp     = new \Elastica\Query\Range();
                $queryRangeTmp->addField("objDateAdd", array(
                    "gte" => $ranges[0],
                    "lte" => $ranges[1]
                ));
                $queryAND->addFilter($queryRangeTmp);
            }
        }*/
        
        if(isset($p_filters['and'])){

            foreach ($p_filters['and'] as $term=>$values){

                if(is_array($values) && count($values) > 0){

                    $queryAND->addFilter(new \Elastica\Query\Terms($term, $values));
                }
            }
        }else{

            foreach ($p_filters as $term=>$values){

                if(is_array($values) && count($values) > 0){
                    
                    $queryAND->addFilter(new \Elastica\Query\Terms($term, $values));
                }
            }
        }

        foreach($p_ranges as $field=>$range){
            $queryRange     = new \Elastica\Query\Range();
            $queryRange->addField($field, $range);
            $queryAND->addFilter($queryRange);
        }

        $queryMain      = new \Elastica\Query\BoolQuery();
        $queryMain->addMust($queryAND);

        //TODO AGGREGATIONS EN UNA FUNCION RECURSIVA
        foreach($p_facets as $field=>$aggregation){
            
            if($aggregation['type'] == 'terms'){

                $termsAgg = new \Elastica\Aggregation\Terms($aggregation['name']);
                $termsAgg->setField($field);

                if(isset($aggregation['size'])){

                    $termsAgg->setSize($aggregation['size']);
                }
                
                $query->addAggregation($termsAgg);
            }
        }

        /*
        if(strlen($p_keyword) > 0){

            $queryMultiMatch = new \Elastica\Query\MultiMatch();

            $queryMultiMatch->setOperator('AND');
            $queryMultiMatch->setFields($p_fields);
            $queryMultiMatch->setQuery($p_keyword);

            $query->setQuery($queryMultiMatch);



            $query->setQuery($queryString);
        }

        if( || isset($p_filters['or']) || isset($p_filters['not'])){
            /*
            $filterBool = new \Elastica\Filter\Bool();

            $count = 0;

            if(isset($p_filters['and'])){


            }

            if(isset($p_filters['or'])){

                foreach ($p_filters['or'] as $term=>$values){

                    if(is_array($values) && count($values) > 0){

                        $filter = new \Elastica\Filter\Terms();
                        $filter->setTerms($term, $values);

                        $filterBool->addShould($filter);

                        $count ++;
                    }

                }
            }

            if(isset($p_filters['not'])){

                foreach ($p_filters['not'] as $term=>$values){

                    if(is_array($values) && count($values) > 0){

                        $filter = new \Elastica\Filter\Terms();
                        $filter->setTerms($term, $values);

                        $filterBool->addMustNot($filter);

                        $count ++;
                    }

                }
            }

            if($count > 0){

                $query->setPostFilter($filterBool);
            }
        }else{

            $queryAND    = new \Elastica\Query\BoolQuery();

            $count = 0;
            foreach ($p_filters as $term=>$values){

                if(is_array($values) && count($values) > 0){

                    $terms = new \Elastica\Query\Terms();
                    $terms->setTerms($term, $values);

                    $queryAND->addFilter($terms);

                    $count ++;
                }

            }

            if($count > 0){

                $query->setPostFilter($queryAND);
            }
        }*/

        //TODO : FALTA FACETS
        
        if(count($p_sorts) > 0){

            $query->addSort($p_sorts);
        }

        $query->setQuery($queryMain);

        //var_dump(json_encode($query->getQuery()->toArray()));
        //echo "<hr>";

        $resultSet = $p_type->search($query);

        //var_dump(json_encode($resultSet->getQuery()->toArray()));
        //echo "<hr>";
        //exit();

        $result = array();
        $result['totals'] = $resultSet->getTotalHits();

        $result['facets']       = array();

        if($resultSet->hasAggregations()){

            foreach($resultSet->getAggregations() as $aggregation=>$aggregationData){

                $buckets = array();

                if(isset($aggregationData['buckets']) && \is_array($aggregationData['buckets'])){

                    foreach($aggregationData['buckets'] as $bucket){

                        $buckets[$bucket['key']] = $bucket['doc_count'];
                    }
                }
            }

            $result['facets'][$aggregation]   = $buckets;
        }

        $result['docs'] = array();

        foreach ($resultSet->getDocuments() as $doc) {

            $result['docs'][] = $doc;
        }
        
        return $result;
    }

    public function addDoc(\Elastica\Type $p_type, $p_id, $p_data){
        
        $result = false;

        $doc = new \Elastica\Document($p_id, $p_data);

        try {

            $esResult = $p_type->addDocument($doc);
            
            if($esResult->isOk()){

                $result = $esResult;
            }else{

                $errorData              = array();
                $errorData['message']   = $esResult->getErrorMessage();
                $errorData['error']     = $esResult->getError();
                $errorData['fullerror'] = $esResult->getFullError();

                var_dump($errorData);exit();

                //$this->logError("Elastic Result Error " . $esResult->getErrorMessage,"Elastic", $errorData);
            }

            $p_type->getIndex()->refresh();

        }catch (\Elastica\Exception\ResponseException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getMessage();
            $exceptionData['request']   = $e->getRequest();
            $exceptionData['response']  = $e->getResponse();
            $exceptionData['trace']     = $e->getTraceAsString();
            $exceptionData['doc']     = $doc->getData();
            
            var_dump($exceptionData);exit();
            //$this->logError("Elastic ResponseException " . $e->getMessage(),"Elastic", $exceptionData);

        }catch (\Elastica\Exception\JSONParseException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getMessage();
            $exceptionData['trace']     = $e->getTraceAsString();
            $exceptionData['doc']     = $doc->getData();
            
            var_dump($exceptionData);exit();
            //$this->logError("Elastic JSONParseException " . $e->getMessage(),"Elastic", $exceptionData);
        }catch (\Elastica\Exception\ElasticsearchException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getExceptionName();
            $exceptionData['error']     = $e->getError();
            $exceptionData['trace']     = $e->getTraceAsString();
            $exceptionData['doc']     = $doc->getData();
            
            var_dump($exceptionData);exit();
            //$this->logError("Elastic ElasticsearchException " . $e->getMessage(),"Elastic",$exceptionData);
        }

        

        return $result;
    }

    public function addBulk(\Elastica\Client $p_client, \Elastica\Index $p_index, \Elastica\Type $p_type, $p_data){
        
        $result = false;

        $bulk   = new \Elastica\Bulk($p_client);
        $bulk->setIndex($p_index);
        $bulk->setType($p_type);

        foreach ($p_data as $_id=>$data){
            
            $bulk->addDocument(new \Elastica\Document($_id, $data));
        }
        
        try {

            $esBulkResult = $bulk->send();
            
            if($esBulkResult->isOk()){

                $result = $esBulkResult;
                
            }else{

                $errorData              = array();
                $errorData['message']   = $esBulkResult->getErrorMessage();
                $errorData['error']     = $esBulkResult->getError();
                $errorData['fullerror'] = $esBulkResult->getFullError();

                var_dump($errorData);exit();

                //$this->logError("Elastic Bulk Result Error " . $esBulkResult->getErrorMessage,"Elastic", $errorData);
            }

            $p_type->getIndex()->refresh();
        }catch (\Elastica\Exception\Bulk\ResponseException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getMessage();
            $exceptionData['failures']  = $e->getFailures();
            $exceptionData['trace']     = $e->getTraceAsString();

            var_dump($exceptionData);exit();

            //$this->logError("Elastic Bulk Exception " . $e->getMessage(),"Elastic", $exceptionData);

        }catch (\Elastica\Exception\ResponseException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getMessage();
            $exceptionData['request']   = $e->getRequest();
            $exceptionData['response']  = $e->getResponse();
            $exceptionData['trace']     = $e->getTraceAsString();

            var_dump($exceptionData);exit();

            //$this->logError("Elastic Exception " . $e->getMessage(),"Elastic", $exceptionData);

        }catch (\Elastica\Exception\JSONParseException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getMessage();
            $exceptionData['trace']     = $e->getTraceAsString();

            var_dump($exceptionData);exit();

            //$this->logError("Elastic Exception " . $e->getMessage(),"Elastic", $exceptionData);
        }catch (\Elastica\Exception\ElasticsearchException $e){

            $exceptionData              = array();
            $exceptionData['name']      = $e->getExceptionName();
            $exceptionData['error']     = $e->getError();
            $exceptionData['trace']     = $e->getTraceAsString();

            var_dump($exceptionData);exit();

            //$this->logError("Elastic Exception " . $e->getMessage(),"Elastic",$exceptionData);
        }

        return $result;
    }

    public function updateDoc(\Elastica\Type $p_type, $p_id, $p_data){
        
        $result = false;

        $doc = new \Elastica\Document($p_id, $p_data);

        $esResult = $p_type->updateDocument($doc);

        if($esResult->isOk()){

            $result = $esResult;
        }

        $p_type->getIndex()->refresh();

        return $result;
    }

    public function deleteDoc(\Elastica\Type $p_type, $p_id){

        $result = false;

        $doc = new \Elastica\Document($p_id);

        $esResult = $p_type->deleteDocument($doc);

        if($esResult->isOk()){

            $result = $esResult;
        }

        $p_type->getIndex()->refresh();

        return $result;
    }

    public function rawQuery(\Elastica\Type $p_type, $p_query)
    {

        $path = $p_type->getIndex()->getName();

        $path .= '/' . $p_type->getName();

        $path .= '/_search';

        $esResult = $p_type->request('_search', \Elastica\Request::POST, $p_query);

        return $esResult->getData();
    }
}
