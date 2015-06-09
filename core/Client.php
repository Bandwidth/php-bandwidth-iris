<?php
/**
 * REST Client
 *
 */

namespace Iris;

interface iClient
{
    public function get($url, $options);
    public function set($url, $data, $callback, $errback);
    public function put($url, $data, $callback, $errback);
    public function delete($url, $callback, $errback);
}


final class PestClient implements iClient
{
    
    public function __construct($login, $password, $options=Null)
    {
        /* TODO:  singleton */

        $this->login = $login;
        $this->password = $password;

        /* TODO:  hardcode */
        $this->url = $this->prepare_base_url($options['url']);

        $this->pest = new \PestXML($this->url);

        $this->pest->setupAuth($login, $password);
        $this->pest->curl_opts[CURLOPT_FOLLOWLOCATION] = false;
    }
    
    public function get($url, $options=Array())
    {
        $url = $this->prepare_url($url);
        $full_url = sprintf('%s%s', $this->url, $url);
        echo('--- request url: '. $full_url . ' --- '); 
        $data = $this->pest->get($full_url, $options);
        return $this->xml2object($data);
    }

    public function set($url, $data, $callback, $errback)
    {
        
    }

    public function put($url, $data, $callback, $errback)
    {
        
    }

    public function delete($url, $callback, $errback)
    {
        
    }

    protected function xml2array ($xmlObject, $out = array ())
    {
        /* snippet from: http://stackoverflow.com/questions/6167279/converting-a-simplexml-object-to-an-array */
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;

        return $out;
    }

    protected function xml2object($xmlObject)
    {
        /* snippet from  http://stackoverflow.com/questions/1869091/how-to-convert-an-array-to-object-in-php */

        $arr = $this->xml2array($xmlObject);
        $object = json_decode(json_encode($arr), FALSE);
        return $object;
        
        
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