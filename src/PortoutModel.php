<?php

/**
 * @model Portout
 * https://api.test.inetwork.com/v1.0/accounts/portouts
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Portouts extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array())
    {
        $disconnects = [];

        $data = parent::get('portouts', Array("page"=> 1, "size" => 30), Array("page", "size"));
        print_r($data); exit;

        if($data['TotalCount']) {
            return $data['TelephoneNumbers']['TelephoneNumber'];
        }
        return $tns;
    }

    /**
    * Create new Portin
    * @params array $data
    * @return \Iris\Portin
    */
    public function create($data) {
        return new Portout($this, $data);
    }

    public function get_by_id($id)
    {
        $url = sprintf('%s/%s', 'portouts', $id);
        $data = parent::get($url);
        return $data;
    }

    public function get_appendix() {
        return '/portouts';
    }

}

class Portout extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "OrderId" => array("type" => "string")
    );

    public function __construct($parent, $data) {
        $this->set_data($data);
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        $this->notes = null;
    }

    /**
    * Get Notes of Entity
    * @return \Iris\Notes
    */
    public function notes() {
        if(is_null($this->notes))
            $this->notes = new Notes($this);
        return $this->notes;
    }
    /**
     * Get Entity Id
     * @return type
     * @throws Exception in case of OrderId is null
     */
    private function get_id() {
        if(!isset($this->OrderId))
            throw new Exception("You can't use this function without OrderId");
        return $this->OrderId;
    }
    /**
     * Provide relative url
     * @return string
     */
    public function get_appendix() {
        return '/'.$this->get_id();
    }

}
