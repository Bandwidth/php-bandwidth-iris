<?php

namespace Iris;

class Account extends RestEntry {
    public function __construct($account_id, $client=Null, $namespace='accounts')
    {
        parent::_init($client, $namespace);
        $this->account_id = $account_id;
        $this->client = $client;
    }

    /**
    * @params \Iris\TnLineOptions
    */
    public function lineOptionOrders(TnLineOptions $data) {
        $url = sprintf('%s/%s', $this->account_id, 'lineOptionOrders');        
        $response = parent::post($url, "LineOptionOrder", $data->to_array());
        return new TnLineOptionOrderResponse($response);
    }

    public function inserviceNumbers() {
        if(!isset($this->inserviceNumbers))
            $this->inserviceNumbers = new InserviceNumbers($this);
        return $this->inserviceNumbers;
    }

    public function orders() {
        if(!isset($this->orders))
            $this->orders = new Orders($this);
        return $this->orders;
    }

    public function portins() {
        if(!isset($this->portins))
            $this->portins = new Portins($this);
        return $this->portins;
    }

    public function disconnects() {
        if(!isset($this->disconnects))
            $this->disconnects = new Disconnects($this);
        return $this->disconnects;
    }

    public function disnumbers() {
        if(!isset($this->disnumbers))
            $this->disnumbers = new Disnumbers($this);
        return $this->disnumbers;
    }

    public function portouts() {
        if(!isset($this->portouts))
            $this->portouts = new Portouts($this);
        return $this->portouts;
    }

    public function lsrorders() {
        if(!isset($this->lsrorders))
            $this->lsrorders = new Lsrorders($this);
        return $this->lsrorders;
    }

    public function libds() {
        if(!isset($this->libds))
            $this->libds = new Libds($this);
        return $this->libds;
    }

    public function didas() {
        if(!isset($this->didas))
            $this->didas = new Didas($this);
        return $this->didas;
    }

    public function subscriptions() {
        if(!isset($this->subscriptions))
            $this->subscriptions = new Subscriptions($this);
        return $this->subscriptions;
    }

    public function tnreservation() {
        if(!isset($this->tnreservation))
            $this->tnreservation = new Tnreservation($this);
        return $this->tnreservation;
    }

    public function sites() {
        if(!isset($this->sites))
            $this->sites = new Sites($this);
        return $this->sites;
    }

    public function reports() {
        if(!isset($this->reports))
            $this->reports = new Reports($this);
        return $this->reports;
    }

    /**
     * Account Info by Id
     *
     */
    public function get($url, $options=Array(), $defaults = Array(), $required = Array())
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
