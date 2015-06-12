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
        $site = new Site($this, $data);
        $site->save();
        return $site;
    }
}

class Site extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "Name" => array(
            "type" => "string"
        ),
        "Description" => array(
            "type" => "string"
        ),
        "Address" => array(
            "type" => "\Iris\Address"
        ),
        "CustomerProvidedID" => array(
            "type" => "string"
        ),
        "CustomerName" => array(
            "type" => "string"
        )
    );

    public function __construct($sites, $data) {
        if(!isset($data) && isset($data->Id)) {
            $this->id = $data->Id;
        }
        $this->set_data($data);
        parent::_init($sites->get_rest_client(), $sites->get_relative_namespace());
    }

    public function save() {
        if($this->id)
            parent::put(null, "Site", $this->to_array());
        else
            parent::post(null, "Site", $this->to_array());
    }
}
