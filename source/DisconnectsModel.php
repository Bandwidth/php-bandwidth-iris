<?php

/**
 * @model Order
 * https://api.test.inetwork.com/v1.0/accounts/orders
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Disconnects extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array())
    {
        $disconnects = [];

        $data = parent::get('disconnects', Array("page"=> 1, "size" => 30), Array("page", "size"));
        print_r($data); exit;

        if($data['TotalCount']) {
            return $data['TelephoneNumbers']['TelephoneNumber'];
        }
        return $tns;
    }

    public function totals()
    {
        $url = sprintf('%s/%s', 'inserviceNumbers', 'totals');
        $data = parent::get($url);
        return $data['Count'];
    }

    public function get_by_tn($tn)
    {
        $url = sprintf('%s/%s', 'inserviceNumbers', $tn);
        $data = parent::get($url);
        return $data;
    }

    public function get_appendix() {
        return '/inserviceNumbers';
    }

    public function create($data) {
        $disconnect_order = new DisconnectOrder($this, $data);
        $disconnect_order->save();
        return $order;
    }
}


final class DisconnectOrder extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "id" => array(
            "type" => "string"
        ),
        "name" => array(
            "type" => "string"
        ),
        "DisconnectTelephoneNumberOrderType" => array(
            "type" => "string"
        ),
    );

    public function __construct($disconnect_orders, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($disconnect_orders)) {
            $this->parent = $disconnect_orders;
            parent::_init($disconnect_orders->get_rest_client(), $disconnect_orders->get_relative_namespace());
        }

    }

    public function save() {
        if(!is_null($this->id))
            parent::put($this->id, "Order", $this->to_array());
        else {
            $header = parent::post(null, "DisconnectTelephoneNumberOrder", $this->to_array());
            $splitted = split("/", $header['Location']);
            $this->id = end($splitted);
        }
    }
}
