<?php

namespace Iris;

class ResponseException extends \Exception {

}

final class GuzzleClient extends iClient {
    public function __construct($login, $password, $options=Null)
    {
        $this->login = $login;
        $this->password = $password;

        $this->url = $this->prepare_base_url($options['url']);

        $this->client = new \GuzzleHttp\Client();

    }

    private function parse_exception_response($e) {
        $body = $e->getResponse()->getBody(true);
        $doc = @simplexml_load_string($body);

        if(isset($doc) && isset($doc->ResponseStatus) && isset($doc->ResponseStatus->Description)) {
            throw new ResponseException((string)$doc->ResponseStatus->Description, (int)$doc->ResponseStatus->ErrorCode);
        } else {
            throw new ResponseException($body, $e->getResponse()->getStatusCode());
        }
    }

    public function get($url, $options=array())
    {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        try {
            $response_body_str = '';
            $string_or_stream_body = $response->getBody(true);
            $response_body_str = $this->get_body($string_or_stream_body);
            if($response_body_str !== "") {
                $response_body_xml = new \SimpleXMLElement($response_body_str);
                $response_array = $this->xml2array($response_body_xml);
            } else {
                $response_array = array();
            }
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            $this->parse_exception_response($e);
        }
        return $response_array;
    }

    public function make_call($url, $base_node, $data, $method) {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        $xml_base_str = sprintf("<%s></%s>", $base_node, $base_node);
        $xml = new \SimpleXMLElement($xml_base_str);

        $this->array2xml($data, $xml);

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
            $this->parse_exception_response($e);
        }

        $string_or_stream_body = $response->getBody(true);
        $response_body_str = $this->get_body($string_or_stream_body);

        try {
            if($response_body_str == "") {
                if($response->hasHeader('Location')) {
                    $response_array = array("Location" => $response->getHeader('Location')[0]);
                } else {
                    $response_array = array();
                }
            } else {
                $response_body_xml = new \SimpleXMLElement($response_body_str);
                $response_array = $this->xml2array($response_body_xml);
            }
        } catch(Exception $e) {
            $response_array = array();
        }
        return $response_array;
    }

    public function post($url, $base_node, $data)
    {
        return $this->make_call($url, $base_node, $data, 'post');
    }

    public function put($url, $base_node, $data)
    {
        return $this->make_call($url, $base_node, $data, 'put');
    }


    public function delete($url)
    {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        try {
            $this->client->delete($full_url, ['auth' =>  [$this->login, $this->password]]);
        }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->parse_exception_response($e);
        }
    }

    protected function get_body($body)
    {
        $body_str = '';
        if ($body instanceof \GuzzleHttp\Psr7\Stream) 
        {
            while(!$body->eof())
            {
                $body_str .= $body->read(1024);
            }
        }
        else 
        {
            $body_str = $body;
        }

        return $body_str;
    }
}
