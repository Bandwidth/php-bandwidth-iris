<?php

namespace Iris;

final class GuzzleClient2 extends iClient {
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

    public function make_call($url, $base_node, $data, $method) {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        $xml_base_str = sprintf("<%s></%s>", $base_node, $base_node);
        $xml = new \SimpleXMLElement($xml_base_str);
        $this->array2xml($data, $xml);

        echo $xml->asXML();

        try {
            $response = $this->client->{$method}(
                                   $full_url,
                                   ['auth' =>  [$this->login, $this->password],
                                    'body' => $xml->asXML(),
                                    'headers' => ['Content-Type' => 'application/xml']
                                    ]
                                   );
        }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            echo "Response status code: ".$e->getResponse()->getStatusCode();
            exit;
        }

        $response_body_str = $response->getBody()->read(1024);

        try {
            $response_body_xml = new \SimpleXMLElement($response_body_str);
            $response_array = $this->xml2array($response_body_xml);
        } catch(Exception $e) {
            $response_array = array();
        }
        return $response_array;

    }

    public function post($url, $base_node, $data)
    {
        $this->make_call($url, $base_node, $data, 'post');
    }

    public function put($url, $data)
    {
        $this->make_call($url, $base_node, $data, 'put');
    }


    public function delete($url, $data)
    {

    }
}
