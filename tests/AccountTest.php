<?php

/* Unit tests for accounts. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * accounts/14
 */
require_once("lib/Client.php");

class AccountsTest extends PHPUnit_Framework_TestCase {
	protected function setUp()
  {
		$this->client = new TestClient('', '', Array('url' => 'https://api.test.inetwork.com/v1.0/'));
  	$this->account = new Iris\Account(14, $this->client);
  }
	public function testGetAccountInfo()
	{
		$this->client->setStringResponse("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><AccountResponse><Account><AccountId>14</AccountId><CompanyName>CWI Hosting</CompanyName><AccountType>Business</AccountType><NenaId></NenaId><Tiers><Tier>0</Tier></Tiers><Address><HouseNumber>60</HouseNumber><HouseSuffix></HouseSuffix><PreDirectional></PreDirectional><StreetName>Pine</StreetName><StreetSuffix>St</StreetSuffix><PostDirectional></PostDirectional><AddressLine2></AddressLine2><City>Denver</City><StateCode>CO</StateCode><Zip>80016</Zip><PlusFour></PlusFour><County></County><Country>United States</Country><AddressType>Service</AddressType></Address><Contact><FirstName>Sanjay</FirstName><LastName>Rao</LastName><Phone>9195441234</Phone><Email>srao@bandwidth.com</Email></Contact><ReservationAllowed>true</ReservationAllowed><LnpEnabled>true</LnpEnabled><AltSpid>X455</AltSpid><SPID>9999</SPID><PortCarrierType>WIRELINE</PortCarrierType></Account></AccountResponse>");

		$response = $this->account->get();

		$this->assertEquals("CWI Hosting", $response->Account->CompanyName);
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/14", $this->client->getUrl());
	}

	public function testGetAvailableNumbers()
	{
		$this->client->setStringResponse("<SearchResult><ResultCount>2</ResultCount><TelephoneNumberDetailList><TelephoneNumberDetail><City>JERSEY CITY</City><LATA>224</LATA><RateCenter>JERSEYCITY</RateCenter><State>NJ</State><TelephoneNumber>2012001555</TelephoneNumber></TelephoneNumberDetail><TelephoneNumberDetail><City>JERSEY CITY</City><LATA>224</LATA><RateCenter>JERSEYCITY</RateCenter><State>NJ</State><TelephoneNumber>123123123</TelephoneNumber></TelephoneNumberDetail></TelephoneNumberDetailList></SearchResult>");

		$response = $this->account->availableNumbers(array("areaCode" => "777"));

		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/14/avalibleNumbers", $this->client->getUrl());
		$options = $this->client->getOptions();
		$this->assertArrayHasKey("areaCode", $options);
		$this->assertEquals("777", $options['areaCode']);
	}

}
