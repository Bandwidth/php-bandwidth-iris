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

final class Orders extends RestEntry{

  //_id, $client=Null, $namespace='accounts/{$this->account_id}/orders'
    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array()) {
        
        $orders = [];

        $data = parent::get('orders', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount']) {
            foreach($data['ListOrderIdUserIdDate']['OrderIdUserIdDate'] as $order) {
                $orders[] = new Order($this, $order);
            }
        }

        return $orders;
    }

    public function get_by_id($id) {
        $order = new Order($this, array("Id" => $id));
        $order->get();
        return $order;
    }

    public function get_rest_client() {
        return $this->parent->get_rest_client();
    }

    public function get_relative_namespace() {
        return $this->parent->get_relative_namespace().'/orders';
    }

    public function create($data) {
        $order = new Order($this, $data);
        $order->save();
        return $order;
    }
}

final class Order extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "orderId" => array(
            "type" => "string"
        ),
        "Quantity" => array(
            "type" => "string"
        ),
        "Name" => array(
            "type" => "string"
        ),
        "CustomerOrderId" => array(
            "type" => "string"
        ),
        "SiteId" => array(
            "type" => "string"
        ),
        "PeerId" => array(
            "type" => "string"
        )
        ,
        "PartialAllowed" => array(
            "type" => "string"
        )
        ,
        "BackOrderRequested" => array(
            "type" => "string"
         ),
        "AreaCodeSearchAndOrderType" => array(
            "type" => "string"
        )
    );
    
    
    public function __construct($orders, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($orders)) {
            $this->parent = $orders;
            parent::_init($orders->get_rest_client(), $orders->get_relative_namespace());
        }
        
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::get($this->id);
        $this->set_data($data['Order']);
    }
    public function delete() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');
        parent::delete($this->id);
    }

    public function save() {
        if(!is_null($this->id))
            parent::put($this->id, "Order", $this->to_array());
        else {
            $header = parent::post(null, "Order", $this->to_array());
            $splitted = split("/", $header['Location']);
            $this->id = end($splitted);
        }
    }
    
    public function areCodes()
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
    public function notes()
    {
        $url = sprintf('%s/%s', $this->id, 'notes');
        $data = parent::get($url);
        return $data;
    }
}