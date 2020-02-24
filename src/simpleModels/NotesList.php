<?php
  
namespace Iris;

class NotesList {
    use BaseModel;

    protected $fields = array(
        "Note" => array("type" => "\Iris\NotesListNote")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class NotesListNote {
    use BaseModel;

    protected $fields = array(
        "Id" => array("type" => "string"),
        "UserId" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "LastDateModifier" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
