<?php

namespace Iris;

final class Cities extends RestEntry{
    use BaseModel;

    public function __construct()
    {
        parent::_init(Null, 'cities');
    }
    
    public function get($state)
    {
        $data = parent::get(Null);
        return $data;
    }
}