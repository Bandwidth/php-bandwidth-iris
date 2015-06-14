<?php
class SippeersTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$c = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL));
		$this->account = new Iris\Account(9500249, $c);
		$this->sites = $this->account->sites();
		$this->site = $this->sites->get_by_id("2297");
		$this->sippeers = $this->site->sippeers();
	}

	public function testSippeersAndSipperGetModel()
	{

		$sippeers = $this->sippeers->get();

		$sippeer0 = $sippeers[0];

		$sippeer1 = $this->sippeers->get_by_id($sippeer0->PeerId);

		$this->assertEquals($sippeer0->PeerName, $sippeer1->PeerName, "Names should be equal");
	}

	/**
     * @expectedException Iris\ResponseException
	 * @expectedExceptionCode 13563
     */
	public function testCreatePutDelete()
	{
		$sippeer0 = $this->sippeers->create(array(
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

		$this->assertFalse(is_null($sippeer0->PeerId), "Id should be present");

		$id = $sippeer0->PeerId;

		$sippeer0->PeerName = "New Name of Test5";

		$sippeer0->save();

		$sippeer1 = $this->sippeers->get_by_id($id);

		$this->assertEquals("New Name of Test5", $sippeer1->PeerName, "Name should be updated");

		$sippeer1->delete();

		$sippeer1 = $this->sippeers->get_by_id($id);

	}
}
