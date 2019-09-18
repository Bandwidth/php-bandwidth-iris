PHP Client library for Bandwidth's Phone Number Dashboard (AKA: Dashboard, Iris)
=========================================================
[![Build Status](https://travis-ci.org/Bandwidth/php-bandwidth-iris.svg?branch=master)](https://travis-ci.org/Bandwidth/php-bandwidth-iris)

## Release Notes

| Version | Notes |
|:---|:---|
| 2.0.0 | Fixed incompatibilities with Bandwidth's Dashboard API Create Site function that required breaking changes. Versions less than 2.0.0 are not guaranteed to work with Bandwidth's Dashboard API. |
| 2.0.1 | Added `ActualFocDate` to Portins model |
| 2.0.2 | Fixed HTTP method for `set_tn_options` to `PUT` |
| 2.0.3 | Fixed HTTP request for `set_tn_options` to the correct XML object |
| 2.0.4 | Added `localVanity` to `availableNumbers` |
| 2.0.5 | Added `NewBillingTelephoneNumber` to `Portins` model |
| 2.0.6 | Build `ReportsModel` functionality |

## Supported PHP Versions

| Version                        | Support Level            |
|:-------------------------------|:-------------------------|
| 5.5 | Supported |
| 5.6 | Supported |
| 7.0 | Supported |
| 7.1 | Supported |
| 7.2 | Supported |
| 7.3 | Supported |

## Install

Run

```bash
composer require bandwidth/iris
```

## Usage

```PHP
$client = new \Iris\Client($login, $password, ['url' => 'https://dashboard.bandwidth.com/api/']);

```

## Run tests

```bash
$ composer install
$ php ./bin/phpunit --bootstrap ./vendor/autoload.php tests/
```
=======
## Examples
There is an 'examples' folder in the source tree that shows how each of the API objects work with simple example code.  To run the examples:

```bash
$ cd examples
$ composer install
$ cp config.php.example config.php
```
Edit the config.php to match your IRIS credentials and run the examples individually.  e.g.

```bash
php availableNumbers-sample.php
```
If the examples take command line parameters, you will get the usage by just executing the individual script.

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
$items = $sites->getList(); // Array(Site1, Site2)
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
$items = $cities->getList(["state" => "NC"]);
```

## Covered Rate Centers
```PHP
$rcs = new Iris\CoveredRateCenters($client);
$rateCenters = $rcs->getList(["page" => 1, "size" => 10 ]);
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
]]);
```

### Get Disconnect
```PHP
$disconnect = $account->disconnects()->disconnect("b902dee1-0585-4258-becd-5c7e51ccf5e1", true); // tnDetails: true
```

### Add Note to Disconnect
```PHP
$disconnect->notes()->create([ "UserId" => "byo_dev", "Description" => "Test Note"]);
```

### Get Notes for Disconnect
```PHP
$items = $disconnect->notes()->getList();
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
$account->dldas()->getList(["telephoneNumber" => "9195551212"]);
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
```
### Get Lidb
```PHP
$lidb = $account->lidbs()->lidb("7802373f-4f52-4387-bdd1-c5b74833d6e2");
```
### List Lidbs
```PHP
$lidbs = $account->lidbs()->getList(["lastModifiedAfter" => "mm-dd-yy", "telephoneNumber"=> "888"]);
```

## LNP Checker
### Check LNP
```PHP
$account->lnpChecker(["4109255199", "9196190594"], "true");
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
```
### Get Order
```PHP
$response = $account->orders()->order("f30a31a1-1de4-4939-b094-4521bbe5c8df", true); // tndetail=true
$order = $response->Order;
```
### List Orders
```PHP
$items = $account->orders()->getList();
```

### Add note to order
```PHP
$order->notes()->create([ "UserId" => "byo_dev", "Description" => "Test Note"]);
```

### Get all Tns for an order
```PHP
$order->tns()->getList();
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
```

## Get PortIn
```PHP
$portin = $account->portins()->portin("d28b36f7-fa96-49eb-9556-a40fca49f7c6"));
```
## List PortIns
```PHP
$portins = $account->portins()->getList(["pon" => "a pon" ]);
```
### PortIn Instance methods
```PHP
$portin->update();
$portin->delete();
$portin->get_activation_status();
$status = $portin->set_activation_status([
    "AutoActivationDate" => "2014-08-30T18:30:00+03:00"
]);
$portin->history();
$portin->totals();
$portin->notes()->getList();
```

### PortIn File Management
```PHP
$portin->list_loas(true); // metadata = true
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
$cities = $rc->getList(["state" => "CA", "available" => "true"]);
```

## SIP Peers
### Create SIP Peer
```PHP
$sippeer = $account->sippeers()->create(array(
        "PeerName" => "Test5 Peer",
        "IsDefaultPeer" => false,
        "ShortMessagingProtocol" => "SMPP",
        "VoiceHosts" => array(
            "Host" => array(
                "HostName" => "192.168.181.90"
            )
        ),
        "SmsHosts" => array(
            "Host" => array(
                "HostName" => "192.168.181.90"
            )
        ),
        "TerminationHosts" => array(
            "TerminationHost" => array(
                "HostName" => "192.168.181.90",
                "Port" => 0,
                "CustomerTrafficAllowed" => "DOMESTIC",
                "DataAllowed" => true
            )
        )
));
```
### Get SIP Peer
```PHP
$sippeer = $account->sippeers->sippeer("500651");
```
### List SIP Peers
```PHP
$sippeers = $account->sippeers()->getList();
```
### Delete SIP Peer
```PHP
$sippeer->delete();
```
### Move TNs
```PHP
$sippeer->movetns([ "FullNumber" => [ "9192000046", "9192000047", "9192000048" ]]);
```
### Get TNs
```PHP
$tns = $sippeer->tns()->getList();
```
### Get TN
```PHP
$tn = $sippeer->tns()->tn("8183386251");
```

### Total TNs
```PHP
$count = $sippeer->totaltns();
```

### Set TN Options
```PHP
$sippeer->tns()->tn("8183386251")->set_tn_options([
    "FullNumber" => "8183386251",
    "CallForward" => "9194394706",
    "RewriteUser" => "JohnDoe",
    "NumberFormat" => "10digit",
    "RPIDFormat" => "e164"
]);
```

## Sites

### Create A Site
```PHP
$site = $account->sites()->create(
    array("Name" => "Test Site",
        "Address" => array(
            "City" => "Raleigh",
            "AddressType" => "Service",
            "HouseNumber" => "1",
            "StreetName" => "Avenue",
            "StateCode" => "NC"
    )));
