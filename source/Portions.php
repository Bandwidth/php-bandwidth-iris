<?php

/**
 * @model Order
 * https://api.test.inetwork.com/v1.0/accounts/orders
 *
 * 
 *
 * provides: 
 * get/0
 *
 */

namespace Iris;

$selfpath = dirname(__FILE__);
require_once($selfpath . '/../vendor/autoload.php');


final class Orders extends RestEntry{

    public function __construct($account_id, $client=Null, $namespace='accounts/{$this->account_id}/portions'){
        $this->account_id = $account_id;
        $this->namespace = $namespace;
        parent::_init($client, $namespace);
    }

    public function get($portion_id, $url='', $options=Array())
    {        
        /* try get order by id if get then else self exception */
        return new Order($this->account_id, $this->portion_id);
    }

    public function list1($filters)
    {
        $data = parent::get($this->account_id);
        return $data;
    }
}

final class Order extends RestEntry{
    public function __construct($account_id, $portion_id, $client=Null, $namespace='accounts/{$this->account_id}/orders')
    {
        $this->account_id = $account_id;
        $this->portion_id = $portion_id;
        parent::_init($client, $namespace);
    }
    
    public function areCodes()
    {
        $url = sprintf('%s/%s/%s', $this->account_id, $this->order_id,'areCodes');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function npaNxx() 
    {
        $url = sprintf('%s/%s/%s', $this->account_id, $this->order_id,'npaNxx');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function totals() 
    {
        $url = sprintf('%s/%s/%s', $this->account_id, $this->order_id,'totals');
        $data = parent::get($url, $filters);
        return $data;
    }
    
    public function tns() 
    {
        $url = sprintf('%s/%s/%s', $this->account_id, $this->order_id,'tns');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function notes() 
    {
        $url = sprintf('%s/%s/%s', $this->account_id, $this->order_id,'notes');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function history() 
    {
        $url = sprintf('%s/%s/%s', $this->account_id, $this->order_id,'history');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function activationStatus() 
    {
        $url = sprintf('%s/%s/%s', $this->account_id, $this->order_id,'activationStatus');
        $data = parent::get($url, $filters);
        return $data;
    }
}