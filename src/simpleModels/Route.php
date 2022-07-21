<?php
  
namespace Iris;

class Route {
    use BaseModel;

    protected $fields = array(
        "Endpoint" => array("type" => "string"),
        "Priority" => array("type" => "int"),
        "Weight" => array("type" => "int"),
        "EndpointType" => array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}