<?php

/**
 * @model Tnsreserv
 * https://api.test.inetwork.com/v1.0/accounts/tnsreservation
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Tnsreservations extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get_by_id($id) {
        $sbc = new Tnsreservation($this, array("Id" => $id));
        $sbc->get();
        return $order;
    }

    public function get_appendix() {
        return '/subscriptions';
    }

    public function create($data) {
        $sbc = new Subscription($this, $data);
        $sbc->save();
        return $order;
    }
}

final class Tnsreservation extends RestEntry{
    use BaseModel;

    protected $fields = array(
        /* TODO:  fill fields */
        "orderId" => array(
            "type" => "string"
        ),

    );

    public function __construct($tnreservations, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($tnreservations)) {
            $this->parent = $tnreservations;
            parent::_init($subscriptions->get_rest_client(), $subscriptions->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::get($this->id);
        /* TODO:  correct key*/
        $this->set_data($data['Subscription']);
    }
    public function delete() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');
        parent::delete($this->id);
    }
}
