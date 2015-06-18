<?php

/**
 * @model Disconnects
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

    /**
     * Create new disconnect
     * @param type $data
     * @return \Iris\Disconnect
     */
    public function create($data) {
        $disconnect = new Disconnect($this, $data);
        return $disconnect;
    }

    /**
     * Provide path of url
     * @return string
     */
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

    /**
     * Constructor
     * @param type $parent
     * @param type $data
     */
    public function __construct($parent, $data)
    {
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        $this->set_data($data);
        $this->notes = new Notes($this);
    }

    /**
     * Make POST request
     */
    public function save() {
        $data = parent::post(null, "DisconnectTelephoneNumberOrder", $this->to_array());
        $this->OrderStatus = new OrderRequestStatus($data);
        $this->OrderId = $this->OrderStatus->orderRequest->id;
    }

    /**
     * Get Entity Id
     * @return type
     * @throws Exception in case of OrderId is null
     */
    private function get_id() {
        if(is_null($this->OrderId))
            throw new Exception("You can't use this function without OrderId");
        return $this->OrderId;
    }

    /**
    * Get Notes of Entity
    * @return \Iris\Notes
    */
    public function notes() {
        return $this->notes;
    }
    /**
     * Provide relative url
     * @return string
     */
    public function get_appendix() {
        return '/'.$this->get_id();
    }
}
