<?php

namespace Iris;

class EmergencyNotificationGroup {
    use BaseModel;

    protected $fields = array(
        "Identifier" => array("type" => "string"),
        "CreatedDate" => array("type" => "string"),
        "ModifiedBy" => array("type" => "string"),
        "ModifiedDate" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "EmergencyNotificationRecipients" => array("type" => "\Iris\EmergencyNotificationRecipient")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class EmergencyNotificationGroups {
    use BaseModel;

    protected $fields = array(
        "Links" => array("type" => "\Iris\Links"),
        "EmergencyNotificationGroups" => array("type" => "\Iris\EmergencyNotificationGroup")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
