<?php

/**
 * @model Account
 * https://api.test.inetwork.com/v1.0/accounts/
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

/* TODO:  remove build url from methods */

final class Account extends RestEntry {

    /**
     *
     *
     */
    public function __construct($account_id, $client=Null, $namespace='accounts')
    {
        parent::_init($client, $namespace);
        $this->account_id = $account_id;

        $this->orders = new Orders($this->account_id);
        $this->orders = new Portions($this->account_id);
        //$this->orders = new Disconnect($this->account_id);
        //$this->orders = new Isrorder($this->account_id);
    }

    /**
     * Account Info by Id
     * 
     */
    public function get($url='', $options=Array())
    {        
        $data = parent::get($this->account_id);
        return $data;
    }

    public function availableNumbers($filters=Array()){
        /* TODO:  too bad */
        //print_r(__FUNCTION__); exit;
        $url = sprintf('%s/%s', $this->account_id, 'avalibleNumbers');
        $data = parent::get($url, $filters);

        return $data;
    }

    public function serviceNumbers($filters=Array()){
        $url = sprintf('%s/%s', $this->account_id, 'serviceNumbers');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function orders($filters=Array()){
        $url = sprintf('%s/%s', $this->account_id, 'orders');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function users($filters=Array()){
        $url = sprintf('%s/%s', $this->account_id, 'users');
        $data = parent::get($url, $filters);
        return $data;
    }

    public function products($filters=Array()){
        $url = sprintf('%s/%s', $this->account_id, 'products');
        $data = parent::get($url, $filters);
        return $data;
    }
}

