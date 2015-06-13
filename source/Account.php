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

class Account extends RestEntry {

    /**
     *
     *
     */
    public function __construct($account_id, $client=Null, $namespace='accounts')
    {
        parent::_init($client, $namespace);
        $this->account_id = $account_id;
        $this->client = $client;

        //$this->orders = new Orders($this->account_id);
    }

    public function getPortins() {
        if(!isset($this->portins))
            $this->portins = new Portins($this);
        return $this->portins;
    }

    public function getSites() {
        if(!isset($this->sites))
            $this->sites = new Sites($this);
        return $this->sites;
    }

    /**
     * Account Info by Id
     *
     */
    public function get($options=Array())
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

    public function get_relative_namespace() {
      return "accounts/{$this->account_id}";
    }

    public function get_rest_client() {
      return $this->client;
    }
}
