<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

use PHPUnit\Framework\TestCase;

class OAuthTest extends TestCase {
    public static $basicContainer;
    public static $basicAccount;
    public static $oauthContainer;
    public static $oauthAccount;
    public static $tokenAccount;
    public static $index = 0;

    public static $inserviceNumbersResponse = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNs><TotalCount>1</TotalCount><Links><first>link</first></Links><TelephoneNumbers><Count>1</Count><TelephoneNumber>8043024183</TelephoneNumber></TelephoneNumbers></TNs>";

    // public static function setUpHandler($mockHandler, $container) {
    //     $handler = HandlerStack::create($mockHandler);
    //     $history = Middleware::history($container);
    //     $handler->push($history);
        
    //     return $handler;
    // }

    public static function setUpBeforeClass(): void {
        self::$basicContainer = [];
        $basicMock = new MockHandler([
            new Response(200, [], self::$inserviceNumbersResponse)
        ]);
        $basicHandler = HandlerStack::create($basicMock);
        $basicHistory = Middleware::history(self::$basicContainer);
        $basicHandler->push($basicHistory);
        $basicClient = new Iris\Client('username', 'password', Array(
            'url' => 'https://api.basic.test.com/v1.0',
            'handler' => $basicHandler,
        ));
        self::$basicAccount = new Iris\Account(9500249, $basicClient);
            
        self::$oauthContainer = [];
        $oauthMock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"abcdef123456\",\"expires_in\":3600}"),
            new Response(200, [], self::$inserviceNumbersResponse),
        ]);
        $oauthHandler = HandlerStack::create($oauthMock);
        $oauthHistory = Middleware::history(self::$oauthContainer);
        $oauthHandler->push($oauthHistory);
        $oauthClient = new Iris\Client(null, null, Array(
            'url' => 'https://api.oauth.test.com/v1.0',
            'handler' => $oauthHandler,
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
        ));
        self::$oauthAccount = new Iris\Account(9500249, $oauthClient);

        // $tokenClient = new Iris\Client(null, null, Array(
        //     'url' => 'https://api.token.test.com/v1.0',
        //     'handler' => $handler,
        //     'accessToken' => 'access_token',
        //     'accessTokenExpiration' => time() + 3600
        // ));
        // self::$tokenAccounts = new Iris\Account(9500249, $tokenClient);

        // $expiredTokenClient = new Iris\Client(null, null, Array(
        //     'url' => 'https://api.token.test.com/v1.0',
        //     'handler' => $handler,
        //     'accessToken' => 'expired_token',
        //     'accessTokenExpiration' => time() - 3600
        // ));
        // self::$expiredTokenAccounts = new Iris\Account(9500249, $expiredTokenClient);
    }

    public function testBasicAuth() {     
        self::$basicAccount->inserviceNumbers();

        $request = self::$basicContainer[0]['request'];
        $this->assertEquals("https://api.basic.test.com/v1.0/accounts/9500249/inserviceNumbers", (string)$request->getUri());
        $this->assertEquals("GET", $request->getMethod());
        $this->assertEquals("Basic " . base64_encode('username:password'), $request->getHeaderLine('Authorization'));
    }

    public function testOAuth() {     
        self::$oauthAccount->inserviceNumbers();

        // print oauth container
        for ($i = 0; $i < count(self::$oauthContainer); $i++) {
            $req = self::$oauthContainer[$i]['request'];
            echo "Request " . ($i + 1) . ":\n";
            echo (string)$req->getUri() . "\n";
            echo $req->getMethod() . "\n";
            echo $req->getHeaderLine('Authorization') . "\n";
            echo "\n";
        }

        $tokenRequest = self::$oauthContainer[0]['request'];
        $this->assertEquals("https://api.bandwidth.com/api/v1/oauth2/token", (string)$tokenRequest->getUri());
        $this->assertEquals("POST", $tokenRequest->getMethod());
        $this->assertEquals("Basic " . base64_encode('client_id:client_secret'), $tokenRequest->getHeaderLine('Authorization'));
        // self::$index++;

        $apiRequest = self::$oauthContainer[1]['request'];
        $this->assertEquals("https://api.oauth.test.com/v1.0/accounts/9500249/inserviceNumbers", (string)$apiRequest->getUri());
        $this->assertEquals("GET", $apiRequest->getMethod());
        $this->assertEquals("Bearer abcdef123456", $apiRequest->getHeaderLine('Authorization'));
        // self::$index++;
    }

    // public function testToken() {     
    //     self::$tokenAccounts->inserviceNumbers();
    //     echo "making request with token\n";

    //     // self::$basicAuthAccounts->inserviceNumbers();
    //     $request = self::$container[self::$index]['request'];
    //     $this->assertEquals("GET", $request->getMethod());
    //     $this->assertEquals("Basic " . base64_encode('usernme:password'), $request->getHeaderLine('Authorization'));
    //     self::$index++;
    // }

}
