<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class PortoutsTest extends PHPUnit_Framework_TestCase {
	public static $container;
    public static $portouts;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><LnpOrderResponse><OrderId>d28b36f7-fa96-49eb-9556-a40fca49f7c6</OrderId><Status><Code>201</Code><Description>Order request received. Please use the order id to check the status of your order later.</Description></Status><ProcessingStatus>PENDING_DOCUMENTS</ProcessingStatus><LoaAuthorizingPerson>John Doe</LoaAuthorizingPerson><Subscriber><SubscriberType>BUSINESS</SubscriberType><BusinessName>Acme Corporation</BusinessName><ServiceAddress><HouseNumber>1623</HouseNumber><StreetName>Brockton Ave #1</StreetName><City>Los Angeles</City><StateCode>CA</StateCode><Zip>90025</Zip><Country>USA</Country></ServiceAddress></Subscriber><BillingTelephoneNumber>6882015002</BillingTelephoneNumber><ListOfPhoneNumbers><PhoneNumber>6882015025</PhoneNumber><PhoneNumber>6882015026</PhoneNumber></ListOfPhoneNumbers><Triggered>false</Triggered><BillingType>PORTIN</BillingType></LnpOrderResponse>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$portouts = $account->portouts();
    }
    public function testPortoutsGet() {
		$portouts = self::$portouts->get(["status" => "x" ]);

		$this->assertEquals(2, count($portouts));
		$json = '{"CountOfTNs":"1","lastModifiedDate":"2015-06-03T15:06:36.234Z","OrderDate":"2015-06-03T15:06:35.533Z","OrderType":"port_in","LNPLosingCarrierId":"1537","LNPLosingCarrierName":"Test Losing Carrier L3","RequestedFOCDate":"2015-06-03T15:30:00.000Z","VendorId":"49","VendorName":"Bandwidth CLEC","PON":"BWC1433343996123","OrderId":"535ba91e-5363-474e-8c97-c374a4aa6a02","ProcessingStatus":"SUBMITTED","userId":"System","BillingTelephoneNumber":"9193491234"}';
		$this->assertEquals($json, json_encode($portouts[0]->to_array()));
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portouts?status=x&page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}
}
