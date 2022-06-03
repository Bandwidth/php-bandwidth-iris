<?php

namespace Iris;

class CallbackCredentials {
    use BaseModel;

    protected $fields = array(
        "BasicAuthentication" => array("type" => "\Iris\BasicAuthentication"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
