<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class SiteTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $sites;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/sites/2489']),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SitesResponse>    <Sites>        <Site>            <Id>2297</Id>            <Name>API Test Site</Name>        </Site>        <Site>            <Id>2301</Id>            <Name>My First Site</Name>            <Description>A Site From Node SDK Examples</Description>        </Site>    </Sites></SitesResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SitesResponse>    <Sites>        <Site>            <Id>2297</Id>            <Name>API Test Site</Name>        </Site></Sites></SitesResponse>"),
			new Response(200),
			new Response(200),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$sites = $account->sites();
    }

    public function testSiteCreate() {
		$site = self::$sites->create(
			array("Name" => "Test Site",
				"Address" => array(
					"City" => "Raleigh",
					"AddressType" => "Service",
					"HouseNumber" => "1",
					"StreetName" => "Avenue",
					"StateCode" => "NC"
			)));

        $site->save();

        $this->assertEquals("2489", $site->Id);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSiteGet() {
		$sites = self::$sites->get();

        $this->assertEquals(2, count($sites));
		$this->assertEquals("2297", $sites[0]->Id);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
	public function testSiteGetOne() {
		$sites = self::$sites->get();

        $this->assertEquals(1, count($sites));
		$this->assertEquals("2297", $sites[0]->Id);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }


	public function testSiteUpdate() {
		$site = self::$sites->create(
			array("Id" => "2489")
		);

        $site->save();

        $this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSiteDelete() {
		$site = self::$sites->create(
			array("Id" => "2489")
		);

		$site->delete();

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

}
