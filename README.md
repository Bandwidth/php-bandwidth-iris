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

To reflect object structure:
```PHP
echo json_encode($site->to_array());
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

## Dlda

### Create Ddla
```PHP
$order_data = [
    "CustomerOrderId" => "123",
    "DldaTnGroups" => [
        "DldaTnGroup" => [
            [
                "TelephoneNumbers" => [
                    "TelephoneNumber" => "4352154856"
                ],
                "AccountType" => "RESIDENTIAL",
                "ListingType" => "LISTED",
                "ListAddress" => "true",
                "ListingName" => [
                    "FirstName" => "FirstName",
                    "FirstName2" => "FirstName2",
                    "LastName" => "LastName",
                    "Designation" => "Designation",
                    "TitleOfLineage" => "TitleOfLineage",
                    "TitleOfAddress" => "TitleOfAddress",
                    "TitleOfAddress2" => "TitleOfAddress2",
                    "TitleOfLineageName2" => "TitleOfLineageName2",
                    "TitleOfAddressName2" => "TitleOfAddressName2",
                    "TitleOfAddress2Name2" => "TitleOfAddress2Name2",
                    "PlaceListingAs" => "PlaceListingAs",
                ],
                "Address" => [
                    "HousePrefix" => "HousePrefix",
                    "HouseNumber" => "915",
                    "HouseSuffix" => "HouseSuffix",
                    "PreDirectional" => "PreDirectional",
                    "StreetName" => "StreetName",
                    "StreetSuffix" => "StreetSuffix",
                    "PostDirectional" => "PostDirectional",
                    "AddressLine2" => "AddressLine2",
                    "City" => "City",
                    "StateCode" => "StateCode",
                    "Zip" => "Zip",
                    "PlusFour" => "PlusFour",
                    "Country" => "Country",
                    "AddressType" => "AddressType"
                ]
            ]
        ]
    ]
];

$dlda = $account->dldas()->create($order_data);
$dlda->post();
```

### Get Dlda
```PHP
$dlda = $account->dldas()->dlda("7802373f-4f52-4387-bdd1-c5b74833d6e2");
```

### Get Dlda History
```PHP
$dlda->history();
```

### List Dldas
```PHP
$account->dldas()->get(["telephoneNumber" => "9195551212"]);
```

## In Service Numbers

### List InService Numbers
```PHP
$account->inserviceNumbers(["areaCode" => "919"]);
```

## Lidb

### Create
```PHP
$order_data = [
    "LidbTnGroups" => [
        "LidbTnGroup" => [
            [
                "TelephoneNumbers" => [
                    "TelephoneNumber" => "4352154856"
                ],
                "SubscriberInformation" => "Steve",
                "UseType" => "RESIDENTIAL",
                "Visibility" => "PUBLIC"
            ],
            [
                "TelephoneNumbers" => [
                    "TelephoneNumber" => "4352154855"
                ],
                "SubscriberInformation" => "Steve",
                "UseType" => "RESIDENTIAL",
                "Visibility" => "PUBLIC"
            ]
        ]
    ]
];

$lidb = $account->lidbs()->create($order_data);
$lidb->post();
```
### Get Lidb
```PHP
$lidb = $account->lidbs()->lidb("7802373f-4f52-4387-bdd1-c5b74833d6e2");
```
### List Lidbs
```PHP
$lidbs = $account->lidbs()->get(["lastModifiedAfter" => "mm-dd-yy", "telephoneNumber"=> "888"]);
```

## LNP Checker
### Check LNP
```PHP
$account->lnpChecker(["TnList" => ["Tn" => ["4109255199", "9196190594"]]], "true");
```

## Orders
### Create Order
```PHP
$order = $account->orders()->create([
    "Name" => "Available Telephone Number order",
    "SiteId" => "2297",
    "CustomerOrderId" => "123456789",
    "ExistingTelephoneNumberOrderType" => [
        "TelephoneNumberList" => [
            "TelephoneNumber" => [ "9193752369", "9193752720", "9193752648"]
        ]
    ]
]);

$order->post();
```
### Get Order
```PHP
$response = $account->orders()->order("f30a31a1-1de4-4939-b094-4521bbe5c8df", true); // tndetail=true
$order = $response->Order;
```
### List Orders
```PHP
$items = $account->orders()->get();
```

### Add note to order
```PHP
$order->notes()->create([ "UserId" => "byo_dev", "Description" => "Test Note"])->save();
```

### Get all Tns for an order
```PHP
$order->tns()->get();
```
## Port Ins
### Create PortIn
```PHP
$portin = $account->portins()->create(array(
    "BillingTelephoneNumber" => "6882015002",
    "Subscriber" => array(
        "SubscriberType" => "BUSINESS",
        "BusinessName" => "Acme Corporation",
        "ServiceAddress" => array(
            "HouseNumber" => "1623",
            "StreetName" => "Brockton Ave",
            "City" => "Los Angeles",
            "StateCode" => "CA",
            "Zip" => "90025",
            "Country" => "USA"
        )
    ),
    "LoaAuthorizingPerson" => "John Doe",
    "ListOfPhoneNumbers" => array(
        "PhoneNumber" => array("9882015025", "9882015026")
    ),
    "SiteId" => "365",
    "Triggered" => "false"
));

$portin->save();
```

## Get PortIn
```PHP
$portin = $account->portins()->create(array(
    "OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
));

$portin->get();
```
## List PortIns
```PHP
$portins = $account->portins()->get(["pon" => "a pon" ]);
```
### PortIn Instance methods
```PHP
$portin->save();
$portin->delete();
$portin->get_activation_status();
$status = $portin->set_activation_status([
    "AutoActivationDate" => "2014-08-30T18:30:00+03:00"
]);
$portin->history();
$portin->totals();
$portin->notes()->get();
```

### PortIn File Management
```PHP

$portin->get_loas(true); // metadata = true
$portin->loas_send("./1.txt");
$portin->loas_update("./1.txt", "1.txt");
$portin->loas_delete("1.txt");
$portin->get_metadata("1.txt");
$meta_new = array(
    "DocumentName" => "text.txt",
    "DocumentType" => "INVOICE"
);
$portin->set_metadata('test.txt', $meta_new);
$portin->delete_metadata('test.txt');
```

## Rate Centers
### List Ratecenters
```PHP
$rc = new \Iris\RateCenter($client);
$cities = $rc->get(["state" => "CA", "available" => "true"]);
```
