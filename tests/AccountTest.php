<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class AccountTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $account;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> <LineOptionOrderResponse><LineOptions> <CompletedNumbers><TelephoneNumber>2013223685</TelephoneNumber> </CompletedNumbers><Errors><Error><TelephoneNumber>5209072452</TelephoneNumber> <ErrorCode>5071</ErrorCode><Description>Telephone number is not available on the system.</Description></Error> <Error><TelephoneNumber>5209072451</TelephoneNumber> <ErrorCode>13518</ErrorCode><Description>CNAM for telephone number is applied at the Location level and it is notapplicable at the TN level.</Description> </Error></Errors> </LineOptions></LineOptionOrderResponse>"),
            new Response(200, [], "<?xml version=\"1.0\"?> <SearchResult><ResultCount>1</ResultCount> <TelephoneNumberDetailList><TelephoneNumberDetail> <City>KNIGHTDALE</City> <LATA>426</LATA> <RateCenter>KNIGHTDALE</RateCenter> <State>NC</State> <FullNumber>9192956932</FullNumber> <Tier>0</Tier><VendorId>49</VendorId> <VendorName>Bandwidth CLEC</VendorName></TelephoneNumberDetail> </TelephoneNumberDetailList></SearchResult>"),
            new Response(200, [], "<?xml version=\"1.0\"?> <SearchResult><ResultCount>2</ResultCount> <TelephoneNumberDetailList><TelephoneNumberDetail> <City>KNIGHTDALE</City> <LATA>426</LATA> <RateCenter>KNIGHTDALE</RateCenter> <State>NC</State> <FullNumber>9192956932</FullNumber> <Tier>0</Tier><VendorId>49</VendorId> <VendorName>Bandwidth CLEC</VendorName></TelephoneNumberDetail><TelephoneNumberDetail> <City>KNIGHTDALE</City> <LATA>426</LATA> <RateCenter>KNIGHTDALE</RateCenter> <State>NC</State> <FullNumber>9192956932</FullNumber> <Tier>0</Tier><VendorId>49</VendorId> <VendorName>Bandwidth CLEC</VendorName></TelephoneNumberDetail> </TelephoneNumberDetailList></SearchResult>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?> <SearchResult><ResultCount>5</ResultCount> <TelephoneNumberList><TelephoneNumber>9194390154</TelephoneNumber> <TelephoneNumber>9194390158</TelephoneNumber> <TelephoneNumber>9194390176</TelephoneNumber> <TelephoneNumber>9194390179</TelephoneNumber> <TelephoneNumber>9194390185</TelephoneNumber></TelephoneNumberList> </SearchResult>"),
            new Response(400, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> <SearchResult><Error> <Code>4000</Code> <Description>The area code of telephone numbers can not end with 11. </Description></Error><ResultCount>0</ResultCount> </SearchResult>"),
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/tnsreservation/2489']),
            new Response(200, [], "<?xml version=\"1.0\"?><ReservationResponse><Reservation> <ReservationId>0099ff73-da96-4303-8a0a-00ff316c07aa</ReservationId> <AccountId>14</AccountId> <ReservationExpires>0</ReservationExpires> <ReservedTn>2512027430</ReservedTn></Reservation> </ReservationResponse>"),
            new Response(200, []),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        self::$account = new Iris\Account(9500249, $client);
    }

    public function testLineOption() {
		$TnLineOptions = new \Iris\TnLineOptions(array(
			"TnLineOptions" => [
				[ "TelephoneNumber" => "5209072451", "CallingNameDisplay" => "off" ],
				[ "TelephoneNumber" => "5209072452", "CallingNameDisplay" => "on" ],
				[ "TelephoneNumber" => "5209072453", "CallingNameDisplay" => "off" ]
			]
		));

        $response = self::$account->lineOptionOrders($TnLineOptions);

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/lineOptionOrders", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbersSingle() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("KNIGHTDALE", $response[0]->City);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbers() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("KNIGHTDALE", $response[1]->City);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbers2() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("9194390154", $response[0]->TelephoneNumber[0]);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    /**
     * @expectedException \Iris\ResponseException
     * @expectedExceptionCode 4000
     */
    public function testAvailableNumbersError() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testTnReservation() {
        $resertation = self::$account->tnsreservations()->create(["ReservedTn" => "2512027430"]);
        $resertation->send();

        self::$index++;
        $json = '{"ReservedTn":"2512027430","ReservationId":"2489"}';
		$this->assertEquals($json, json_encode($resertation->to_array()));
        $this->assertEquals("2489", $resertation->get_id());
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testGetTnReservation() {
        $resertation = self::$account->tnsreservations()->tnsreservation("0099ff73-da96-4303-8a0a-00ff316c07aa");

        $json = '{"ReservedTn":"2512027430","ReservationId":"0099ff73-da96-4303-8a0a-00ff316c07aa","ReservationExpires":"0","AccountId":"14"}';
		$this->assertEquals($json, json_encode($resertation->to_array()));
        $this->assertEquals("0099ff73-da96-4303-8a0a-00ff316c07aa", $resertation->get_id());
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation/0099ff73-da96-4303-8a0a-00ff316c07aa", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testDeleteTnReservation() {
        $resertation = self::$account->tnsreservations()->create(["ReservationId" => "0099ff73-da96-4303-8a0a-00ff316c07aa"]);
        $resertation->delete();

        $this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation/0099ff73-da96-4303-8a0a-00ff316c07aa", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

}
