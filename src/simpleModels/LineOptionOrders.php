<?php

namespace Iris;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class TnLineOptions {
    use BaseModel;

    protected $fields = array(
        "TnLineOptions" => array("type" => "\Iris\TnLineOption")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

#[AllowDynamicProperties]
class TnLineOption {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumber" => array("type" => "string"),
        "CallingNameDisplay" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}


#[AllowDynamicProperties]
class TnLineOptionOrderResponse {
    use BaseModel;

    protected $fields = array(
        "LineOptions" => array("type" => "\Iris\LineOption")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

#[AllowDynamicProperties]
class LineOption {
    use BaseModel;

    protected $fields = array(
        "CompletedNumbers" => array("type" => "\Iris\TelephoneNumbers"),
        "Errors" => array("type" => "\Iris\Error")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
