<?php

namespace Iris;

class EmergencyNotificationRecipients {
    use BaseModel;

    protected $fields = array(
        "Links" => array("type" => "\Iris\Links"),
        "EmergencyNotificationRecipients" => array("type" => "\Iris\EmergencyNotificationRecipient")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
