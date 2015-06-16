<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class PortinsTest extends PHPUnit_Framework_TestCase {
	public function setUp() {

		$mock = new MockHandler([
    		new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>
			<LnpOrderResponse>
			<OrderId>d28b36f7-fa96-49eb-9556-a40fca49f7c6</OrderId>
			<Status>
			<Code>201</Code><Description>Order request received. Please use the order id to check the status of your order later.</Description>
			</Status><ProcessingStatus>PENDING_DOCUMENTS</ProcessingStatus>
			<LoaAuthorizingPerson>John Doe</LoaAuthorizingPerson><Subscriber>
			<SubscriberType>BUSINESS</SubscriberType><BusinessName>Acme Corporation</BusinessName>
			<ServiceAddress><HouseNumber>1623</HouseNumber><StreetName>Brockton Ave #1</StreetName>
			<City>Los Angeles</City><StateCode>CA</StateCode><Zip>90025</Zip><Country>USA</Country>
			</ServiceAddress></Subscriber><BillingTelephoneNumber>6882015002</BillingTelephoneNumber>
			<ListOfPhoneNumbers><PhoneNumber>6882015025</PhoneNumber><PhoneNumber>6882015026</PhoneNumber></ListOfPhoneNumbers>
			<Triggered>false</Triggered>
			<BillingType>PORTIN</BillingType></LnpOrderResponse>"),
			new Response(200),
			new Response(200),
			new Response(200)
		]);

		$handler = HandlerStack::create($mock);

		$c = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
		$this->account = new Iris\Account(9500249, $c);
		$this->portins = $this->account->portins();
	}

	public function testSiteModel()
	{
        $portin = $this->portins->create(array(
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

		$this->assertEquals("d28b36f7-fa96-49eb-9556-a40fca49f7c6", $portin->OrderId);

		$portin->loas_send(__DIR__."/test.txt", array("Content-Type" => "application/pdf"));

		$portin->loas_update(__DIR__."/test.txt", 'test.txt', array("Content-Type" => "application/pdf"));

		$portin->loas_delete('test.txt');
	}

}
