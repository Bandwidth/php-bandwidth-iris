<?php

/**
 * @model Portins
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

class Portins extends RestEntry {
    public function __construct($account) {
        $this->account = $account;
        parent::_init($account->get_rest_client(), $account->get_relative_namespace());
    }

    public function create() {
        $data = parent::post('portins', '');
        return $data;
    }

    public function get($filters = Array()) {
        $out = [];

        $portins = parent::get('portins', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($portins->lnpPortInfoForGivenStatus && is_array($portins->lnpPortInfoForGivenStatus)) {
            foreach($portins->lnpPortInfoForGivenStatus as $portin) {
                $out[] = new Portin($this, $portin);
            }
        }

        return $out;
    }

    public function totals()
    {
        $url = sprintf('%s/%s', 'portins', 'totals');
        $data = parent::get($url);
        return $data;
    }

    public function get_rest_client() {
        return $this->account->get_rest_client();
    }

    public function get_relative_namespace() {
        return $this->account->get_relative_namespace().'/portins';
    }
}

class Portin extends RestEntry {
    public function __construct($portins, $data) {
        $this->id = $data->OrderId;
        parent::_init($portins->get_rest_client(), $portins->get_relative_namespace());
    }

    public function get() {
        $this->data = parent::get($this->id);
        return $this->data;
    }

    public function areaCodes()
    {
        $url = sprintf('%s/%s', $this->id, 'areaCodes');
        $data = parent::get($url);
        return $data;
    }
    public function history()
    {
        $url = sprintf('%s/%s', $this->id, 'history');
        $data = parent::get($url);
        return $data;
    }
    public function npaNxx()
    {
        $url = sprintf('%s/%s', $this->id, 'npaNxx');
        $data = parent::get($url);
        return $data;
    }
    public function tns()
    {
        $url = sprintf('%s/%s', $this->id, 'tns');
        $data = parent::get($url);
        return $data;
    }
    public function totals()
    {
        $url = sprintf('%s/%s', $this->id, 'totals');
        $data = parent::get($url);
        return $data;
    }
    public function activationStatus()
    {
        $url = sprintf('%s/%s', $this->id, 'activationStatus');
        $data = parent::get($url);
        return $data;
    }
    public function notes()
    {
        $url = sprintf('%s/%s', $this->id, 'notes');
        $data = parent::get($url);
        return $data;
    }
}
