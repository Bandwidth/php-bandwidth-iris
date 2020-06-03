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
            new Response(200, [],"<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SearchResult/>"),
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/tnsreservation/2489']),
            new Response(200, [], "<?xml version=\"1.0\"?><ReservationResponse><Reservation> <ReservationId>0099ff73-da96-4303-8a0a-00ff316c07aa</ReservationId> <AccountId>14</AccountId> <ReservationExpires>0</ReservationExpires> <ReservedTn>2512027430</ReservedTn></Reservation> </ReservationResponse>"),
            new Response(200, []),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\"?><NumberPortabilityResponse>   <SupportedRateCenters />   <UnsupportedRateCenters>      <RateCenterGroup>         <RateCenter>BALTIMORE</RateCenter>         <City>BALTIMORE</City>         <State>MD</State>         <LATA>238</LATA>         <TnList>            <Tn>4109255199</Tn>            <Tn>4104685864</Tn>         </TnList>      </RateCenterGroup>      <RateCenterGroup>         <RateCenter>SPARKSGLNC</RateCenter>         <City>SPARKS GLENCOE</City>         <State>MD</State>         <LATA>238</LATA>         <TnList>            <Tn>4103431313</Tn>            <Tn>4103431561</Tn>         </TnList>      </RateCenterGroup>   </UnsupportedRateCenters>   <PartnerSupportedRateCenters>      <!-- Only available for fullCheck=offnetportability -->      <RateCenterGroup>         <RateCenter>FT COLLINS</RateCenter>         <City>FORT COLLINS</City>         <State>CO</State>         <LATA>656</LATA>         <Tiers>            <Tier>1</Tier>         </Tiers>         <TnList>            <Tn>4109235436</Tn>         </TnList>      </RateCenterGroup>   </PartnerSupportedRateCenters>   <SupportedLosingCarriers>      <LosingCarrierTnList>         <LosingCarrierSPID>9998</LosingCarrierSPID>         <LosingCarrierName>Test Losing Carrier L3</LosingCarrierName>         <LosingCarrierIsWireless>false</LosingCarrierIsWireless>         <LosingCarrierAccountNumberRequired>false</LosingCarrierAccountNumberRequired>         <LosingCarrierMinimumPortingInterval>5</LosingCarrierMinimumPortingInterval>         <TnList>            <Tn>4109255199</Tn>            <Tn>4104685864</Tn>            <Tn>4103431313</Tn>            <Tn>4103431561</Tn>         </TnList>      </LosingCarrierTnList>   </SupportedLosingCarriers>   <UnsupportedLosingCarriers /></NumberPortabilityResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SearchResultForAvailableNpaNxx>    <AvailableNpaNxxList>        <AvailableNpaNxx>            <City>COMPTON:COMPTON DA</City>            <Npa>424</Npa>            <Nxx>242</Nxx>            <Quantity>7</Quantity>            <State>CA</State>        </AvailableNpaNxx>        <AvailableNpaNxx>            <City>COMPTON:GARDENA DA</City>            <Npa>424</Npa>            <Nxx>246</Nxx>            <Quantity>5</Quantity>            <State>CA</State>        </AvailableNpaNxx>    </AvailableNpaNxxList></SearchResultForAvailableNpaNxx>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNs>    <TotalCount>4</TotalCount>    <Links>        <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/accounts/9500249/inserviceNumbers?size=500&amp;page=1&gt;;rel=\"first\";</first>    </Links>    <TelephoneNumbers>        <Count>4</Count>        <TelephoneNumber>8183386247</TelephoneNumber>        <TelephoneNumber>8183386249</TelephoneNumber>        <TelephoneNumber>8183386251</TelephoneNumber>        <TelephoneNumber>8183386252</TelephoneNumber>    </TelephoneNumbers></TNs>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Quantity><Count>4</Count></Quantity>"),
            new Response(200, [], "<?xml version=\"1.0\"?><TNs><TotalCount>4</TotalCount><Links><first></first></Links><TelephoneNumbers><Count>2</Count><TelephoneNumber>4158714245</TelephoneNumber><TelephoneNumber>4352154439</TelephoneNumber></TelephoneNumbers></TNs>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Quantity><Count>4</Count></Quantity>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><BdrCreationResponse><Info>Your BDR archive is currently being constructed</Info> </BdrCreationResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><BillingReportsRetrievalResponse>                                                <BillingReportList>                                                    <BillingReport>                                                        <BillingReportId>5f8734f0-d7c3-445c-b1e2-cdbb620e4ff7</BillingReportId>                                                        <BillingReportKind>DIDSNAP</BillingReportKind>                                                        <UserId>jbm</UserId>                                                        <ReportStatus>PROCESSING</ReportStatus>                                                        <Description>The requested report archive is still being constructed, please check back later.</Description>                                                        <CreatedDate>2017-11-01 14:12:16</CreatedDate>                                                        <DateRange>                                                            <StartDate>2017-01-01</StartDate>                                                            <EndDate>2017-09-30</EndDate>                                                        </DateRange>                                                    </BillingReport>                                                    <BillingReport>                                                        <BillingReportId>7680a54a-b1f1-4d43-8af6-bf3a701ad202</BillingReportId>                                                        <BillingReportKind>DIDSNAP</BillingReportKind>                                                        <UserId>jbm</UserId>                                                        <ReportStatus>COMPLETE</ReportStatus>                                                        <Description>The requested report archive is failed</Description>                                                        <CreatedDate>2017-11-06 14:22:21</CreatedDate>                                                        <DateRange>                                                            <StartDate>2017-05-01</StartDate>                                                            <EndDate>2017-10-31</EndDate>                                                        </DateRange>                                                    </BillingReport>                                                </BillingReportList>                                            </BillingReportsRetrievalResponse>"),
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/billingreports/a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2'], '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><BillingReportCreationResponse><ReportStatus>RECEIVED</ReportStatus><Description>The report archive is currently being constructed.</Description></BillingReportCreationResponse>'),
            new Response(200, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/billingreports/a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2/file'], '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><BillingReportRetrievalResponse><ReportStatus>COMPLETED</ReportStatus><Description>The report archive is constructed.</Description></BillingReportRetrievalResponse>'),
            new Response(200, ['Content-Type' => 'application/zip'], 'zipcontent'),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                    <ImportTnOrderResponse>                            <CustomerOrderId>SJM000001</CustomerOrderId>                            <OrderCreateDate>2018-01-20T02:59:54.000Z</OrderCreateDate>                            <AccountId>9900012</AccountId>                            <CreatedByUser>smckinnon</CreatedByUser>                            <OrderId>b05de7e6-0cab-4c83-81bb-9379cba8efd0</OrderId>                            <LastModifiedDate>2018-01-20T02:59:54.000Z</LastModifiedDate>                            <SiteId>202</SiteId>                            <SipPeerId>520565</SipPeerId>                            <Subscriber>                                <Name>ABC Inc.</Name>                                <ServiceAddress>                                    <HouseNumber>11235</HouseNumber>                                    <StreetName>Back</StreetName>                                    <City>Denver</City>                                    <StateCode>CO</StateCode>                                    <Zip>27541</Zip>                                    <County>Canyon</County>                                </ServiceAddress>                            </Subscriber>                            <LoaAuthorizingPerson>The Authguy</LoaAuthorizingPerson>                            <TelephoneNumbers>                                <TelephoneNumber>9199918388</TelephoneNumber>                                <TelephoneNumber>4158714245</TelephoneNumber>                                <TelephoneNumber>4352154439</TelephoneNumber>                                <TelephoneNumber>4352154466</TelephoneNumber>                            </TelephoneNumbers>                            <ProcessingStatus>PROCESSING</ProcessingStatus>                            <Errors/>                    </ImportTnOrderResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                <RemoveImportedTnOrderResponse>                        <CustomerOrderId>SJM000001</CustomerOrderId>                        <OrderCreateDate>2018-01-20T02:59:54.000Z</OrderCreateDate>                        <AccountId>9900012</AccountId>                        <CreatedByUser>smckinnon</CreatedByUser>                        <OrderId>b05de7e6-0cab-4c83-81bb-9379cba8efd0</OrderId>                        <LastModifiedDate>2018-01-20T02:59:54.000Z</LastModifiedDate>                        <TelephoneNumbers>                            <TelephoneNumber>9199918388</TelephoneNumber>                            <TelephoneNumber>4158714245</TelephoneNumber>                            <TelephoneNumber>4352154439</TelephoneNumber>                            <TelephoneNumber>4352154466</TelephoneNumber>                        </TelephoneNumbers>                        <ProcessingStatus>PROCESSING</ProcessingStatus>                        <Errors/>                </RemoveImportedTnOrderResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                <TNs>                    <TotalCount>2</TotalCount>                    <Links>                        <first>link</first>                    </Links>                    <TelephoneNumbers>                        <Count>2</Count>                        <TelephoneNumber>8043024183</TelephoneNumber>                        <TelephoneNumber>8042121778</TelephoneNumber>                    </TelephoneNumbers>                </TNs>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                <ImportTnCheckerResponse>                    <ImportTnCheckerPayload>                        <TelephoneNumbers>                            <TelephoneNumber>3032281000</TelephoneNumber>                        </TelephoneNumbers>                        <ImportTnErrors>                            <ImportTnError>                                <Code>19006</Code>                                <Description>Bandwidth numbers cannot be imported by this account at this time.</Description>                                <TelephoneNumbers>                                    <TelephoneNumber>4109235436</TelephoneNumber>                                    <TelephoneNumber>4104685864</TelephoneNumber>                                </TelephoneNumbers>                            </ImportTnError>                        </ImportTnErrors>                    </ImportTnCheckerPayload>                </ImportTnCheckerResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>				<CsrResponse>					<OrderId>4f5b3804-3d0d-49de-a133-d4b67ad6fa11</OrderId>					<Status>RECEIVED</Status>					<AccountNumber>123456789ABC</AccountNumber>					<AccountTelephoneNumber>9196191234</AccountTelephoneNumber>					<EndUserName>Bandwidth User</EndUserName>					<AuthorizingUserName>Auth Bandwidth User</AuthorizingUserName>					<CustomerCode>123</CustomerCode>					<EndUserPIN>123ABC</EndUserPIN>					<EndUserPassword>supersecretpassword123</EndUserPassword>					<AddressLine1>900 Main Campus Drive</AddressLine1>					<City>Raleigh</City>					<State>NC</State>					<ZIPCode>27606</ZIPCode>					<TypeOfService>business</TypeOfService>				</CsrResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>				<CsrResponse>					<OrderId>4f5b3804-3d0d-49de-a133-d4b67ad6fa11</OrderId>					<Status>RECEIVED</Status>					<AccountNumber>123456789ABC</AccountNumber>					<AccountTelephoneNumber>9196191234</AccountTelephoneNumber>					<EndUserName>Bandwidth User</EndUserName>					<AuthorizingUserName>Auth Bandwidth User</AuthorizingUserName>					<CustomerCode>123</CustomerCode>					<EndUserPIN>123ABC</EndUserPIN>					<EndUserPassword>supersecretpassword123</EndUserPassword>					<AddressLine1>900 Main Campus Drive</AddressLine1>					<City>Raleigh</City>					<State>NC</State>					<ZIPCode>27606</ZIPCode>					<TypeOfService>business</TypeOfService>				</CsrResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>				<CsrResponse>					<OrderId>4f5b3804-3d0d-49de-a133-d4b67ad6fa11</OrderId>					<Status>RECEIVED</Status>					<AccountNumber>123456789ABC</AccountNumber>					<AccountTelephoneNumber>9196191234</AccountTelephoneNumber>					<EndUserName>Bandwidth User</EndUserName>					<AuthorizingUserName>Auth Bandwidth User</AuthorizingUserName>					<CustomerCode>123</CustomerCode>					<EndUserPIN>123ABC</EndUserPIN>					<EndUserPassword>supersecretpassword123</EndUserPassword>					<AddressLine1>900 Main Campus Drive</AddressLine1>					<City>Raleigh</City>					<State>NC</State>					<ZIPCode>27606</ZIPCode>					<TypeOfService>business</TypeOfService>				</CsrResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                <Notes>                    <Note>                        <Id>87037</Id>                        <UserId>jbm</UserId>                        <Description>This is a test note</Description>                        <LastDateModifier>2014-11-16T04:01:10.000Z</LastDateModifier>                    </Note>                    <Note>                        <Id>87039</Id>                        <UserId>smckinnon</UserId>                        <Description>This is a second test note</Description>                        <LastDateModifier>2014-11-16T04:08:46.000Z</LastDateModifier>                    </Note>                </Notes> "),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                <fileListResponse>                <fileCount>2</fileCount>                <fileNames>803f3cc5-beae-469e-bd65-e9891ccdffb9-1092874634747.pdf</fileNames>                <resultCode>0</resultCode>                <resultMessage>LOA file list successfully returned</resultMessage>            </fileListResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                <fileListResponse>                <fileCount>2</fileCount>                <fileNames>803f3cc5-beae-469e-bd65-e9891ccdffb9-1092874634747.pdf</fileNames>                <fileNames>803f3cc5-beae-469e-bd65-e9891ccdffb9-1430814967669.pdf</fileNames>                <resultCode>0</resultCode>                <resultMessage>LOA file list successfully returned</resultMessage>            </fileListResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>                <FileMetaData>                <DocumentName>file </DocumentName>                <DocumentType>LOA</DocumentType>            </FileMetaData>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
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
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/lineOptionOrders", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbersSingle() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("KNIGHTDALE", $response[0]->City);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbers() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("KNIGHTDALE", $response[1]->City);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbers2() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("9194390154", $response[0]->TelephoneNumber[0]);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    /**
     * @expectedException \Iris\ResponseException
     * @expectedExceptionCode 4000
     */
    public function testAvailableNumbersError() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbersNoResults() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testTnReservation() {
        $resertation = self::$account->tnsreservations()->create(["ReservedTn" => "2512027430"]);

        self::$index++;
        $json = '{"ReservedTn":"2512027430","ReservationId":"2489"}';
		$this->assertEquals($json, json_encode($resertation->to_array()));
        $this->assertEquals("2489", $resertation->get_id());
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testGetTnReservation() {
        $resertation = self::$account->tnsreservations()->tnsreservation("0099ff73-da96-4303-8a0a-00ff316c07aa");

        $json = '{"ReservedTn":"2512027430","ReservationId":"0099ff73-da96-4303-8a0a-00ff316c07aa","ReservationExpires":"0","AccountId":"14"}';
		$this->assertEquals($json, json_encode($resertation->to_array()));
        $this->assertEquals("0099ff73-da96-4303-8a0a-00ff316c07aa", $resertation->get_id());
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation/0099ff73-da96-4303-8a0a-00ff316c07aa", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testDeleteTnReservation() {
        $resertation = self::$account->tnsreservations()->create(["ReservationId" => "0099ff73-da96-4303-8a0a-00ff316c07aa"], false);
        $resertation->delete();

        $this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation/0099ff73-da96-4303-8a0a-00ff316c07aa", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testlnpChecker() {
        $res = self::$account->lnpChecker(["4109255199", "9196190594"], "true");

        $json = '{"SupportedRateCenters":"","UnsupportedRateCenters":{"RateCenterGroup":[{"RateCenter":"BALTIMORE","City":"BALTIMORE","State":"MD","LATA":"238","TnList":{"Tn":["4109255199","4104685864"]}},{"RateCenter":"SPARKSGLNC","City":"SPARKS GLENCOE","State":"MD","LATA":"238","TnList":{"Tn":["4103431313","4103431561"]}}]},"PartnerSupportedRateCenters":{"RateCenterGroup":{"RateCenter":"FT COLLINS","City":"FORT COLLINS","State":"CO","LATA":"656","TnList":{"Tn":"4109235436"},"Tiers":{"Tier":"1"}}},"SupportedLosingCarriers":{"LosingCarrierTnList":{"LosingCarrierSPID":"9998","LosingCarrierName":"Test Losing Carrier L3","LosingCarrierIsWireless":"false","LosingCarrierAccountNumberRequired":"false","LosingCarrierMinimumPortingInterval":"5","TnList":{"Tn":["4109255199","4104685864","4103431313","4103431561"]}}}}';

		$this->assertEquals($json, json_encode($res->to_array()));

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/lnpchecker?fullCheck=true", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testNpaGet() {
        $npas = self::$account->availableNpaNxx(["state" => "CA"]);

        $json = '{"City":"COMPTON:COMPTON DA","Npa":"424","Nxx":"242","Quantity":"7","State":"CA"}';
        $this->assertEquals($json, json_encode($npas[0]->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNpaNxx?state=CA", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testInserviceGet() {
        $numbers = self::$account->inserviceNumbers(["page"=> "2", "type" => "x"]);

        $json = '{"TelephoneNumber":["8183386247","8183386249","8183386251","8183386252"]}';
        $this->assertEquals($json, json_encode($numbers->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/inserviceNumbers?page=2&type=x", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testInserviceTotals() {
        $numbers = self::$account->inserviceNumbers_totals();

        $this->assertEquals(4, $numbers);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/inserviceNumbers/totals", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testDiscNumbersGet() {
        $numbers = self::$account->disnumbers(["page"=> "2", "type" => "x"]);

        $json = '{"TelephoneNumber":["4158714245","4352154439"]}';
        $this->assertEquals($json, json_encode($numbers->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/discNumbers?page=2&type=x", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testDisknumbersTotals() {
        $numbers = self::$account->disnumbers_totals();

        $this->assertEquals(4, $numbers);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/discNumbers/totals", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testBdr() {
        $response = self::$account->bdrs(new \Iris\Bdr([
            "StartDate" => "xx-yy-zzzz",
            "EndDate" => "xx-yy-zzzz",
        ]));

        $this->assertEquals("Your BDR archive is currently being constructed", $response->Info);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/bdrs", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testBillingReports() {
        $billingReports = self::$account->billingreports()->getList();

        $this->assertEquals(2, count($billingReports));
        $this->assertEquals('5f8734f0-d7c3-445c-b1e2-cdbb620e4ff7', $billingReports[0]->Id);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/billingreports", self::$container[self::$index]['request']->getUri());
        unset($billingReports);

        self::$index++;
    }

    public function testBillingReportRequest() {
        $response = self::$account->billingreports()->request(array(
            'Type' => 'BDR',
            'DateRange' => array(
                'StartDate' => '2017-11-01',
                'EndDate' => '2017-11-02',
            )
        ));

        $this->assertEquals("a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2", $response->Id);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/billingreports", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testBillingReportReadyAndDownload() {
        $billingReport = self::$account->billingreports()
            ->billingreport('a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2');

        $this->assertEquals("a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2", $billingReport->Id);
        $this->assertEquals("COMPLETED", $billingReport->ReportStatus);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/billingreports/a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2", self::$container[self::$index]['request']->getUri());
        self::$index++;

        $zip = $billingReport->file();

        $this->assertEquals("zipcontent", $zip->getContents());
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/billingreports/a12b456c8-abcd-1a3b-a1b2-0a2b4c6d8e0f2/file", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testGetImportTnOrder() {
        $importTnOrderResponse = self::$account->getImportTnOrder("b05de7e6-0cab-4c83-81bb-9379cba8efd0");

        $this->assertEquals("b05de7e6-0cab-4c83-81bb-9379cba8efd0", $importTnOrderResponse->OrderId);
        self::$index++;
    }

    public function testRemoveImportedTnOrder() {
        $removeImportedTnOrderResponse = self::$account->getRemoveImportedTnOrder("b05de7e6-0cab-4c83-81bb-9379cba8efd0");

        $this->assertEquals("b05de7e6-0cab-4c83-81bb-9379cba8efd0", $removeImportedTnOrderResponse->OrderId);
        self::$index++;
    }

    public function testGetInserviceNumbers() {
        $tns = self::$account->getInserviceNumbers();

        $this->assertEquals("2", $tns->TotalCount);
        $this->assertEquals("link", $tns->Links->first);
        $this->assertEquals("2", $tns->TelephoneNumbers->Count);
        $this->assertEquals("8043024183", $tns->TelephoneNumbers->TelephoneNumber[0]);
        $this->assertEquals("8042121778", $tns->TelephoneNumbers->TelephoneNumber[1]);

        self::$index++;
    }

    public function testCheckTnsPortability() {
        $importTnCheckerResponse = self::$account->checkTnsPortability(array("5554443333"));

        $this->assertEquals("3032281000", $importTnCheckerResponse->ImportTnCheckerPayload->TelephoneNumbers->TelephoneNumber);
        $this->assertEquals("19006", $importTnCheckerResponse->ImportTnCheckerPayload->ImportTnErrors->ImportTnError->Code);

        
        self::$index++;
    }

    public function testCreateCsrOrder() {
		$request = new \Iris\Csr(array());
        $response = self::$account->createCsrOrder($request);
		
        $this->assertEquals("4f5b3804-3d0d-49de-a133-d4b67ad6fa11", $response->OrderId);
        self::$index++;
    }

    public function testGetCsrOrder() {
        $response = self::$account->getCsrOrder("id");
        $this->assertEquals("4f5b3804-3d0d-49de-a133-d4b67ad6fa11", $response->OrderId);
        self::$index++;
    }

    public function testReplaceCsrOrder() {
		$request = new \Iris\Csr(array());
        $response = self::$account->replaceCsrOrder("id", $request);
		
        $this->assertEquals("4f5b3804-3d0d-49de-a133-d4b67ad6fa11", $response->OrderId);
        self::$index++;
    }

    public function testGetCsrOrderNotes() {
        $response = self::$account->getCsrOrderNotes("order_id", "note");
        
        $this->assertEquals("This is a test note", $response->Note[0]->Description);
        $this->assertEquals("This is a second test note", $response->Note[1]->Description);
        self::$index++;
    }

    public function testGetImportTnOrderLoas() {
        //1 element in fileNames
        $response = self::$account->getImportTnOrderLoas("order_id");

        $this->assertEquals("803f3cc5-beae-469e-bd65-e9891ccdffb9-1092874634747.pdf", $response->fileNames);
        $this->assertEquals("LOA file list successfully returned", $response->resultMessage);
        self::$index++;

        //2 elements in fileNames
        $response = self::$account->getImportTnOrderLoas("order_id");

        $this->assertEquals("803f3cc5-beae-469e-bd65-e9891ccdffb9-1092874634747.pdf", $response->fileNames[0]);
        $this->assertEquals("803f3cc5-beae-469e-bd65-e9891ccdffb9-1430814967669.pdf", $response->fileNames[1]);
        $this->assertEquals("LOA file list successfully returned", $response->resultMessage);
        self::$index++;
    }

    public function testGetImportTnOrderLoaFileMetadata() {
        $response = self::$account->getImportTnOrderLoaFileMetadata("order_id", "file_id");

        $this->assertEquals("file", $response->DocumentName);
        $this->assertEquals("LOA", $response->DocumentType);
        self::$index++;
    }
}
