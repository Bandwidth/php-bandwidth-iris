<?php

class FieldRequiredException extends Exception {
    public function __construct($key) {
        $this->key = $key;
        parent::__construct("Field {$this->key} is required.");
    }
}

class FieldValidateInArrayException extends Exception {
    public function __construct($key, $arr) {
        $this->key = $key;
        $this->arr = $arr;
        parent::__construct("Field {$this->key} should have one of the following values: ".implode(", ", $this->arr));
    }
}

trait BaseModel {
    protected function set_data($data) {
        foreach($data as $key => $value) {
            if(!array_key_exists($key, $this->fields))
                continue;

            $classname = $this->fields[$key]["type"];

            if($classname === "string") {
                $this->{$key} = $value;
            }
            else {
                $this->{$key} = new $classname($value);
            }
        }
    }

    public function validate($key, $value, $validate) {
        switch($validate['type']) {
            case "in_array":
                if(!in_array($value, $validate['value']))
                    throw new FieldValidateInArrayException($key, $validate['value']);
                break;
        }
    }

    public function to_array() {
        $out = array();

        foreach($this->fields as $key => $rules) {
            if(property_exists($this, $key)) {
                $value = $this->{$key};

                if($rules['type'] === "string")
                    $out[$key] = $value;
                else
                    $out[$key] = $value->to_array();
            }
            else if(isset($rules['required']) && $rules['required'] === true) {
                throw new FieldRequiredException($key);
            }

            if(isset($rules['validate'])) {
                $this->validate($key, $value, $rules['validate']);
            }
        }

        return $out;
    }
}