```

### Updating a Site
```PHP
$site->Name = "New Name";
$site->update();
```
### Deleting a Site
```PHP
$site->delete();
```
### Listing All Sites
```PHP
$sites = $account->sites()->getList();
```

### Orders of a site
```PHP
$site->orders()->getList(["status" => "disabled"]);
```
### Total TNs of a site
```PHP
$site->totaltns();
```
### Portins of a site
```PHP
$site->portins()->getList(["status" => "disabled" ]);
```
### Sippeers
```PHP
$site->sippeers()->create([...])
```
[see SIP Peers]

## Subscriptions
### Create Subscription
```PHP
$subscription = $account->subscriptions()->create([
    "OrderType" => "portins",
    "OrderId" => "98939562-90b0-40e9-8335-5526432d9741",
    "EmailSubscription" => [
        "Email" => "test@test.com",
        "DigestRequested" => "DAILY"
    ]
]);
```
### Get Subscription
```PHP
$subscription = $account->subscriptions()->subscription($id);
```
### List Subscriptions
```PHP
$account->subscriptions()->getList(["orderType" => "portins"]);
```
### Update
```PHP
$subscription->OrderType = "portins";
$subscription->update();
```
### DELETE
```PHP
$subscription->delete();
```

## TNs
### Get TN
```PHP
$tns = new Iris\Tns(null, $client);
$tn = $tns->tn($id);
```
### List TNs
```PHP
$tns = new Iris\Tns(null, $client);
$tns_items = $tns->getList(["page" => 1, "size" => 10 ]);
```
### TN Instance Methods
```PHP
$tn = $tns->tn("7576768750");
$site = $tn->site();
$sippeer = $tn->sippeer();
$tnreservation = $tn->tnreservation();
$tn->tndetails();
$rc = $tn->ratecenter();
$lata = $tn->lata();
$lca = $tn->lca();
```
## TN Reservation
### Create TN Reservation
```PHP
$resertation = $account->tnsreservations()->create(["ReservedTn" => "2512027430"]);
```
### Get TN Reservation
```PHP
$resertation = $account->tnsreservations()->tnsreservation("0099ff73-da96-4303-8a0a-00ff316c07aa");
```
### Delete TN Reservation
```PHP
$resertation = $account->tnsreservations()->tnsreservation("0099ff73-da96-4303-8a0a-00ff316c07aa");
$resertation->delete();
```

## Billing reports

### Listing all Billing Report instances
```PHP
$billingReports = $account->billingreports()->getList();
```

### Request new billing report
```PHP
$billingReport = $account->billingreports()->request(
    array("Type" => "bdr",
        "DateRange" => array(
            "StartDate" => "2018-02-05",
            "EndDate" => "2018-02-06",
    )));
```

### Checking status of the billing report
```PHP
$billingReport = $account->billingreports()->billingreport('a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2');
```

### Download zip with content of the billing report
```PHP
$zipStream = $billingReport->file();
```

### Download zip with content of the billing report and save to file
```PHP
$outFile = '/tmp/report.zip';
$billingReport->file(['stream' => true, 'sink' => $outFile]);
```
