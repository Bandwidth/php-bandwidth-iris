<?php

namespace Iris;

class CsrResponse {
    use BaseModel;
    
    protected $fields = array(
        "OrderId" => array("type" => "string"),
        "Status" => array("type" => "string"),
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
        "TypeOfService" => array("type" => "string"),
        "Errors" => array("type" => "\Iris\Error")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}  
