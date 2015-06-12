<?php

/**
 * @model Sites
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

class Sites extends RestEntry {
    public function __construct($account) {
        $this->account = $account;
        parent::_init($account->get_rest_client(), $account->get_relative_namespace());
    }

    public function get($filters = Array()) {
        $sites = [];

        $data = parent::get('sites');

        if(isset($data->Sites) && isset($data->Sites->Site)) {
            foreach($data->Sites->Site as $site) {
                $sites[] = new Site($this, $site);
            }
        }

        return $sites;
    }

    public function get_rest_client() {
        return $this->account->get_rest_client();
    }

    public function get_relative_namespace() {
        return $this->account->get_relative_namespace().'/sites';
    }

    public function create($data) {
        $site = new Site($this, null);
        $site->create($data);
        return $site;
    }
}

class Site extends RestEntry {
    protected $fields = array(
        "Name" => "string",
        "Description" => "string",
        "Address" => "Address",
        "CustomerProvidedID" => "string",
        "CustomerName" => "string"
    );

    protected $required = array(
        "Name"
    );

    public function __construct($sites, $data = null) {
        if(!is_null($data)) {
            $this->id = $data->Id;
            $this->set_data($data);
        }
        parent::_init($sites->get_rest_client(), $sites->get_relative_namespace());
    }

    public function create($data) {
        try {
            $res = parent::post(null, "Site", $data);
        } catch(Exception $e) {
            var_dump($e);
        }
        var_dump($res);
    }
}
