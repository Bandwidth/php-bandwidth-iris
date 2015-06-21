<?php

namespace Iris;

final class User extends RestEntry{
    use BaseModel;

    public function __construct($user_id)
    {
        $this->id = $user_id;
        parent::_init(Null, 'users');
    }
    
    public function get()
    {
        $url = sprintf('%s', $this->id);
        $data = parent::get($url);
        return $data;
    }
    
    public function password($password)
    {
        $url = sprintf('%s/%s', $this->id, 'password');
        $data = parent::put($url, ['Password' => $password]);
        return $data;
    }
}