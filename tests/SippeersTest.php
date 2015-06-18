<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class SippeersTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $sippeers;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/sites/2489/sippeers/9091']),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNSipPeersResponse>    <SipPeers>        <SipPeer>            <PeerId>500709</PeerId>            <PeerName>Test4 Peer</PeerName>            <IsDefaultPeer>true</IsDefaultPeer>            <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>            <VoiceHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </VoiceHosts>            <VoiceHostGroups/>            <SmsHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </SmsHosts>            <TerminationHosts>                <TerminationHost>                    <HostName>192.168.181.94</HostName>                    <Port>0</Port>                    <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                    <DataAllowed>true</DataAllowed>                </TerminationHost>            </TerminationHosts>        </SipPeer>        <SipPeer>            <PeerId>500705</PeerId>            <PeerName>Test2 Peer</PeerName>            <IsDefaultPeer>false</IsDefaultPeer>            <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>            <VoiceHosts>                <Host>                    <HostName>192.168.181.98</HostName>                </Host>            </VoiceHosts>            <VoiceHostGroups/>            <SmsHosts>                <Host>                    <HostName>192.168.181.98</HostName>                </Host>            </SmsHosts>            <TerminationHosts>                <TerminationHost>                    <HostName>192.168.181.98</HostName>                    <Port>0</Port>                    <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                    <DataAllowed>true</DataAllowed>                </TerminationHost>            </TerminationHosts>        </SipPeer>    </SipPeers></TNSipPeersResponse>    "),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNSipPeersResponse>    <SipPeers>        <SipPeer>            <PeerId>500709</PeerId>            <PeerName>Test4 Peer</PeerName>            <IsDefaultPeer>true</IsDefaultPeer>            <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>            <VoiceHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </VoiceHosts>            <VoiceHostGroups/>            <SmsHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </SmsHosts>            <TerminationHosts>                <TerminationHost>                    <HostName>192.168.181.94</HostName>                    <Port>0</Port>                    <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                    <DataAllowed>true</DataAllowed>                </TerminationHost>            </TerminationHosts>        </SipPeer>    </SipPeers></TNSipPeersResponse>    "),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SipPeerResponse>    <SipPeer>        <PeerId>500651</PeerId>        <PeerName>Something</PeerName>        <IsDefaultPeer>false</IsDefaultPeer>        <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>        <VoiceHosts>            <Host>                <HostName>192.168.181.2</HostName>            </Host>        </VoiceHosts>        <VoiceHostGroups/>        <SmsHosts>            <Host>                <HostName>192.168.181.2</HostName>            </Host>        </SmsHosts>        <TerminationHosts>            <TerminationHost>                <HostName>192.168.181.2</HostName>                <Port>0</Port>                <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                <DataAllowed>true</DataAllowed>            </TerminationHost>        </TerminationHosts>    </SipPeer></SipPeerResponse>"),
			new Response(200),
			new Response(200),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        $site = $account->sites()->create(["Id" => "9999"]);
		self::$sippeers = $site->sippeers();
    }

    public function testSippeerCreate() {
		$sippeer = self::$sippeers->create(array(
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

        $sippeer->save();

        $this->assertEquals("9091", $sippeer->PeerId);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeersGet() {
		$sippeers = self::$sippeers->get();

        $this->assertEquals(2, count($sippeers));
		$this->assertEquals("500709", $sippeers[0]->PeerId);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeersGetOne() {
		$sippeers = self::$sippeers->get();

        $this->assertEquals(1, count($sippeers));
		$this->assertEquals("500709", $sippeers[0]->PeerId);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeerGet() {
		$sippeer = self::$sippeers->sippeer("500651");

		$this->assertEquals("500651", $sippeer->PeerId);
		$this->assertEquals("192.168.181.2", $sippeer->VoiceHosts->Host->HostName);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/500651", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeerUpdate() {
		$sippeer = self::$sippeers->create(
			array("PeerId" => "2489")
		);

        $sippeer->save();

        $this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }


	public function testSiteDelete() {
		$sippeer = self::$sippeers->create(
			array("PeerId" => "2489")
		);

        $sippeer->delete();

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

}
