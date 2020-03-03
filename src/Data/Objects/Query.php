<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 05/10/16
 * Time: 05:40 PM
 */

namespace Nubesys\Data\Objects;


class Query
{
    private $model;
    private $options;

    private $filters;
    private $orders;
    private $pagination;

    public function __construct($p_model, $p_options = array())
    {
        $this->model        = $p_model;
        $this->options      = $p_options;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return mixed
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @param mixed $pagination
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
    }


}