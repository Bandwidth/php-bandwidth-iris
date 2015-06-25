<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php site-create-sample.php [site name] e.g. php site-create-sample.php test12");
}

if(empty(Config::SITE)){
  die("You must configure a site and sip peer for this demo in your config file");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD, Array('url' => Config::URL));
$account = new Iris\Account(Config::ACCOUNT, $client);

$name = $argv[1];

$site = $account->sites()->create(
    array("Name" => $name,
        "Address" => array(
            "City" => "Raleigh",
            "AddressType" => "Service",
            "HouseNumber" => "1",
            "StreetName" => "Avenue",
            "StateCode" => "NC"
    )));

echo json_encode($site->to_array());
echo "\n";

$site->Address->HouseNumber = "12";

$site->update();

echo json_encode($account->sites()->site($site->Id)->to_array());
