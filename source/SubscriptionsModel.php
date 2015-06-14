<?php

/**
 * @model Subsction
 * https://api.test.inetwork.com/v1.0/accounts/subscriptions
 *
 * 
 *
 * provides: 
 * get/0
 *
 */

namespace Iris;

final class Subscriptions extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array()) {
        
        $subscriptions = [];

        $data = parent::get('subscriptions', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));
        /* TODO:  correct struct */
        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount']) {
            foreach($data['ListOrderIdUserIdDate']['OrderIdUserIdDate'] as $subscribtion) {
                $subscriptions[] = new Subscription($this, $subscription);
            }
        }

        return $subscriptions;
    }

    public function get_by_id($id) {
        $sbc = new Subscription($this, array("Id" => $id));
        $sbc->get();
        return $order;
    }

    public function get_rest_client() {
        return $this->parent->get_rest_client();
    }

    public function get_relative_namespace() {
        return $this->parent->get_relative_namespace().'/subscriptions';
    }

    public function create($data) {
        $sbc = new Subscription($this, $data);
        $sbc->save();
        return $order;
    }
}

final class Subscription extends RestEntry{
    use BaseModel;

    protected $fields = array(
        /* TODO:  fill fields */
        "orderId" => array(
            "type" => "string"
        ),
        
    );
    
    
    public function __construct($subscriptions, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($subscriptions)) {
            $this->parent = $subscriptions;
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

    public function save() {
        if(!is_null($this->id))
            parent::put($this->id, "Order", $this->to_array());
        else {
            $header = parent::post(null, "Order", $this->to_array());
            $splitted = split("/", $header['Location']);
            $this->id = end($splitted);
        }
    }
}