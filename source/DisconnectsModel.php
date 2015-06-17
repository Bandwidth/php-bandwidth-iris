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

class Disconnects extends RestEntry{

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
    public function create($data) {
        $disconnect = new Disconnect($this, $data);
        $disconnect->save();
        return $disconnect;
    }

    public function get_appendix() {
        return '/disconnects';
    }

}


class Disconnect extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "OrderId" => array("type" => "string"),
        "name" => array("type" => "string"),
        "CustomerOrderId" => array("type" => "string"),
        "DisconnectTelephoneNumberOrderType" => array("type" => "\Iris\TelephoneNumberList"),
        "OrderStatus" => array("type" => "\Iris\OrderRequestStatus")
    );

    public function __construct($parent, $data)
    {
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        $this->set_data($data);
    }

    public function save() {
        $data = parent::post(null, "DisconnectTelephoneNumberOrder", $this->to_array());
        $this->OrderStatus = new OrderRequestStatus($data);
        $this->OrderId = $this->OrderStatus->orderRequest->id;
    }
}
