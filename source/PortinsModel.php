<?php

/**
 * @model Portins
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

class Portins extends RestEntry {
    public function __construct($account) {
        $this->parent = $account;
        parent::_init($account->get_rest_client(), $account->get_relative_namespace());
    }

    public function get($filters = Array()) {
        $out = [];

        $portins = parent::get('portins', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($portins->lnpPortInfoForGivenStatus && is_array($portins->lnpPortInfoForGivenStatus)) {
            foreach($portins->lnpPortInfoForGivenStatus as $portin) {
                $out[] = new Portin($this, $portin);
            }
        }

        return $out;
    }

    public function create($data) {
        $portin = new Portin($this, $data);
        $portin->save();
        return $portin;
    }

    public function totals()
    {
        $url = sprintf('%s/%s', 'portins', 'totals');
        $data = parent::get($url);
        return $data;
    }

    public function get_appendix() {
        return '/portins';
    }
}

class Portin extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "OrderId" => array("type" => "string"),
        "Status" => array("type" => "string"),
        "Errors" => array("type" => "string"),
        "ProcessingStatus" => array("type" => "string"),
        "RequestedFocDate" => array("type" => "string"),
        "WirelessInfo" => array("type" => "Iris\WirelessInfo"),
        "LosingCarrierName" => array("type" => "string"),
        "LastModifiedDate" => array("type" => "string"),
        "userId" => array("type" => "string"),
        "BillingTelephoneNumber" => array("type" => "string"),
        "Subscriber" => array("type" => "Iris\Subscriber"),
        "LoaAuthorizingPerson" => array("type" => "string"),
        "ListOfPhoneNumbers" => array("type" => "Iris\Phones"),
        "SiteId" => array("type" => "string"),
        "Triggered" => array("type" => "string"),
        "BillingType" => array("type" => "string")
    );

    public function __construct($portins, $data) {
        $this->set_data($data);
        parent::_init($portins->get_rest_client(), $portins->get_relative_namespace());
    }

    public function save() {
        $data = parent::post(null, "LnpOrder", $this->to_array());
        $this->set_data($data);
    }

    public function loas_send($file) {
        $body = fopen($file, 'r');
        $url = sprintf('%s/%s', $this->get_id(), 'loas');
        $data = parent::raw_post($url, $body);
    }
    public function loas_update($file, $filename) {
        $body = fopen($file, 'r');
        $url = sprintf('%s/%s/%s', $this->get_id(), 'loas', $filename);
        $data = parent::raw_put($url, $body);
    }
    public function loas_delete($filename) {
        $url = sprintf('%s/%s/%s', $this->get_id(), 'loas', $filename);
        $data = parent::delete($url);
    }

    public function get() {
        $this->data = parent::get($this->id);
        return $this->data;
    }

    public function areaCodes()
    {
        $url = sprintf('%s/%s', $this->id, 'areaCodes');
        $data = parent::get($url);
        return $data;
    }
    public function history()
    {
        $url = sprintf('%s/%s', $this->id, 'history');
        $data = parent::get($url);
        return $data;
    }
    public function npaNxx()
    {
        $url = sprintf('%s/%s', $this->id, 'npaNxx');
        $data = parent::get($url);
        return $data;
    }
    public function tns()
    {
        $url = sprintf('%s/%s', $this->id, 'tns');
        $data = parent::get($url);
        return $data;
    }
    public function totals()
    {
        $url = sprintf('%s/%s', $this->id, 'totals');
        $data = parent::get($url);
        return $data;
    }
    public function activationStatus()
    {
        $url = sprintf('%s/%s', $this->id, 'activationStatus');
        $data = parent::get($url);
        return $data;
    }
    public function notes()
    {
        $url = sprintf('%s/%s', $this->id, 'notes');
        $data = parent::get($url);
        return $data;
    }

    private function get_id() {
        if(is_null($this->OrderId))
            throw new Exception("You can't use this function without OrderId");
        return $this->OrderId;
    }
    public function get_appendix() {
        return '/'.$this->get_id();
    }

}
