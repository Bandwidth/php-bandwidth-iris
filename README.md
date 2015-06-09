PHP Client library for IRIS / BBS API
=========================================================


Installing:
----------------------------------------------------------------

1. composer update

2. cp ./config.default.php ./config.php

3. Fill config.php


Shell running:
----------------------------------------------------------------

php -a
include('./vendor/autoload.php');
$a = new Iris\Account(9500249);
print_r($a->get());
print_r($a->users());
print_r($a->orders());

or

$c = new Iris\PestClient('another rest login','another rest pass', Array('url' => 'https://api.test.inetwork.com/v1.0/'));
$a = new Iris\Account(9500249, $c);
print_r($a->get()); 


