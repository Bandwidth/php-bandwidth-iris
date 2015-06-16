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
    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
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

    public function get_by_id($id) {
        $site = new Site($this, array("Id" => $id));
        $site->get();
        return $site;
    }

    public function get_appendix() {
        return '/sites';
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
        "id" => array(
            "type" => "string"
        ),
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
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($sites)) {
            $this->parent = $sites;
            parent::_init($sites->get_rest_client(), $sites->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::get($this->id);
        $this->set_data($data['Site']);
    }
    public function delete() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');
        parent::delete($this->id);
    }

    public function save() {
        if(!is_null($this->id))
            parent::put($this->id, "Site", $this->to_array());
        else {
            $header = parent::post(null, "Site", $this->to_array());
            $splitted = split("/", $header['Location']);
            $this->id = end($splitted);
        }
    }

    public function sippeers() {
        if(!isset($this->sippeers))
            $this->sippeers = new Sippeers($this);
        return $this->sippeers;
    }

    public function get_appendix() {
        return '/'.$this->id;
    }
}
