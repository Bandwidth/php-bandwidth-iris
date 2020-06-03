<?php
  
namespace Iris;

class EmergencyNotificationEndpointOrder {
    use BaseModel;

    protected $fields = array(
        "OrderId" => array("type" => "string"),
        "OrderCreatedDate" => array("type" => "string"),
        "CreatedBy" => array("type" => "string"),
        "ProcessingStatus" => array("type" => "string"),
        "CustomerOrderId" => array("type" => "string"),
        "EmergencyNotificationEndpointAssociations" => array("type" => "\Iris\EmergencyNotificationEndpointAssociation")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class EmergencyNotificationEndpointAssociation {
    use BaseModel;

    protected $fields = array(
        "EmergencyNotificationGroup" => array("type" => "\Iris\EmergencyNotificationGroup"),
        "AddedAssociations" => array("type" => "\Iris\AddedAssociations")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class AddedAssociations {
    use BaseModel;

    protected $fields = array(
        "EepToEngAssociations" => array("type" => "\Iris\EepToEngAssociations")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class EepToEngAssociations {
    use BaseModel;

    protected $fields = array(
        "EepTns" => array("type" => "\Iris\EepTns"),
        "EepAeuiIds" => array("type" => "\Iris\EepAeuiIds")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class EepTns {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumber" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class EepAeuiIds {
    use BaseModel;

    protected $fields = array(
        "Identifier" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class EmergencyNotificationEndpointOrders {
    use BaseModel;

    protected $fields = array(
        "Links" => array("type" => "\Iris\Links"),
        "EmergencyNotificationEndpointOrders" => array("type" => "\Iris\EmergencyNotificationEndpointOrder")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
