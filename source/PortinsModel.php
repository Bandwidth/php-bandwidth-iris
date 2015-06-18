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

    /**
    * Create new Portin
    * @params array $data
    * @return \Iris\Portin
    */
    public function create($data) {
        return new Portin($this, $data);
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
        "Status" => array("type" => "Iris\Status"),
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

    public function __construct($parent, $data) {
        $this->set_data($data);
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        $this->notes = null;
    }

    public function save() {
        if(is_null($this->OrderId)) {
            $data = parent::post(null, "LnpOrder", $this->to_array());
        } else {
            $data = parent::put($this->get_id(), "LnpOrderSupp", $this->to_array());
        }
        $this->set_data($data);
    }

    public function delete() {
        parent::delete($this->get_id());
    }

    public function loas_send($file) {
        $body = fopen($file, 'r');
        $url = sprintf('%s/%s', $this->get_id(), 'loas');
        parent::raw_post($url, $body);
    }
    public function loas_update($file, $filename) {
        $body = fopen($file, 'r');
        $url = sprintf('%s/%s/%s', $this->get_id(), 'loas', $filename);
        parent::raw_put($url, $body);
    }
    public function loas_delete($filename) {
        $url = sprintf('%s/%s/%s', $this->get_id(), 'loas', $filename);
        parent::delete($url);
    }

    public function get_loas($metadata) {
        $url = sprintf('%s/%s', $this->get_id(), 'loas');
        $query = array();

        if($metadata) {
            $query['metadata'] = 'true';
        }

        return (object)parent::get($url, $query);
    }

    public function get_metadata($filename) {
        $url = sprintf('%s/%s/%s/metadata', $this->get_id(), 'loas', $filename);
        $data = parent::get($url);
        return new FileMetaData($data);
    }

    public function set_metadata($filename, FileMetaData $meta) {
        $url = sprintf('%s/%s/%s/metadata', $this->get_id(), 'loas', $filename);
        parent::put($url, "FileMetaData", $meta->to_array());
    }

    public function delete_metadata($filename) {
        $url = sprintf('%s/%s/%s/metadata', $this->get_id(), 'loas', $filename);
        parent::delete($url);
    }

    public function get_activation_status() {
        $url = sprintf('%s/%s', $this->get_id(), 'activationStatus');
        $data = parent::get($url);
        return new ActivationStatus($data['ActivationStatus']);
    }

    public function set_activation_status(ActivationStatus $data) {
        $url = sprintf('%s/%s', $this->get_id(), 'activationStatus');
        $data = parent::post($url, "ActivationStatus", $data->to_array());
        return new ActivationStatus($data['ActivationStatus']);
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

    /**
    * Get Notes of Entity
    * @return \Iris\Notes
    */
    public function notes() {
        if(is_null($this->notes))
            $this->notes = new Notes($this);

        return $this->notes;
    }
    /**
     * Get Entity Id
     * @return type
     * @throws Exception in case of OrderId is null
     */
    private function get_id() {
        if(is_null($this->OrderId))
            throw new \Exception("You can't use this function without OrderId");
        return $this->OrderId;
    }
    /**
     * Provide relative url
     * @return string
     */
    public function get_appendix() {
        return '/'.$this->get_id();
    }

}
