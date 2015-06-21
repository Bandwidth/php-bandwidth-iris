<?php

/**
 * @model Order
 * https://api.test.inetwork.com/v1.0/accounts/orders
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class InserviceNumbers extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array())
    {
        $tns = [];

        $data = parent::get('inserviceNumbers', $filters, $filters);
        if($data['TotalCount']) {
            return $data['TelephoneNumbers']['TelephoneNumber'];
        }
        return $tns;
    }

    public function totals()
    {
        $url = sprintf('%s/%s', 'inserviceNumbers', 'totals');
        $data = parent::get($url);
        return $data['Count'];
    }

    public function get_by_tn($tn)
    {
        $url = sprintf('%s/%s', 'inserviceNumbers', $tn);
        $data = parent::get($url);
        return $data;
    }

    public function get_appendix() {
        return '/inserviceNumbers';
    }

}
