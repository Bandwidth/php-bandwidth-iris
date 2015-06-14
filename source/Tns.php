<?php

namespace Iris;

/* TODO:  change on autoload compouser*/
$selfpath = dirname(__FILE__);
require_once($selfpath . '/RestEntry.php');


/* TODO:  remove build url from methods */

final class Tns extends RestEntry {

    /**
     *
     *
     */
    public function __construct($client, $namespace=Null)
    {
        parent::_init($client, $namespace='tns');
    }

    public function get($filters = Array()) {
        $tns = [];
        $data = parent::get('', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));
        if($data['TelephoneNumberCount'] && $data['TelephoneNumbers']) {
            foreach($data['TelephoneNumbers'] as $tn) {
                $tns[] = new Tn($this, $tn);
            }
        }
        return $tns;
    }
    
    public function get_by_number($id) {
        $order = new Order($this, array("Id" => $id));
        $order->get();
        return $order;
    }

    public function get_rest_client() {
      return $this->client;
    }

    public function get_relative_namespace() {
        return 'tns';
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
        )
    );

    public function __construct($tns, $data)
    {
          if(isset($data)) {
              if(is_object($data) && $data->FullNumber)
                  $this->id = $data->FullNumber;
              if(is_array($data) && isset($data['FullNumber']))
                  $this->id = $data['FullNumber'];
          }
          $this->set_data($data);

          if(!is_null($tns)) {
              $this->parent = $tns;
              parent::_init($tns->get_rest_client(), $tns->get_relative_namespace());
          }

    }
    
    public function tnsdetails()
    {
        $url = sprintf('%s/%s', $this->id, 'tndetails');
        $data = parent::get($url);
        return $data;
    }

    public function sites()
    {
        $url = sprintf('%s/%s', $this->id, 'sites');
        $data = parent::get($url);
        return $data;
    }

    public function sippeers()
    {
        $url = sprintf('%s/%s', $this->id, 'sippeers');
        $data = parent::get($url);
        return $data;
    }

    public function lca()
    {
        $url = sprintf('%s/%s', $this->id, 'lca');
        $data = parent::get($url);
        return $data;
    }

    public function lata()
    {
        $url = sprintf('%s/%s', $this->id, 'lata');
        $data = parent::get($url);
        return $data;
    }

    public function history()
    {
        $url = sprintf('%s/%s', $this->id, 'history');
        $data = parent::get($url);
        return $data;
    }

    public function tnsreservation()
    {
        $url = sprintf('%s/%s', $this->id, 'tnsreservation');
        $data = parent::get($url);
        return $data;
    }
}