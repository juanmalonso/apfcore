<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 26/07/16
 * Time: 06:03 PM
 */

namespace Nubesys\Data\Objects\Adapters;

use Nubesys\Core\Common;

class Mysql extends Common
{
    public function isTableExist($p_table){

        return $this->getDI()->get('datadb')->tableExists($p_table);
    }

    public function query($p_query){

        $result = $this->getDI()->get('datadb')->fetchAll($p_query, \Pdo::FETCH_ASSOC, array());

        return $result;
    }

    public function execute($p_statement){

        $result = $this->getDI()->get('datadb')->execute($p_statement);

        return $result;
    }

    public function select($p_table, $p_options = array(), $p_params = array()){
        
        $result = false;

        //TODO: Agregar Table Is Exists? antes de usar la tabla

        $fields = '*';
        if(isset($p_options['fields'])){

            if(is_array($p_options['fields'])){

                $fields = implode(", ", $p_options['fields']);

            }else if (is_string($p_options['fields'])){

                //TODO: Agregar Validacion con Expresion Regular para saber si esta bien formateado los campos

                $fields = $p_options['fields'];
            }
        }

        $conditions = ' ';
        if(isset($p_options['conditions']) && strlen($p_options['conditions']) > 0){

            $conditions =  " WHERE " . $p_options['conditions'];
        }

        $order = '';
        if(isset($p_options['order'])){

            if(is_array($p_options['order'])){

                $orderPartes = array();

                foreach($p_options['order'] as $field=>$direction){

                    $orderPartes[] = $field . " " . $direction;
                }

                $order = "ORDER BY " . implode(", ", $orderPartes);

            }else if (is_string($p_options['order'])){

                $order = " ORDER BY " . $p_options['order'];
            }
        }
        //TODO: Ver Opcion Sin LIMIT
        $rows = " LIMIT 200";
        if(isset($p_options['rows'])){

            if(is_numeric($p_options['rows'])){

                $rows = " LIMIT " . $p_options['rows'];
            }
        }

        $offset = '';
        if(isset($p_options['offset'])){

            if(is_numeric($p_options['offset'])){

                $offset = " OFFSET " . $p_options['offset'];
            }
        }

        $params = array();
        foreach ($p_params as $key=>$value){

            /*if(is_string($value)){

                $params[$key] = $this->getDI()->get('datadb')->escapeString($value);
            }else{

                $params[$key] = $value;
            }*/

            $params[$key] = $value;
        }

        //TODO: TryCatchear Esto
        //TODO: CREAR Exception NubesysMySQL

        $query = "SELECT " . $fields . " FROM " . $p_table . "" . $conditions ."" . $order . "" . $rows . "" . $offset . ";";

        $resultSet = $this->getDI()->get('datadb')->fetchAll($query, \Pdo::FETCH_ASSOC, $params);

        if(is_array($resultSet)){

            foreach ($resultSet as $row){

                $result[] = $row;
            }
        }

        return $result;
    }

