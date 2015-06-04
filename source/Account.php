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

final class Account {

    /**
     *
     * Init forms:
     *
     * GET 
     * Account()
     *
     */
    public function __construct($data=null) {
      $data = Ensure::Input($data);

      parent::_init($data, new DependsResource,
        new LoadsResource(array("primary" => "GET", "id" => "id", "init" => "", "silent" => false)),
        new SchemaResource(array("fields" => array( "balance", "accountType"), "needs" => array("balance", "accountType")),
        new SubFunctionResource(array("term" => "transactions", "type" => "get"))
      ));
    }


    /**
     * Return the balance
     * in float
     */
    public function get(account_id) {
      return $this->client()->get('/accounts/account_id');
      $pest = new PestXML('https://api.test.inetwork.com/v1.0');
      $pest->setupAuth('byo_dev', 'yBf7QzGj3Gzsovf');
      $pest->curl_opts[CURLOPT_FOLLOWLOCATION] = false;
      $data = $pest->get('/accounts/9500249');
      print_r($data->xpath('//Account/Contact'));
    }
}

