<?php
  
namespace Iris;

class EmergencyNotificationGroupOrder {
    use BaseModel;

    protected $fields = array(
        "OrderId" => array("type" => "string"),
        "OrderCreatedDate" => array("type" => "string"),
        "CreatedBy" => array("type" => "string"),
        "ProcessingStatus" => array("type" => "string"),
        "CustomerOrderId" => array("type" => "string"),
        "AddedEmergencyNotificationGroup" => array("type" => "\Iris\AddedEmergencyNotificationGroup")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class AddedEmergencyNotificationGroup {
    use BaseModel;

    protected $fields = array(
        "Identifier" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "AddedEmergencyNotificationRecipients" => array("type" => "\Iris\AddedEmergencyNotificationRecipient")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class AddedEmergencyNotificationRecipient {
    use BaseModel;

    protected $fields = array(
        "Identifier" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class EmergencyNotificationGroupOrders {
    use BaseModel;

    protected $fields = array(
        "Links" => array("type" => "\Iris\Links"),
        "EmergencyNotificationGroupOrders" => array("type" => "\Iris\EmergencyNotificationGroupOrder")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
