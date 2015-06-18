<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class NotesTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $account;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Notes>    <Note>        <Id>11425</Id>        <UserId>byo_dev</UserId>        <Description>Test Note</Description>        <LastDateModifier>2015-06-18T04:19:59.000Z</LastDateModifier>    </Note></Notes>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Notes>    <Note>        <Id>11425</Id>        <UserId>byo_dev</UserId>        <Description>Test Note</Description>        <LastDateModifier>2015-06-18T04:19:59.000Z</LastDateModifier>    </Note></Notes>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Notes>    <Note>        <Id>11425</Id>        <UserId>byo_dev</UserId>        <Description>Test Note</Description>        <LastDateModifier>2015-06-18T04:19:59.000Z</LastDateModifier>    </Note></Notes>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Notes>    <Note>        <Id>11425</Id>        <UserId>byo_dev</UserId>        <Description>Test Note</Description>        <LastDateModifier>2015-06-18T04:19:59.000Z</LastDateModifier>    </Note></Notes>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        self::$account = new Iris\Account(9500249, $client);
    }

    public function testNotesDisconnects() {
        self::$account->disconnects()->create(array("OrderId" => "b902dee1-0585-4258-becd-5c7e51ccf5e1"))->notes()->get();
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/disconnects/b902dee1-0585-4258-becd-5c7e51ccf5e1/notes", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testNotesOrders() {
        self::$account->orders()->create(array("Id" => "b902dee1-0585-4258-becd-5c7e51ccf5e1"))->notes()->get();
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders/b902dee1-0585-4258-becd-5c7e51ccf5e1/notes", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testNotesPortins() {
        self::$account->portins()->create(array("OrderId" => "b902dee1-0585-4258-becd-5c7e51ccf5e1"))->notes()->get();
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/b902dee1-0585-4258-becd-5c7e51ccf5e1/notes", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testNotesPortouts() {
        self::$account->portouts()->create(array("OrderId" => "b902dee1-0585-4258-becd-5c7e51ccf5e1"))->notes()->get();
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portouts/b902dee1-0585-4258-becd-5c7e51ccf5e1/notes", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
}
