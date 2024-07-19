<?php

use PHPUnit\Framework\TestCase;

class SubscriptionsNew extends TestCase {
    public static $container;
    public static $subscriptions;
    public static $index = 0;

    public static function setUpBeforeClass(): void {
        $client = new Iris\Client("test", "test", Array('url' => 'https://test.com'));
        $account = new Iris\Account(9500249, $client);
        self::$subscriptions = $account->subscriptions();
    }

	public function testSubsCreate() {
		$subscription = self::$subscriptions->create([
            "OrderType" => "portins",
            "OrderId" => "98939562-90b0-40e9-8335-5526432d9741",
            "EmailSubscription" => [
                "Email" => "test@test.com",
                "DigestRequested" => "DAILY"
            ],
            "CallbackCredentials" => [
                "BasicAuthentication" => [
                    "Username" => "username",
                    "Password" => "password"
                ]
            ],
            "PublicKey" => "testkey"
        ], false);

        $json = '{"OrderType":"portins","OrderId":"98939562-90b0-40e9-8335-5526432d9741","EmailSubscription":{"Email":"test@test.com","DigestRequested":"DAILY"},"CallbackCredentials":{"BasicAuthentication":{"Username":"username","Password":"password"}},"PublicKey":"testkey"}';
		$this->assertEquals($json, json_encode($subscription->to_array()));

    }

}
