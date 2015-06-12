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

    public function post($url, $base_node, $data)
    {
      $url = $this->prepare_url($url);
      $full_url = sprintf('%s%s', $this->url, $url);

      $xml_base_str = sprintf("<%s></%s>", $base_node, $base_node);
      $xml = new \SimpleXMLElement($xml_base_str);
      $this->array2xml($data, $xml);

      try {
          $response = $this->client->post(
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

      var_dump($response);

      $response_body_str = $response->getBody()->read(1024);
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
