<?php

namespace Iris;

class Subscriber {
    use BaseModel;

    protected $fields = array(
        "SubscriberType" => array("type" => "string"),
        "BusinessName" => array("type" => "string"),
        "ServiceAddress" => array("type" => "\Iris\ServiceAddress"),
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
