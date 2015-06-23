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

        if(isset($data['TelephoneNumbers']) && isset($data['TelephoneNumbers']["TelephoneNumber"])) {
            $items =  $data['TelephoneNumbers']["TelephoneNumber"];

            if($this->is_assoc($items))
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
        "CallForward" => array("type" => "string"),
        "Features" => array("type" => "\Iris\Features"),
        "TnAttributes" => array("type" => "\Iris\TnAttributes")
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

    public function tndetails() {
        $url = sprintf("%s/%s", $this->get_id(), "tndetails");
        $data = parent::get($url);
        $data = $data['TelephoneNumberDetails'];
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

    public function tnreservation() {
        if(!isset($this->AccountId) || !isset($this->SiteId))
            $this->get();

        $url = sprintf("%s/%s", $this->get_id(), "tnreservation");
        $account = new Account($this->AccountId, $this->parent->get_rest_client());
        $data = parent::get($url);
        return $account->tnsreservations()->create($data);
    }

    public function set_tn_options($data) {
        $data = new \Iris\SipPeerTelephoneNumber($data);

        if(!($this->parent->parent instanceof Sippeer))
            throw new \Exception("You should get TN from sippeer");
        parent::post($this->get_id(), "SipPeerTelephoneNumbers", $data->to_array());
    }

    public function ratecenter() {
        $url = sprintf("%s/%s", $this->get_id(), "ratecenter");
        $data = parent::get($url);
        if($data['TelephoneNumberDetails']) {
            return new TelephoneNumberDetail($data['TelephoneNumberDetails']);
        }
        return null;
    }
    public function lata() {
        $url = sprintf("%s/%s", $this->get_id(), "lata");
        $data = parent::get($url);
        if($data['TelephoneNumberDetails']) {
            return new TelephoneNumberDetail($data['TelephoneNumberDetails']);
        }
        return null;
    }

    public function lca() {
        $url = sprintf("%s/%s", $this->get_id(), "lca");
        $data = parent::get($url);
        return new LcaSearch($data);
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
