<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class ReportsTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $reports;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            //GET report
            new Response(200, [], '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><ReportsResponse><Reports><Report><Name>Sample Report 1</Name><Id>100020</Id><Description>Sample Report 1 Description</Description></Report><Report><Name>Sample Report 2</Name><Id>100021</Id><Description>Sample Report 2 Description</Description></Report></Reports></ReportsResponse>'),
            //GET report by id
            new Response(200, [], '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><ReportsResponse><Report><Id>123</Id><Name>Sample Report 1</Name><Parameters><Parameter><Name>Report Parameter 1</Name><Type>Enum</Type><Required>false</Required><ValueFilter>Value1</ValueFilter><Values><Value><InternalName>Value1</InternalName><DisplayName>Display Value1</DisplayName></Value></Values><Description>Report Parameter 1 Description</Description><MultiSelectAllowed>true</MultiSelectAllowed><HelpInformation>Report Parameter 1 Help Text</HelpInformation></Parameter><Parameter><Name>Report Parameter 2</Name></Parameter></Parameters></Report></ReportsResponse>'),
            //GET instances for report
            new Response(200, [], '<ReportInstancesResponse><Instances><Instance><Id>100090</Id><ReportId>100020</ReportId><ReportName>Sample Report</ReportName><OutputFormat>pdf</OutputFormat><RequestedByUserName>jbm</RequestedByUserName><RequestedAt>2015-05-18 14:03:04</RequestedAt><Parameters><Parameter><Name>AccountId</Name><Value>2</Value></Parameter></Parameters><Status>Expired</Status></Instance><Instance><Id>100090</Id><ReportId>100020</ReportId><ReportName>Sample Report</ReportName><OutputFormat>pdf</OutputFormat><RequestedByUserName>jbm</RequestedByUserName><RequestedAt>2015-05-18 14:03:04</RequestedAt><Parameters><Parameter><Name>AccountId</Name><Value>1</Value></Parameter></Parameters><Status>Expired</Status></Instance></Instances></ReportInstancesResponse>'),

            //POST update report instance
            new Response(200, [], ''),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$reports = $account->reports();
    }

    public function testReportsGet() {
        $report = self::$reports->getList()[0];
        $this->assertEquals("Sample Report 1 Description", $report->Description);
        $this->assertEquals("Sample Report 1", $report->Name);
        $this->assertEquals("100020", $report->Id);
    }

    public function testReportsGetIdAndInstances() {
        $report = self::$reports->get_by_id("123");
        $this->assertEquals("123", $report->Id);
        $this->assertEquals("Sample Report 1", $report->Name);
        $this->assertEquals("Report Parameter 1", $report->Parameters->Parameter[0]->Name);

        $instance = $report->instances()[0];
        $this->assertEquals("100090", $instance->Id);
        $this->assertEquals("Sample Report", $instance->ReportName);
    }
}
