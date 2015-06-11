<?php

/**
 * @model Portins
 * https://api.test.inetwork.com/v1.0/accounts/orders
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

class Portins extends RestEntry {
    public function __construct($account) {
        parent::_init($account->get_rest_client(), $account->get_relative_namespace());
    }

    public function items($filters = Array()) {
        $data = parent::get('portins', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));
        return $data;
    }
}
