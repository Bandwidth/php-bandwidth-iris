<?php
  
namespace Iris;

class FileListResponse {
    use BaseModel;

    protected $fields = array(
        "fileCount" => array("type" => "integer"),
        "fileNames" => array("type" => "string"),
        "resultCode" => array("type" => "integer"),
        "resultMessage" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
