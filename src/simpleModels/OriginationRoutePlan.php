<?php
  
namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class OriginationRoutePlan {
    use BaseModel;

    protected $fields = array(
        "Id" => array("type" => "string"),
        "Route" => array("type" => "\Iris\Route"),
        "Action" => array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
