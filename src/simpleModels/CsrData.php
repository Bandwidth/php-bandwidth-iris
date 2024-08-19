<?php
  
namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class CsrData {
    use BaseModel;

    protected $fields = array(
        "WorkingTelephoneNumber" => array("type" => "string"),
        "AccountNumber" => array("type" => "string"),
        "CustomerName" => array("type" => "string"),
        "ServiceAddress" => array("type" => "\Iris\ServiceAddress"),
        "WorkingTelephoneNumbersOnAccount" => array("type" => "\Iris\TelephoneNumbers")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
