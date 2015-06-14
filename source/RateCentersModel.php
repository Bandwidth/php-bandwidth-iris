<?php

namespace Iris;

final class RateCenters extends RestEntry{
    use BaseModel;

    public function __construct()
    {
        parent::_init(Null, 'rateCenters');
    }
    
    public function get($state)
    {
        $data = parent::get(Null);
        return $data;
    }
}