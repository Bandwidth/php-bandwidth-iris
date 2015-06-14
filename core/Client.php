<?php
/**
 * REST Client
 *
 */

namespace Iris;

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
        if(is_array($value)) {
          if(!is_numeric($key)){

            $subnode = $xml->addChild("$key");
            $this->array2xml($value, $subnode);
          }
          else{
            $subnode = $xml->addChild("item$key");
            $this->array2xml($value, $subnode);
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

    public function put($url, $base_node, $data)
    {
    }


    public function delete($url)
    {
    }
}
