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

}
