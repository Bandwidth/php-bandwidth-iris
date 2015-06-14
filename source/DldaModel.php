<?php

/**
 * @model Dldas
 * https://api.test.inetwork.com/v1.0/accounts/dldas
 *
 * 
 *
 * provides: 
 * get/0
 *
 */

namespace Iris;

final class Dldas extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array()) {
        
        $dldas = [];

        $data = parent::get('dldas', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));
        print_r($data); exit;
        
        /* TODO:  correct structure */
        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount']) {
            foreach($data['ListOrderIdUserIdDate']['OrderIdUserIdDate'] as $dlda) {
                $dldas[] = new Dlda($this, $dlda);
            }
        }

        return $libds;
    }

    public function get_by_id($id) {
        $order = new Dlda($this, array("Id" => $id));
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
        $order = new Dlda($this, $data);
        $order->save();
        return $order;
    }
}

final class Dlda extends RestEntry{
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

        if(!is_null($dldas)) {
            $this->parent = $dldas;
            parent::_init($orders->get_rest_client(), $orders->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->id))
            throw new \Exception('Id should be provided');

        $data = parent::get($this->id);
        $this->set_data($data['Dlda']);
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

    public function history()
    {
        $url = sprintf('%s/%s', $this->id, 'history');
        $data = parent::get($url);
        return $data;
    }
}