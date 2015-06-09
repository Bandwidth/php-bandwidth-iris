<?php

namespace Iris;

abstract class RestEntry{
    
    protected $client = Null;
    protected $namespace = Null;
    
    protected function _init($client, $namespace)
    {
        if (!$client)
        {
            $this->client = new PestClient(Config::REST_LOGIN, Config::REST_PASS, Array('url' => Config::REST_URL));
        }
        else 
        {
            $this->client = $client;
        }
        if ($namespace)
        {    
            $this->namespace = $namespace;
        }
        else
        {
            $this->namespace = strtolower(get_class($this));
        }        
    }

    protected function get_url($path)
    {
        return sprintf('%s/%s', $this->namespace, $path);
    }

    protected function get($url, $options=Array())
    {
        $url = $this->get_url($url);
        return $this->client->get($url, $options);
    }
}