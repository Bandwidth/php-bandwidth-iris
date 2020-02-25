<?php
  
namespace Iris;

class CsrNotesList {
    use BaseModel;

    protected $fields = array(
        "Note" => array("type" => "\Iris\CsrNote")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
