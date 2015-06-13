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
            foreach($data['SipPeers']['SipPeer'] as $sippeer) {
                $sippeers[] = new Sippeer($this, $sippeer);
            }
        }

        return $sippeers;
    }

    public function get_rest_client() {
        return $this->parent->get_rest_client();
    }

    public function get_relative_namespace() {
        return $this->parent->get_relative_namespace().'/sippeers';
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
        if(isset($data)) {
            if(is_object($data) && $data->PeerId)
                $this->id = $data->PeerId;
            if(is_array($data) && isset($data['PeerId']))
                $this->id = $data['PeerId'];
        }
        $this->set_data($data);

        if(!is_null($parent)) {
            $this->parent = $parent;
            parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        }
    }

    public function get_rest_client() {
        return $this->parent->get_rest_client();
    }

    public function get_relative_namespace() {
        return $this->parent->get_relative_namespace().'/'.$this->id;
    }

}
