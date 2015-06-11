<?php
/**
 * REST Client
 *
 */

namespace Iris;

$selfpath = dirname(__FILE__);
require_once($selfpath . '/../vendor/autoload.php');

abstract class iClient
{
    abstract function get($url, $options);
    abstract function post($url, $base_node, $data);
    abstract function put($url, $data);
    abstract function delete($url, $data);

    protected function xml2array ($xmlObject, $out = array ())
    {
      /* snippet: http://stackoverflow.com/questions/6167279/converting-a-simplexml-object-to-an-array */
      foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;

      return $out;
    }

    protected function xml2object($xmlObject)
    {
      /* snippet: http://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php */

      $arr = $this->xml2array($xmlObject);
      $object = json_decode(json_encode($arr), FALSE);
      return $object;
    }

    protected function array2xml($arr, &$xml)
    {
      /* snippet:  http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml */
      foreach($arr as $key => $value) {
        if(is_array($value)) {
          if(!is_numeric($key)){

            $subnode = $xml->addChild("$key");
            $this->array2xml($value, $subnode);
          }
          else{
            $subnode = $xml->addChild("item$key");
            array_to_xml($value, $subnode);
          }
        }
        else {
          $xml->addChild("$key",htmlspecialchars("$value"));
        }
      }
    }

    protected function prepare_base_url($url)
    {
        return substr($url, -1) != '/' ? $url . '/' : $url;
    }

    protected function prepare_url($url)
    {
        return substr($url, 0, 1) == '/' ? substr($url, 1) : $url;
    }
}


class PestClient extends iClient
{

    public function __construct($login, $password, $options=Null)
    {
        /* TODO:  singleton */

        $this->login = $login;
        $this->password = $password;

        $this->url = $this->prepare_base_url($options['url']);

        $this->pest = new \PestXML($this->url);

        $this->pest->setupAuth($login, $password);
        $this->pest->curl_opts[CURLOPT_FOLLOWLOCATION] = false;
    }

    public function get($url, $options=Array())
    {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        //echo('--- request url: '. $full_url . ' --- ');
        $data = $this->pest->get($full_url, $options);
        return $this->xml2object($data);
    }

    public function post($url, $base_node, $data)
    {

        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);
        echo('--- request url: '. $full_url . ' --- ');
        $data = $this->pest->post($full_url, $data);
        return $this->xml2object($data);
    }

    public function put($url, $data)
    {

    }

    public function delete($url, $data)
    {

    }
}


final class GuzzleClient extends iClient {
    public function __construct($login, $password, $options=Null)
    {
        /* TODO:  singleton */

        $this->login = $login;
        $this->password = $password;

        $this->url = $this->prepare_base_url($options['url']);

        $this->client = new \GuzzleHttp\Client();

    }

    public function get($url, $options=Null)
    {

      $url = $this->prepare_url($url);
      $full_url = sprintf('%s%s', $this->url, $url);
      $request = $this->client->get($full_url, ['auth' =>  [$this->login, $this->password]]);
      $response_body_str = $request->getBody()->read(1024);
      $response_body_xml = new \SimpleXMLElement($response_body_str);
      return $this->xml2array($response_body_xml);
    }

    public function post($url, $base_node, $data)
    {
      $url = $this->prepare_url($url);
      $full_url = sprintf('%s%s', $this->url, $url);

      //prepare data
      $key = 'Order';
      $xml_base_str = sprintf("<?xml version=\"1.0\"?><%s></%s>", $base_node, $base_node);
      $xml = new \SimpleXMLElement($xml_base_str);
      $this->array2xml($data, $xml);
      //print $xml->asXML();exit;
      $result = Null;
      try {
          $request = $this->client->post(
                                 $full_url,
                                 ['auth' =>  [$this->login, $this->password],
                                  'body' => $xml->asXML(),
                                  'headers' => ['Content-Type' => 'application/xml']
                                  ]
                                 );
          $result = &$request;
      }
      catch (\GuzzleHttp\Exception\ClientException $e) {
          $result = &$e->getResponse();
      }
      $response_body_str = $result->getBody()->read(1024);
      $response_body_xml = new \SimpleXMLElement($response_body_str);
      return $this->xml2array($response_body_xml);
    }

    public function put($url, $data)
    {

    }


    public function delete($url, $data)
    {

    }
}
