<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class TnsTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $tns;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TelephoneNumbersResponse>    <TelephoneNumberCount>2</TelephoneNumberCount>    <Links>        <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/tns?size=500&amp;page=1&gt;;rel=\"first\";</first>    </Links>    <TelephoneNumbers>        <TelephoneNumber>            <City>CHESAPEAKE</City>            <Lata>252</Lata>            <State>VA</State>            <FullNumber>7576768750</FullNumber>            <Tier>0</Tier>            <VendorId>49</VendorId>            <VendorName>Bandwidth CLEC</VendorName>            <RateCenter>NRFOLKZON1</RateCenter>            <Status>PortInPendingFoc</Status>            <AccountId>9500249</AccountId>            <LastModified>2015-06-03T15:10:13.000Z</LastModified>        </TelephoneNumber>        <TelephoneNumber>            <City>AGOURA</City>            <Lata>730</Lata>            <State>CA</State>            <FullNumber>8183386247</FullNumber>            <Tier>0</Tier>            <VendorId>49</VendorId>            <VendorName>Bandwidth CLEC</VendorName>            <RateCenter>AGOURA    </RateCenter>            <Status>Inservice</Status>            <AccountId>9500249</AccountId>            <LastModified>2015-05-30T14:40:54.000Z</LastModified>        </TelephoneNumber>    </TelephoneNumbers></TelephoneNumbersResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TelephoneNumberResponse>    <TelephoneNumber>7576768750</TelephoneNumber>    <Status>PortInPendingFoc</Status>    <LastModifiedDate>2015-06-03T15:10:13.000Z</LastModifiedDate>    <OrderCreateDate>2015-06-03T15:10:12.808Z</OrderCreateDate>    <OrderId>98939562-90b0-40e9-8335-5526432d9741</OrderId>    <OrderType>PORT_NUMBER_ORDER</OrderType>    <SiteId>2297</SiteId>    <AccountId>9500249</AccountId></TelephoneNumberResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TelephoneNumberResponse>    <TelephoneNumber>7576768750</TelephoneNumber>    <Status>PortInPendingFoc</Status>    <LastModifiedDate>2015-06-03T15:10:13.000Z</LastModifiedDate>    <OrderCreateDate>2015-06-03T15:10:12.808Z</OrderCreateDate>    <OrderId>98939562-90b0-40e9-8335-5526432d9741</OrderId>    <OrderType>PORT_NUMBER_ORDER</OrderType>    <SiteId>2297</SiteId>    <AccountId>9500249</AccountId></TelephoneNumberResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Site>    <Id>2297</Id>    <Name>API Test Site</Name></Site>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SipPeer>    <Id>500651</Id>    <Name>Something</Name></SipPeer>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNReservation><ReservationId>123</ReservationId><AccountId>111</AccountId><ReservationExpires>int (seconds)</ReservationExpires><ReservedTn>6136211234</ReservedTn></TNReservation>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$tns = new Iris\Tns(null, $client);
    }

	public function testTnsGet() {
		$tns = self::$tns->get(["page" => 1, "size" => 10 ]);

        $this->assertEquals(2, count($tns));
		$this->assertEquals("7576768750", $tns[0]->FullNumber);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/tns?page=1&size=10", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testTnGet() {
        $tn = self::$tns->tn("7576768750");

        $this->assertEquals("PortInPendingFoc", $tn->Status);
        $this->assertEquals("98939562-90b0-40e9-8335-5526432d9741", $tn->OrderId);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/tns/7576768750", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testSiteGet() {
        $tn = self::$tns->tn("7576768750");
        self::$index++;

        $site = $tn->site();

        $this->assertEquals("2297", $site->Id);
        $this->assertEquals("API Test Site", $site->Name);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/tns/7576768750/sites", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testSippeerGet() {
        $tn = self::$tns->create([ "FullNumber" => "7576768750", "AccountId" => "9500249", "SiteId" => "2297"]);

        $sippeer = $tn->sippeer();

        $this->assertEquals("500651", $sippeer->PeerId);
        $this->assertEquals("Something", $sippeer->PeerName);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/tns/7576768750/sippeers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testTNReservationGet() {
        $tn = self::$tns->create([ "FullNumber" => "7576768750", "AccountId" => "9500249", "SiteId" => "2297"]);

        $tnreservation = $tn->tnreservation();

        $json = '{"ReservedTn":"6136211234","ReservationId":"123","ReservationExpires":"int (seconds)","AccountId":"111"}';
		$this->assertEquals($json, json_encode($tnreservation->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/tns/7576768750/tnreservation", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

}
