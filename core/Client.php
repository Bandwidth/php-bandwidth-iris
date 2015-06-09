<?php
/**
 * REST Client
 *
 */

namespace Iris;
require_once('./vendor/autoload.php');


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
        $this->url = $options['url'];

        $this->pest = new \PestXML($this->url);

        $this->pest->setupAuth($login, $password);
        $this->pest->curl_opts[CURLOPT_FOLLOWLOCATION] = false;
    }
    
    public function get($url, $options)
    {
        $full_url = sprintf('%s%s', $this->url, $url);
        $data = $this->pest->get($full_url);
        return $this->xml2array($data);
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

    function xml2array ($xmlObject, $out = array ())
    {
        /* snippet from: http://stackoverflow.com/questions/6167279/converting-a-simplexml-object-to-an-array */
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;

        return $out;
    }
    
}