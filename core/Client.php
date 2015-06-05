<?php
/**
 * REST Client
 *
 */

namespace Iris;
require_once('./vendor/autoload.php');

interface iClient
{
    public function get($url, $callback, $errback);
    public function set($url, $data, $callback, $errback);
    public function put($url, $data, $callback, $errback);
    public function delete($url, $callback, $errback);
}


final class PestClient implements iClient
{
    
    public function __construct($login, $password, $options=Null)
    {
        $this->login = $login;
        $this->password = $password;

        $this->pest = new \PestXML($REST_URL);
        $this->pest->setupAuth($REST_LOGIN, $REST_PASS);
        $this->pest->curl_opts[CURLOPT_FOLLOWLOCATION] = false;
    }
    
    public function get($url, $callback, $errback)
    {
        $data = $this->pest->get($url, $callback, $errback);
        //return $this->to_array($data);
        return $data;
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

    private function to_array() 
    {
        
    }
    
}