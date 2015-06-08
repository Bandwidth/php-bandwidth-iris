<?php

namespace Iris;

/* TODO:  change on autoload compouser*/
$selfpath = dirname(__FILE__);
require_once($selfpath . '/RestEntry.php');


/* TODO:  remove build url from methods */

final class Tns extends RestEntry {

    /**
     *
     *
     */
    public function __construct($client, $spacename=Null)
    {
        parent::_init($client, $spacename);
    }

    /**
     * Account Info by Id
     * 
     */
    public function search($filters=Array())
    {
        $data = parent::get('', $filters);
        return $data;
    }
    
    public function get($telephone_number) 
    {
        $data = parent::get($telephone_number);
        $this->telephone_number = $telephone_number;
        return $data;
    }

    public function serviceNumbers($filters=Array()){
        $url = sprintf('%s/%s', $this->account_id, 'serviceNumbers');
        $data = parent::get($url);
        return $data;
    }
}