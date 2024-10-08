<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class NumberPortabilityResponse {
    use BaseModel;

    protected $fields = array(
        "PortType" => array("type" => "string"),
        "PortableNumbers" => array("type" => "\Iris\TnList"),
        "SupportedRateCenters" => array("type" => "string"),
        "SupportedTollFreeNumbers" => array("type" => "string"),
        "UnsupportedRateCenters" => array("type" => "\Iris\RateCentersS"),
        "PartnerSupportedRateCenters" => array("type" => "\Iris\RateCentersS"),
        "SupportedLosingCarriers" => array("type" => "\Iris\SupportedLosingCarriers")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}


#[AllowDynamicProperties]
class RateCentersS {
    use BaseModel;

    protected $fields = array(
        "RateCenterGroup" => array("type" => "\Iris\RateCenterGroup"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}


#[AllowDynamicProperties]
class RateCenterGroup {
    use BaseModel;

    protected $fields = array(
        "RateCenter" => array("type" => "string"),
        "City" => array("type" => "string"),
        "State" => array("type" => "string"),
        "LATA" => array("type" => "string"),
        "TnList" => array("type" => "\Iris\TnList"),
        "Tiers" => array("type" => "\Iris\Tiers")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}


#[AllowDynamicProperties]
class SupportedLosingCarriers {
    use BaseModel;

    protected $fields = array(
        "LosingCarrierTnList" => array("type" => "\Iris\LosingCarrierTnList"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}


#[AllowDynamicProperties]
class LosingCarrierTnList {
    use BaseModel;

    protected $fields = array(
        "LosingCarrierSPID" => array("type" => "string"),
        "LosingCarrierName" => array("type" => "string"),
        "LosingCarrierIsWireless" => array("type" => "string"),
        "LosingCarrierAccountNumberRequired" => array("type" => "string"),
        "LosingCarrierMinimumPortingInterval" => array("type" => "string"),
        "TnList" => array("type" => "\Iris\TnList"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
