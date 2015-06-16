<?php

/**
 * @model Portout
 * https://api.test.inetwork.com/v1.0/accounts/portouts
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Portouts extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function get($filters = Array())
    {
        $disconnects = [];

        $data = parent::get('portouts', Array("page"=> 1, "size" => 30), Array("page", "size"));
        print_r($data); exit;

        if($data['TotalCount']) {
            return $data['TelephoneNumbers']['TelephoneNumber'];
        }
        return $tns;
    }

    public function get_by_id($id)
    {
        $url = sprintf('%s/%s', 'portouts', $id);
        $data = parent::get($url);
        return $data;
    }

    public function get_appendix() {
        return '/portouts';
    }

}
