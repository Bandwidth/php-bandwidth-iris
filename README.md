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

## Covered Rate Centers
```PHP
$rcs = new Iris\CoveredRateCenters($client);
$rateCenters = $rcs->get(["page" => 1, "size" => 10 ]);
```

## Disconnected Numbers
```PHP
$account->disnumbers(["areaCode" => "919"]);
```

## Disconnect Numbers
The Disconnect object is used to disconnect numbers from an account.  Creates a disconnect order that can be tracked

### Create Disconnect
```PHP
$disconnect = $account->disconnects()->create([
    "name" => "test disconnect order 4",
    "CustomerOrderId" => "Disconnect1234",
    "DisconnectTelephoneNumberOrderType" => [
        "TelephoneNumberList" => [
            "TelephoneNumber" => [ "9192755378", "9192755703" ]
        ]
    ]
]];
```

### Get Disconnect
```PHP
$disconnect = $account->disconnects()->create(["OrderId" => "b902dee1-0585-4258-becd-5c7e51ccf5e1"]);
$disconnect->get(true); // tnDetails: true
```

### Add Note to Disconnect
```PHP
$disconnect->notes()->create([ "UserId" => "byo_dev", "Description" => "Test Note"])->save();
```

### Get Notes for Disconnect
```PHP
$items = $disconnect->notes()->get();
```
