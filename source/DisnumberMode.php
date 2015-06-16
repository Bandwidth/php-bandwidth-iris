<?php

/**
 * @model Discnumbers
 * https://api.test.inetwork.com/v1.0/accounts/discnumbers
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Discnumbers extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array()) {

        $discnumbers = [];

        $data = parent::get('discnumbers', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));
        print_r($data); exit;

        if($data['TotalCount']) {
            foreach($data['TelephoneNumbers'] as $discnumber) {
                $discnumbers[] = $discnumber;
            }
        }
        return $discnumbers;
    }

    public function get_appendix() {
        return '/discnumbers';
    }

    public function totals()
    {
        $url = sprintf('%s/%s', 'discnumbers', 'totals');
        $data = parent::get($url);
        return $data;
    }
}
