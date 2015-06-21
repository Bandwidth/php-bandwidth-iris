<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class SippeersTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $lidbs;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, [], "<?xml version=\"1.0\"?><ResponseSelectWrapper><ListOrderIdUserIdDate><TotalCount>2122</TotalCount><OrderIdUserIdDate><accountId>9999999</accountId><CountOfTNs>0</CountOfTNs><lastModifiedDate>2014-02-25T16:02:43.195Z</lastModifiedDate><OrderType>lidb</OrderType><OrderDate>2014-02-25T16:02:43.195Z</OrderDate><orderId>abe36738-6929-4c6f-926c-88e534e2d46f</orderId><OrderStatus>FAILED</OrderStatus><TelephoneNumberDetails/><userId>team_ua</userId></OrderIdUserIdDate><!-- ...SNIP... --><OrderIdUserIdDate><accountId>9999999</accountId><CountOfTNs>0</CountOfTNs><lastModifiedDate>2014-02-25T16:02:39.021Z</lastModifiedDate><OrderType>lidb</OrderType><OrderDate>2014-02-25T16:02:39.021Z</OrderDate><orderId>ba5b6297-139b-4430-aab0-9ff02c4362f4</orderId><OrderStatus>FAILED</OrderStatus><userId>team_ua</userId></OrderIdUserIdDate></ListOrderIdUserIdDate></ResponseSelectWrapper>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><LidbOrderResponse><LidbOrder><CustomerOrderId>testCustomerOrderId</CustomerOrderId><orderId>255bda29-fc57-44e8-a6c2-59b45388c6d0</orderId>    <OrderCreateDate>2014-05-28T14:46:21.724Z</OrderCreateDate><ProcessingStatus>RECEIVED</ProcessingStatus><CreatedByUser>jbm</CreatedByUser><LastModifiedDate>2014-02-20T19:33:17.600Z</LastModifiedDate><OrderCompleteDate>2014-02-20T19:33:17.600Z</OrderCompleteDate><ErrorList/><LidbTnGroups><LidbTnGroup><TelephoneNumbers><TelephoneNumber>4082213311</TelephoneNumber></TelephoneNumbers><FullNumber>8042105618</FullNumber><SubscriberInformation>Fred</SubscriberInformation><UseType>BUSINESS</UseType><Visibility>PRIVATE</Visibility></LidbTnGroup><LidbTnGroup><TelephoneNumbers><TelephoneNumber>4082212850</TelephoneNumber><TelephoneNumber>4082213310</TelephoneNumber></TelephoneNumbers><FullNumber>8042105760</FullNumber><SubscriberInformation>Fred</SubscriberInformation><UseType>RESIDENTIAL</UseType><Visibility>PUBLIC</Visibility></LidbTnGroup></LidbTnGroups></LidbOrder></LidbOrderResponse>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL, 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$lidbs = $account->lidbs();
    }

    public function testLidbsGet() {
		$lidbs = self::$lidbs->get(["lastModifiedAfter" => "yy-mm-dd", "telephoneNumber"=> "888"]);

        $json = '{"orderId":"abe36738-6929-4c6f-926c-88e534e2d46f","accountId":"9999999","CountOfTNs":"0","userId":"team_ua","lastModifiedDate":"2014-02-25T16:02:43.195Z","OrderType":"lidb","OrderDate":"2014-02-25T16:02:43.195Z","OrderStatus":"FAILED"}';
        $this->assertEquals($json, json_encode($lidbs[0]->to_array()));

        $this->assertEquals("abe36738-6929-4c6f-926c-88e534e2d46f", $lidbs[0]->get_id());
        $this->assertEquals("2", count($lidbs));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/lidbs?lastModifiedAfter=yy-mm-dd&telephoneNumber=888", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testLidbsPost() {
        $order_data = [
            "LidbTnGroups" => [
                "LidbTnGroup" => [
                    [
                        "TelephoneNumbers" => [
                            "TelephoneNumber" => "4352154856"
                        ],
                        "SubscriberInformation" => "Steve",
                        "UseType" => "RESIDENTIAL",
                        "Visibility" => "PUBLIC"
                    ],
                    [
                        "TelephoneNumbers" => [
                            "TelephoneNumber" => "4352154855"
                        ],
                        "SubscriberInformation" => "Steve",
                        "UseType" => "RESIDENTIAL",
                        "Visibility" => "PUBLIC"
                    ]
                ]
            ]
        ];

		$lidb = self::$lidbs->create($order_data);
        $json = '{"LidbTnGroups":{"LidbTnGroup":[{"TelephoneNumbers":{"TelephoneNumber":"4352154856"},"SubscriberInformation":"Steve","UseType":"RESIDENTIAL","Visibility":"PUBLIC"},{"TelephoneNumbers":{"TelephoneNumber":"4352154855"},"SubscriberInformation":"Steve","UseType":"RESIDENTIAL","Visibility":"PUBLIC"}]}}';
        $this->assertEquals($json, json_encode($lidb->to_array()));


        $lidb->post();

        $json = '{"CustomerOrderId":"testCustomerOrderId","orderId":"255bda29-fc57-44e8-a6c2-59b45388c6d0","LastModifiedDate":"2014-02-20T19:33:17.600Z","OrderCreateDate":"2014-05-28T14:46:21.724Z","ProcessingStatus":"RECEIVED","CreatedByUser":"jbm","OrderCompleteDate":"2014-02-20T19:33:17.600Z","LidbTnGroups":{"LidbTnGroup":[{"TelephoneNumbers":{"TelephoneNumber":"4082213311"},"SubscriberInformation":"Fred","UseType":"BUSINESS","Visibility":"PRIVATE","FullNumber":"8042105618"},{"TelephoneNumbers":{"TelephoneNumber":["4082212850","4082213310"]},"SubscriberInformation":"Fred","UseType":"RESIDENTIAL","Visibility":"PUBLIC","FullNumber":"8042105760"}]}}';
        $this->assertEquals($json, json_encode($lidb->to_array()));

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/lidbs", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    //  <LidbOrder>
    // <LidbTnGroups>
    //     <LidbTnGroup>
    //         <TelephoneNumbers>
    //             <TelephoneNumber>4352154856</TelephoneNumber>
    //         </TelephoneNumbers>
    //         <SubscriberInformation>Steve</SubscriberInformation>
    //         <UseType>RESIDENTIAL</UseType>
    //         <Visibility>PUBLIC</Visibility>
    //     </LidbTnGroup>
    //     <LidbTnGroup>
    //         <TelephoneNumbers>
    //             <TelephoneNumber>4352154855</TelephoneNumber>
    //         </TelephoneNumbers>
    //         <SubscriberInformation>Steve</SubscriberInformation>
    //         <UseType>RESIDENTIAL</UseType>
    //         <Visibility>PUBLIC</Visibility>
    //     </LidbTnGroup>
    // </LidbTnGroups>
    // </LidbOrder>


}
