<?php
/**
 * REST Client
 *
 */

namespace Iris;

class ResponseException extends \Exception {

}

abstract class iClient
{
    abstract function get($url, $options);
    abstract function post($url, $base_node, $data);
    abstract function put($url, $base_node, $data);
    abstract function delete($url);

    protected function isAssoc($array)
    {
        $array = array_keys($array); return ($array !== array_keys($array));
    }

    protected function xml2array ($xml)
    {
      $arr = array();
      foreach ($xml as $element) {
        $tag = $element->getName();
        $e = get_object_vars($element);
        if (!empty($e)) {
          $res = $element instanceof \SimpleXMLElement ? $this->xml2array($element) : $e;
        }
        else {
          $res = trim($element);
        }

        if(isset($arr[$tag])) {
            if(!is_array($arr[$tag]) || $this->isAssoc($arr[$tag])) {
                $tmp = $arr[$tag];
                $arr[$tag] = [];
                $arr[$tag][] = $tmp;
            }
            $arr[$tag][] = $res;
        } else
            $arr[$tag] = $res;

      }
      return $arr;
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
        if(is_array($value) && $this->isAssoc($value)) {
          if(!is_numeric($key)){
            $subnode = $xml->addChild("$key");
            $this->array2xml($value, $subnode);
          }
          else{
            $subnode = $xml->addChild("item$key");
            $this->array2xml($value, $subnode);
          }
        } else if(is_array($value) && !$this->isAssoc($value)) {
            foreach($value as $item) {
                if(is_array($item)) {
                    $subnode = $xml->addChild("$key");
                    $this->array2xml($item, $subnode);
                } else {
                    $xml->addChild("$key",htmlspecialchars("$item"));
                }
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

final class Client extends iClient {
    public function __construct($login, $password, $options=Null)
    {
        if(empty($login) || empty($password) || !is_array($options) || !isset($options['url']))
            throw new \Exception("Provide login, password and url");

        $this->login = $login;
        $this->password = $password;

        $this->url = $this->prepare_base_url($options['url']);

        $client_options = array();

        if(isset($options['handler'])) {
            $client_options['handler'] = $options['handler'];
        }

        $this->client = new \GuzzleHttp\Client($options);
    }

    private function parse_exception_response($e) {
        $body = $e->getResponse()->getBody(true);
        $doc = @simplexml_load_string($body);

        if(isset($doc) && isset($doc->ResponseStatus) && isset($doc->ResponseStatus->Description)) {
            throw new ResponseException((string)$doc->ResponseStatus->Description, (int)$doc->ResponseStatus->ErrorCode);
        } else if(isset($doc) && isset($doc->Error) && isset($doc->Error->Description) && isset($doc->Error->Code)) {
            throw new ResponseException((string)$doc->Error->Description, (int)$doc->Error->Code);
        } else {
            throw new ResponseException($body, $e->getResponse()->getStatusCode());
        }
    }

    public function get($url, $options=array())
    {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        try {
            if(CONFIG::DEBUG) {
                echo "GET: ".$full_url."\n";
                echo "GET OPTIONS: ".json_encode($options)."\n";
            }

            $response = $this->client->get($full_url, ['query' => $options, 'auth' =>  [$this->login, $this->password]]);
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

        if(CONFIG::DEBUG) {
            echo "**** send ****\n";
            echo $xml->asXML()."\n";
            echo $method.": ".$full_url."\n";
            echo "**** *** ****\n";
        }

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

    public function raw_post($url, $body, $headers = array())
    {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        echo "RAW POST: ".$full_url."\n";
        return $this->client->post($full_url, ['body' => $body, 'headers' => $headers]);
    }

    public function raw_put($url, $body, $headers = array())
    {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);

        if(CONFIG::DEBUG) {
            echo "RAW PUT: ".$full_url."\n";
        }
        return $this->client->put($full_url, ['body' => $body, 'headers' => $headers]);
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

        if(CONFIG::DEBUG) {
            echo "delete: ".$full_url."\n";
        }

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
