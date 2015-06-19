<?php

namespace Iris;

final class Tns extends RestEntry {
    public function __construct($parent, $client=null, $namespace="")
    {
        if($parent) {
            $this->parent = $parent;
            parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
        }
        else {
            parent::_init($client, $namespace="");
        }
    }

    public function get_from_sippeer() {
        $tns = [];
        $data = parent::get('tns');

        if($data['SipPeerTelephoneNumbers'] && $data['SipPeerTelephoneNumbers']["SipPeerTelephoneNumber"]) {
            $items =  $data['SipPeerTelephoneNumbers']["SipPeerTelephoneNumber"];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $tn) {
                $tns[] = new Tn($this, $tn);
            }
        }
        return $tns;
    }

    public function get($filters = Array()) {
        if(isset($this->parent) && $this->parent instanceof \Iris\Sippeer) {
            return $this->get_from_sippeer();
        }
        $tns = [];
        $data = parent::get('tns', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($data['TelephoneNumberCount'] && $data['TelephoneNumbers'] && $data['TelephoneNumbers']["TelephoneNumber"]) {
            $items =  $data['TelephoneNumbers']["TelephoneNumber"];

            if($data['TelephoneNumberCount'] == "1")
                $items = [ $items ];

            foreach($items as $tn) {
                $tns[] = new Tn($this, $tn);
            }
        }
        return $tns;
    }

    public function create($data) {
        return new Tn($this, $data);
    }

    public function tn($id) {
        $tn = new Tn($this, array("FullNumber" => $id));
        $tn->get();
        return $tn;
    }

    public function get_rest_client() {
        if(isset($this->parent)) {
            return $this->parent->client;
        } else {
            return $this->client;
        }
    }

    public function get_relative_namespace() {
        return (isset($this->parent) ? $this->parent->get_relative_namespace() : '').'/tns';
    }
}


final class Tn extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "City" => array(
            "type" => "string"
        ),
        "Lata" => array(
            "type" => "string"
        ),
        "State" => array(
            "type" => "string"
        ),
        "FullNumber" => array(
            "type" => "string"
        ),
        "Tier" => array(
            "type" => "string"
        ),
        "VendorId" => array(
            "type" => "string"
        ),
        "VendorName" => array(
            "type" => "string"
        ),
        "RateCenter" => array(
            "type" => "string"
        ),
        "Status" => array(
            "type" => "string"
        ),
        "AccountId" => array(
            "type" => "string"
        ),
        "LastModified" => array(
            "type" => "string"
        ),
        "OrderCreateDate" => array("type" => "string"),
        "OrderId" => array("type" => "string"),
        "OrderType" => array("type" => "string"),
        "SiteId" =>  array("type" => "string"),
        "AccountId" => array("type" => "string"),
        "CallForward" => array("type" => "string")
    );

    public function __construct($tns, $data) {
        $this->set_data($data);
        $this->parent = $tns;
        parent::_init($tns->get_rest_client(), $tns->get_relative_namespace());
    }

    public function get() {
        $data = parent::get($this->get_id());
        if(isset($data["SipPeerTelephoneNumber"]))
            $data = $data['SipPeerTelephoneNumber'];
        $this->set_data($data);
    }

    public function site() {
        if(!isset($this->AccountId))
            $this->get();

        $url = sprintf("%s/%s", $this->get_id(), "sites");
        $data = parent::get($url);
        $account = new Account($this->AccountId, $this->parent->get_rest_client());
        return $account->sites()->create($data);
    }

    public function sippeer() {
        if(!isset($this->AccountId) || !isset($this->SiteId))
            $this->get();

        $url = sprintf("%s/%s", $this->get_id(), "sippeers");
        $data = parent::get($url);
        $account = new Account($this->AccountId, $this->parent->get_rest_client());
        return $account->sites()->create(["Id" => $this->SiteId])->sippeers()->create(["PeerId" => $data["Id"], "PeerName" => $data["Name"]]);
    }

    public function set_tn_options(SipPeerTelephoneNumber $data) {
        if(!($this->parent->parent instanceof Sippeer))
            throw new \Exception("You should get TN from sippeer");
        parent::post($this->get_id(), "SipPeerTelephoneNumbers", $data);
    }

    public function get_id() {
        if(!isset($this->FullNumber))
            throw new \Exception("You should set FullNumber");
        return $this->FullNumber;
    }

    public function get_appendix() {
        return '/'.$this->get_id();
    }

}
