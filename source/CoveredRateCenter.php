<?php

namespace Iris;

final class CoveredRateCenters extends RestEntry{
    use BaseModel;

    public function __construct()
    {
        parent::_init(Null, 'coveredRateCenters');
    }
    
    public function get($state)
    {
        $data = parent::get(Null);
        return $data;
    }
}

final class CoveredRateCenter extends RestEntry{
    use BaseModel;

    public function __construct()
    {
        parent::_init(Null, 'coveredRateCenters');
    }
    
    public function get($state)
    {
        $data = parent::get(Null);
        return $data;
    }
}