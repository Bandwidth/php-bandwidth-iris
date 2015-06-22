PHP Client library for IRIS / BBS API
=========================================================

## Install

Run

```bash
composer require bandwidth/iris
```

## Usage

```PHP
$client = new \Iris\Client($login, $password, ['url' => 'https://api.inetwork.com/v1.0/']);

```

## API Objects
### General principles
In most cases you should use Account object as start point.

```PHP
$account = new \Iris\Account($your_account_id, $client);
```

Account has related entities such Orders, Sites, etc.

Example:
```PHP
$sites = $account->sites();
```

To get stored Sites you should create $sites object and execute get() method.

```PHP
$items = $sites->get(); // Array(Site1, Site2)
```

## Available Numbers
```PHP
$account->availableNumbers([ "areaCode" => "818" ]);
```

## Available NpaNxx
```PHP
$account->availableNpaNxx(["state" => "CA"]);
```

## Cities
```PHP
$cities = new \Iris\Cities($client);
$items = $cities->get(["state" => "NC"]);
```
