<?php

/**
 * @model Libd
 * https://api.test.inetwork.com/v1.0/accounts/libds
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Libds extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array()) {

        $libds = [];

        $data = parent::get('libds', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));
        print_r($data); exit;

        /* TODO:  correct structure */
        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount']) {
            foreach($data['ListOrderIdUserIdDate']['OrderIdUserIdDate'] as $libd) {
                $orders[] = new Libd($this, $libd);
            }
        }

        return $libds;
    }

    public function get_by_id($id) {
        $order = new Libd($this, array("Id" => $id));
        $order->get();
        return $order;
    }

    public function get_appendix() {
        return '/orders';
    }

    public function create($data) {
        $order = new Libd($this, $data);
        $order->save();
        return $order;
    }
}

final class Libd extends RestEntry{
    use BaseModel;

    protected $fields = array(
        /* TODO:  fill fields */
        "orderId" => array(
            "type" => "string"
        ),
    );

    public function __construct($libds, $data)
    {
        if(isset($data)) {
            if(is_object($data) && $data->Id)
                $this->id = $data->Id;
            if(is_array($data) && isset($data['Id']))
                $this->id = $data['Id'];
        }
        $this->set_data($data);

        if(!is_null($libds)) {
            $this->parent = $libds;
            parent::_init($orders->get_rest_client(), $orders->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::get($this->id);
        $this->set_data($data['Order']);
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
