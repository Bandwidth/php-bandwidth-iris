<?php

/**
 * @model Account
 * https://api.test.inetwork.com/v1.0/accounts/
 *
 * 
 *
 * provides: 
 * get/0
 *
 */
namespace Iris;


abstract class RestEntry{
    
    private $rest_client = Null;
    private $entry_name = Null;
    
    protected function _init()
    {
        $this->rest_client = PestClient($config->login, $config->password);
        $this->
        
    }
    
    protected function request() 
    {

        
    }

    protected function get_entry_name() {
        if ($this->entry_name)
            {
                return $this->entry_name;
            }
        else 
            {
                return sprintf('%ss', strtolower(get_class($this)));
            }
    }
}

final class Account extend RestEntry{

    /**
     *
     * GET 
     * Account()
     *
     */
    public function __construct($data=null) 
    {
        parent::_init();
        
    }


    /**
     * Return the balance
     * in float
     */
    public function get(account_id) {
        // test 9500249
        $data = $this->get(account_id);
        print_r($data->xpath('//Account/Contact'));
    }
}