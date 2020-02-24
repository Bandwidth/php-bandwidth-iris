<?php
  
namespace Iris;

class Csr {
    use BaseModel;

    protected $fields = array(
        "CustomerOrderId" => array("type" => "string"),
        "WorkingOrBillingTelephoneNumber" => array("type" => "string"),
        "AccountNumber" => array("type" => "string"),
        "AccountTelephoneNumber" => array("type" => "string"),
        "EndUserName" => array("type" => "string"),
        "AuthorizingUserName" => array("type" => "string"),
        "CustomerCode" => array("type" => "string"),
        "EndUserPIN" => array("type" => "string"),
        "EndUserPassword" => array("type" => "string"),
        "AddressLine1" => array("type" => "string"),
        "City" => array("type" => "string"),
        "State" => array("type" => "string"),
        "ZIPCode" => array("type" => "string"),
        "TypeOfService" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
