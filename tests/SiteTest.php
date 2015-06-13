<?php
class SiteTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$c = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL));
		$this->account = new Iris\Account(9500249, $c);
		$this->sites = $this->account->sites();
	}
	/**
     * @expectedException GuzzleHttp\Exception\ClientException
     */
	public function testSiteModel()
	{
        $site = $this->sites->create(
		array("Name" => "Test Site",
            "Address" => array(
                "City" => "Raleigh",
                "AddressType" => "Service",
                "HouseNumber" => "1",
                "StreetName" => "Avenue",
                "StateCode" => "NC"
        )));

        $this->assertFalse(is_null($site->id), "Id should be present");

		$site->Address->City = "New York";

		$this->assertEquals("New York", $site->Address->City, "City should be updated");

		$site->save();

		$id = $site->id;

		$site_reloaded = $this->sites->get_by_id($id);

		$this->assertEquals("New York", $site_reloaded->Address->City, "City should be updated for reloaded site");

		$site_reloaded->delete();

		$site_reloaded = $this->sites->get_by_id($id);
	}

}
