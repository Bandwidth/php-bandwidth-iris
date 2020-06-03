<?php
  
namespace Iris;

class EmergencyNotificationRecipient {
    use BaseModel;

    protected $fields = array(
        "Identifier" => array("type" => "string"),
        "CreatedDate" => array("type" => "string"),
        "LastModifiedDate" => array("type" => "string"),
        "ModifiedByUser" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "Type" => array("type" => "string"),
        "EmailAddress" => array("type" => "string"),
        "Callback" => array("type" => "\Iris\EnrCallback"),
        "Sms" => array("type" => "\Iris\EnrSms"),
        "Tts" => array("type" => "\Iris\EnrTts")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
