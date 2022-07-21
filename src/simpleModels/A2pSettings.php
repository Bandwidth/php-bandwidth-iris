<?php
  
namespace Iris;

class A2pSettings {
    use BaseModel;

    protected $fields = array(
        "MessageClass" => array("type" => "string"),
        "CampaignId" => array("type" => "string"),
        "Action" => array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}