    public function selectOne($p_table, $p_options = array(), $p_params = array()){

        $result = false;

        //TODO: Agregar Table Is Exists? antes de usar la tabla

        $fields = '*';
        if(isset($p_options['fields'])){

            if(is_array($p_options['fields'])){

                $fields = implode(", ", $p_options['fields']);

            }else if (is_string($p_options['fields'])){

                //TODO: Agregar Validacion con Expresion Regular para saber si esta bien formateado los campos

                $fields = $p_options['fields'];
            }
        }

        $conditions = ' ';
        if(isset($p_options['conditions']) && strlen($p_options['conditions']) > 0){

            $conditions =  " WHERE " . $p_options['conditions'];
        }

        $order = '';
        if(isset($p_options['order'])){

            if(is_array($p_options['order'])){

                $orderPartes = array();

                foreach($p_options['order'] as $field=>$direction){

                    $orderPartes[] = $field . " " . $direction;
                }

                $order = "ORDER BY " . implode(", ", $orderPartes);

            }else if (is_string($p_options['order'])){

                $order = " ORDER BY " . $p_options['order'];
            }
        }
        //TODO: Ver Opcion Sin LIMIT
        $rows = " LIMIT 100";
        if(isset($p_options['rows'])){

            if(is_numeric($p_options['rows'])){

                $rows = " LIMIT " . $p_options['rows'];
            }
        }

        $offset = '';
        if(isset($p_options['offset'])){

            if(is_numeric($p_options['offset'])){

                $offset = " OFFSET " . $p_options['offset'];
            }
        }

        $params = array();
        foreach ($p_params as $key=>$value){

            /*if(is_string($value)){

                $params[$key] = $this->getDI()->get('db')->escapeString($value);
            }else{

                $params[$key] = $value;
            }*/

            $params[$key] = $value;
        }

        //TODO: TryCatchear Esto
        //TODO: CREAR Exception NubesysMySQL

        $query = "SELECT " . $fields . " FROM " . $p_table . "" . $conditions ."" . $order . "" . $rows . "" . $offset . ";";

        $resultSet = $this->getDI()->get('datadb')->fetchOne($query, \Pdo::FETCH_ASSOC, $params);

        if(is_array($resultSet)){

            $result = $resultSet;
        }

        return $result;
    }

    //TODO : Falta SELECT STATT para Estadisticas AVG, COUNT, SUM
    public function selectCount($p_table, $p_options = array(), $p_params = array()){

        $result = false;

        //TODO: Agregar Table Is Exists? antes de usar la tabla

        $field = 'COUNT(*) AS cantidad';

        $conditions = ' ';
        if(isset($p_options['conditions']) && strlen($p_options['conditions']) > 0){

            $conditions =  " WHERE " . $p_options['conditions'];
        }

        $params = array();
        foreach ($p_params as $key=>$value){

            /*if(is_string($value)){

                $params[$key] = $this->getDI()->get('db')->escapeString($value);
            }else{

                $params[$key] = $value;
            }*/

            $params[$key] = $value;
        }

        //TODO: TryCatchear Esto
        //TODO: CREAR Exception NubesysMySQL

        $query = "SELECT " . $field . " FROM " . $p_table . "" . $conditions .";";

        $resultSet = $this->getDI()->get('datadb')->fetchOne($query, \Pdo::FETCH_ASSOC, $params);

        if(is_array($resultSet)){

            $result = $resultSet['cantidad'];
        }

        return $result;
    }

    public function insert($p_table, $p_data){
        
        $result = false;

        if(is_array($p_data)){

            $result = $this->getDI()->get('datadb')->insertAsDict($p_table, $p_data);
        }

        //TODO : Retornar LastInserId

        return $result;
    }

    public function insertBulk($p_table, $p_data){
        
        $result = false;

        /*if(is_array($p_data)){

            $result = $this->getDI()->get('datadb')->insertAsDict($p_table, $p_data);
        }

        //TODO : Retornar LastInserId






        $query = "insert into 'tablename' (a, b, c) values ";

        for($i = 0; $i < 10000; $i++){
            $values .= "(" . rand(1,50) . ", " . rand(1, 50). ", " . rand(1, 50). "),";
        }

        $values = substr($values, 0, strlen($values) - 1);
        $query .= $values;
        $this->db->query($query);*/

        return $result;
    }

    public function update($p_table, $p_data, $p_conditions){
        
        $result = false;

        if(is_array($p_data) && strlen($p_conditions) > 0){

            $result = $this->getDI()->get('datadb')->updateAsDict($p_table, $p_data, $p_conditions);
        }

        return $result;
    }

    public function delete($p_table, $p_conditions){

        $result = false;

        if(strlen($p_conditions) > 0){

            $result = $this->getDI()->get('datadb')->delete($p_table, $p_conditions);
        }

        return $result;
    }
}