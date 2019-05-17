<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class BadCredsTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $client;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(401, [], ""),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        self::$client = new Iris\Client("test", "test", Array('url' => 'https://test.dashboard.bandwidth.com/', 'handler' => $handler));
    }

    /**
     * @expectedException   Iris\ResponseException
     * @expectedExceptionMessageRegExp #^.*resulted in a.*$#
     * @expectedExceptionCode 401
     */
    public function testAuthFail() {
        $c = new \Iris\Cities(self::$client);
        try {
            $cities = $c->getList(["state" => "NC"]);
        } catch (ClientException $e) {
            $this->assertTrue(!empty($e->getMessage()));
            throw $e;
        }
    }
}
