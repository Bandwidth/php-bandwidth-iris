<?php

namespace Iris;

abstract class RestEntry{

    protected $client = Null;
    protected $namespace = Null;
    protected $fields = array();
    protected $required = array();

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
        if(is_null($path))
            return $this->namespace;
        
        return sprintf('%s/%s', $this->namespace, $path);
    }

    protected function get($url, $options=Array(), $defaults = Array(), $required = Array())
    {
        $url = $this->get_url($url);

        $this->set_defaults($options, $defaults);
        $this->check_required($options, $required);

        return $this->client->get($url, $options);
    }

    protected function post($url, $data, $headers = array())
    {
        $url = $this->get_url($url);
        return $this->client->post($url, $data, $headers);
    }

    protected function set_defaults(&$options, $defaults) {
        foreach($defaults as $key => $value) {
            if(!array_key_exists($key, $options))
                $options[$key] = $value;
        }
    }

    protected function check_required($options, $required) {
        foreach($required as $key) {
            if(!array_key_exists($key, $options))
                throw new ValidateException("Required options '{$key}' should be provided");
        }
    }

    protected function set_data($data) {
        foreach($data as $key => $value) {
            if(array_key_exists($key, $this->fields)) {
                $this->{$key} = $value;
            }
        }
    }

}
