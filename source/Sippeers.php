<?php

namespace Iris;

class Sippeers extends RestEntry {
    public function __construct($site) {
        $this->parent = $site;
        parent::_init($site->get_rest_client(), $site->get_relative_namespace());
    }

    public function get($filters = Array()) {
        $sippeers = [];

        $data = parent::get('sippeers');

        if(isset($data['SipPeers']) && isset($data['SipPeers']['SipPeer'])) {
            if(!is_array($data['SipPeers']['SipPeer']))
                $peers = [ $data['SipPeers']['SipPeer'] ];
            else
                $peers = $data['SipPeers']['SipPeer'];

            foreach($peers as $sippeer) {
                $sippeers[] = new Sippeer($this, $sippeer);
            }
        }

        return $sippeers;
    }

    public function get_by_id($id) {
        $sipper = new Sippeer($this, array("PeerId" => $id));
        $sipper->get();
        return $sipper;
    }

    public function get_appendix() {
        return '/sippeers';
    }

    public function create($data) {
        $sipper = new Sippeer($this, $data);
        $sipper->save();
        return $sipper;
    }
}

class Sippeer extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "PeerId" => array("type" => "string"),
        "PeerName" => array("type" => "string"),
        "IsDefaultPeer" => array("type" => "string"),
        "ShortMessagingProtocol" => array("type" => "string"),
        "VoiceHosts" => array("type" => "Iris\Hosts"),
        "VoiceHostGroups" => array("type" => "string"),
        "SmsHosts" => array("type" => "Iris\Hosts"),
        "TerminationHosts" => array("type" => "Iris\Hosts")
    );

    public function __construct($parent, $data) {
        $this->PeerId = null;

        if(isset($data)) {
            if(is_object($data) && $data->PeerId)
                $this->PeerId = $data->PeerId;
            if(is_array($data) && isset($data['PeerId']))
                $this->PeerId = $data['PeerId'];
        }
        $this->set_data($data);

        if(!is_null($parent)) {
            $this->parent = $parent;
            parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        }
    }

    public function get() {
        if(is_null($this->PeerId))
            throw new \Exception('Id should be provided');

        $data = parent::get($this->PeerId);
        $this->set_data($data['SipPeer']);
    }

    public function save() {
        if(!is_null($this->PeerId))
            parent::put($this->PeerId, "SipPeer", $this->to_array());
        else {
            $header = parent::post(null, "SipPeer", $this->to_array());
            $splitted = split("/", $header['Location']);
            $this->PeerId = end($splitted);
        }
    }

    public function delete() {
        if(is_null($this->PeerId))
            throw new \Exception('Id should be provided');
        parent::delete($this->PeerId);
    }

    public function get_appendix() {
        return '/'.$this->PeerId;
    }
}
