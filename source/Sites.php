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
}

class Site extends RestEntry {
    private $fields = array(
        "Name", "Description", "Address", "CustomerProvidedID", "CustomerName"
    );

    public function __construct($sites, $data) {
        $this->id = $data->Id;
        $this->set_data($data);
        parent::_init($sites->get_rest_client(), $sites->get_relative_namespace());
    }

    private function set_data($data) {
        foreach($data as $key => $value) {
            echo $key;
            if(in_array($key, $this->fields)) {
                $this->{$key} = $value;
            }
        }
    }

}
