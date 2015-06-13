<?php
class SippeersTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$c = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL));
		$this->account = new Iris\Account(9500249, $c);
		$this->sites = $this->account->sites();
	}

	public function testSiteModel()
	{
		$site = $this->sites->get_by_id("2297");

		$sippeers = $site->sippeers()->get();

		echo json_encode($sippeers[0]->to_array());
	}

}
