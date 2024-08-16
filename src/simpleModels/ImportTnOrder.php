<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class ImportTnOrder {
    use BaseModel;

    protected $fields = array(
        "CustomerOrderId" => array("type" => "string"),
        "OrderCreateDate" => array("type" => "string"),
        "AccountId" => array("type" => "string"),
        "CreatedByUser" => array("type" => "string"),
        "OrderId" => array("type" => "string"),
        "LastModifiedDate" => array("type" => "string"),
        "SiteId" => array("type" => "string"),
        "SipPeerId" => array("type" => "string"),
        "Subscriber" => array("type" => "\Iris\Subscriber"),
        "LoaAuthorizingPerson" => array("type" => "string"),
        "ProcessingStatus" => array("type" => "string"),
        "Errors" => array("type" => "\Iris\Error"),
        "TelephoneNumbers" => array("type" => "\Iris\Phones")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
