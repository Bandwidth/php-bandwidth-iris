<?php
  
namespace Iris;

class NotesList {
    use BaseModel;

    protected $fields = array(
        "Note" => array("type" => "\Iris\Note")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
