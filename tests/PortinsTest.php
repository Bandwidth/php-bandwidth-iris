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
			new Response(200),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><FileMetaData><DocumentName>test.txt</DocumentName><DocumentType>LOA</DocumentType></FileMetaData>"),
			new Response(200),
			new Response(200),
			new Response(200, [], '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
				<fileListResponse>
					<fileCount>0</fileCount>
    				<resultCode>0</resultCode>
    				<resultMessage>No LOA files found for order</resultMessage>
				</fileListResponse>'),
			new Response(200),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>
			<ActivationStatusResponse>
				<ActivationStatus>
					<AutoActivationDate>2014-08-29T18:30:00+03:00</AutoActivationDate>
					<ActivatedTelephoneNumbersList>
						<TelephoneNumber>6052609021</TelephoneNumber>
						<TelephoneNumber>6052609021</TelephoneNumber>
					</ActivatedTelephoneNumbersList>
					<NotYetActivatedTelephoneNumbersList/>
				</ActivationStatus>
			</ActivationStatusResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>
			<ActivationStatusResponse>
				<ActivationStatus>
					<AutoActivationDate>2014-08-29T18:30:00+03:00</AutoActivationDate>
					<ActivatedTelephoneNumbersList>
						<TelephoneNumber>6052609021</TelephoneNumber>
						<TelephoneNumber>6052609021</TelephoneNumber>
					</ActivatedTelephoneNumbersList>
					<NotYetActivatedTelephoneNumbersList/>
				</ActivationStatus>
			</ActivationStatusResponse>"),
			new Response(200, [], '<?xml version="1.0"?> <LnpOrderResponse>
<OrderId>0fe651a2-6ffc-4758-b7b7-e3eed66409ec</OrderId> <Status>
<Code>200</Code>
<Description>Supp request received. Please use the order id to check the status of your order later.
</Description></Status>
<ProcessingStatus>REQUESTED_SUPP</ProcessingStatus>
<RequestedFocDate>2012-08-30T00:00:00Z</RequestedFocDate> </LnpOrderResponse>')
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

		$meta = $portin->get_metadata('text.txt');

		$this->assertEquals("test.txt", $meta->DocumentName);

		$this->assertEquals("LOA", $meta->DocumentType);

		$meta_new = new \Iris\FileMetaData(array(
			"DocumentName" => "text.txt",
			"DocumentType" => "INVOICE"
		));

		$portin->set_metadata('text.txt', $meta_new);

		$portin->delete_metadata('text.txt');

		$loas = $portin->get_loas(true);

		$portin->delete();

        $portin->get_activation_status()->ActivatedTelephoneNumbersList->TelephoneNumber[0];

		$portin->set_activation_status(new \Iris\ActivationStatus(array(
			"AutoActivationDate" => "2014-08-30T18:30:00+03:00"
		)))->ActivatedTelephoneNumbersList->TelephoneNumber[0];

		$portin->RequestedFocDate = "2012-08-30T00:00:00.000Z";

		$portin->WirelessInfo = new Iris\WirelessInfo(array(
			"AccountNumber" => "77129766500001",
			"PinNumber" => "6232"
		));

		$portin->save();

		$this->assertEquals("200", $portin->Status->Code);
	}

}
