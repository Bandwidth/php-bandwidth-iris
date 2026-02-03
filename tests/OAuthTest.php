<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

use PHPUnit\Framework\TestCase;

class OAuthTest extends TestCase {
    private const API_URL = 'https://api.test.com/v1.0';
    private const INSERVICE_URL = self::API_URL . '/accounts/9500249/inserviceNumbers';
    private const TOKEN_URL = 'https://api.bandwidth.com/api/v1/oauth2/token';

    private const INSERVICE_RESPONSE = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNs><TotalCount>1</TotalCount><Links><first>link</first></Links><TelephoneNumbers><Count>1</Count><TelephoneNumber>8043024183</TelephoneNumber></TelephoneNumbers></TNs>";
    private const TOKEN_RESPONSE = '{"access_token":"abcdef123456","expires_in":3600}';

    private const TOKEN_REQUEST_AUTH_STRING = 'Basic Y2xpZW50X2lkOmNsaWVudF9zZWNyZXQ=';
    private const BEARER_AUTH_STRING = 'Bearer abcdef123456';

    private function makeAccount(array $responses, array $clientOptions, array &$container, ?string $login = null, ?string $password = null): Iris\Account {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $history = Middleware::history($container);
        $handler->push($history);
        $client = new Iris\Client($login, $password, array_merge(['url' => self::API_URL, 'handler' => $handler], $clientOptions));
        return new Iris\Account(9500249, $client);
    }

    private function assertRequest(array $container, int $idx, string $method, string $uri, ?string $authHeader = null): void {
        $request = $container[$idx]['request'];
        $this->assertSame($uri, (string)$request->getUri());
        $this->assertSame($method, $request->getMethod());
        if ($authHeader !== null) {
            $this->assertSame($authHeader, $request->getHeaderLine('Authorization'));
        }
    }

    public function testBasicAuth(): void {
        $container = [];
        $account = $this->makeAccount([
            new Response(200, [], self::INSERVICE_RESPONSE),
        ], [], $container, 'username', 'password');

        $account->inserviceNumbers();
        $this->assertRequest($container, 0, 'GET', self::INSERVICE_URL, 'Basic ' . base64_encode('username:password'));
    }

    public function testOAuth(): void {
        $container = [];
        $account = $this->makeAccount([
            new Response(200, [], self::TOKEN_RESPONSE),
            new Response(200, [], self::INSERVICE_RESPONSE),
            new Response(200, [], self::INSERVICE_RESPONSE),
        ], [
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
        ], $container);

        $account->inserviceNumbers();
        $account->inserviceNumbers();

        $this->assertRequest($container, 0, 'POST', self::TOKEN_URL, self::TOKEN_REQUEST_AUTH_STRING);
        $this->assertRequest($container, 1, 'GET', self::INSERVICE_URL, self::BEARER_AUTH_STRING);
        $this->assertRequest($container, 2, 'GET', self::INSERVICE_URL, self::BEARER_AUTH_STRING);
    }

    public function testToken(): void {
        $container = [];
        $account = $this->makeAccount([
            new Response(200, [], self::INSERVICE_RESPONSE),
        ], [
            'accessToken' => 'access_token',
            'accessTokenExpiration' => time() + 3600,
        ], $container);

        $account->inserviceNumbers();
        $this->assertRequest($container, 0, 'GET', self::INSERVICE_URL, 'Bearer access_token');
    }

    public function testExpiredToken(): void {
        $container = [];
        $account = $this->makeAccount([
            new Response(200, [], self::TOKEN_RESPONSE),
            new Response(200, [], self::INSERVICE_RESPONSE),
        ], [
            'accessToken' => 'expired_token',
            'accessTokenExpiration' => time() - 3600,
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
        ], $container);

        $account->inserviceNumbers();
        $this->assertRequest($container, 0, 'POST', self::TOKEN_URL, self::TOKEN_REQUEST_AUTH_STRING);
        $this->assertRequest($container, 1, 'GET', self::INSERVICE_URL, self::BEARER_AUTH_STRING);
    }
}
