<?php
  
namespace Iris;

class CsrNote {
    use BaseModel;

    protected $fields = array(
        "Id" => array("type" => "string"),
        "UserId" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "LastDateModifier" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
