<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class PortinsTest extends PHPUnit_Framework_TestCase {
	public static $container;
    public static $portins;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><LnpOrderResponse><OrderId>d28b36f7-fa96-49eb-9556-a40fca49f7c6</OrderId><Status><Code>201</Code><Description>Order request received. Please use the order id to check the status of your order later.</Description></Status><ProcessingStatus>PENDING_DOCUMENTS</ProcessingStatus><LoaAuthorizingPerson>John Doe</LoaAuthorizingPerson><Subscriber><SubscriberType>BUSINESS</SubscriberType><BusinessName>Acme Corporation</BusinessName><ServiceAddress><HouseNumber>1623</HouseNumber><StreetName>Brockton Ave #1</StreetName><City>Los Angeles</City><StateCode>CA</StateCode><Zip>90025</Zip><Country>USA</Country></ServiceAddress></Subscriber><BillingTelephoneNumber>6882015002</BillingTelephoneNumber><ListOfPhoneNumbers><PhoneNumber>6882015025</PhoneNumber><PhoneNumber>6882015026</PhoneNumber></ListOfPhoneNumbers><Triggered>false</Triggered><BillingType>PORTIN</BillingType></LnpOrderResponse>"),
			new Response(200),
			new Response(200),
			new Response(200),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><FileMetaData><DocumentName>test.txt</DocumentName><DocumentType>LOA</DocumentType></FileMetaData>"),
			new Response(200),
			new Response(200),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>    <fileListResponse>        <fileCount>0</fileCount>        <resultCode>0</resultCode>        <resultMessage>No LOA files found for order</resultMessage>    </fileListResponse>"),
			new Response(200),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><ActivationStatusResponse>    <ActivationStatus>        <AutoActivationDate>2014-08-29T18:30:00+03:00</AutoActivationDate>        <ActivatedTelephoneNumbersList>            <TelephoneNumber>6052609021</TelephoneNumber>            <TelephoneNumber>6052609021</TelephoneNumber>        </ActivatedTelephoneNumbersList>        <NotYetActivatedTelephoneNumbersList/>    </ActivationStatus></ActivationStatusResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><ActivationStatusResponse>    <ActivationStatus>        <AutoActivationDate>2014-08-29T18:30:00+03:00</AutoActivationDate>        <ActivatedTelephoneNumbersList>            <TelephoneNumber>6052609021</TelephoneNumber>            <TelephoneNumber>6052609021</TelephoneNumber>        </ActivatedTelephoneNumbersList>        <NotYetActivatedTelephoneNumbersList/>    </ActivationStatus></ActivationStatusResponse>"),
			new Response(200, [], "<?xml version=\"1.0\"?> <LnpOrderResponse><OrderId>0fe651a2-6ffc-4758-b7b7-e3eed66409ec</OrderId> <Status><Code>200</Code><Description>Supp request received. Please use the order id to check the status of your order later.</Description></Status><ProcessingStatus>REQUESTED_SUPP</ProcessingStatus><RequestedFocDate>2012-08-30T00:00:00Z</RequestedFocDate> </LnpOrderResponse>")
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$portins = $account->portins();
    }

	public function testPortinsCreate()
	{
        $portin = self::$portins->create(array(
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

		$this->assertEquals("9882015026", $portin->ListOfPhoneNumbers->PhoneNumber[1]);
		$this->assertEquals("Brockton Ave", $portin->Subscriber->ServiceAddress->StreetName);

		$portin->save();

		$this->assertEquals("d28b36f7-fa96-49eb-9556-a40fca49f7c6", $portin->OrderId);

		$this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins", self::$container[self::$index]['request']->getUri());
        self::$index++;
	}

	public function testPortinsLoasSend()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));
		$portin->loas_send(__DIR__."/test.txt", array("Content-Type" => "application/pdf"));
		$this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasUpdate()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));
		$portin->loas_update(__DIR__."/test.txt", 'test.txt', array("Content-Type" => "application/pdf"));
		$this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasDelete()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));
		$portin->loas_delete('test.txt');
		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasGetMetadata()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));
		$meta = $portin->get_metadata('test.txt');

		$this->assertEquals("test.txt", $meta->DocumentName);
		$this->assertEquals("LOA", $meta->DocumentType);

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt/metadata", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasSetMetadata()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));

		$meta_new = new \Iris\FileMetaData(array(
			"DocumentName" => "text.txt",
			"DocumentType" => "INVOICE"
		));
		$portin->set_metadata('test.txt', $meta_new);

		$this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt/metadata", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasDeleteMetadata()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));

		$portin->delete_metadata('test.txt');

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt/metadata", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsGetLoas()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));

		$portin->get_loas(true);

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas?metadata=true", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsDelete() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));

		$portin->delete();

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsGetActivationStatus() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));

		$status = $portin->get_activation_status();

		$this->assertEquals("6052609021", $status->ActivatedTelephoneNumbersList->TelephoneNumber[0]);
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/activationStatus", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsSetActivationStatus() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		));

		$status = $portin->set_activation_status(new \Iris\ActivationStatus(array(
			"AutoActivationDate" => "2014-08-30T18:30:00+03:00"
		)));

		$this->assertEquals("6052609021", $status->ActivatedTelephoneNumbersList->TelephoneNumber[0]);
		$this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/activationStatus", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsUpdate() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6",
			"Status" => array(
				"Code" => 0,
				"Description" => "Empty"
			)
		));
		$portin->RequestedFocDate = "2012-08-30T00:00:00.000Z";
		$portin->save();

		$this->assertEquals(200, $portin->Status->Code);
		$this->assertEquals("Supp request received. Please use the order id to check the status of your order later.", $portin->Status->Description);
		$this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}
}
