<?php
class OrderTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		$c = new Iris\GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL));
		$this->account = new Iris\Account(9500249, $c);
		$this->orders = $this->account->orders();
	}
	/**
     * @expectedException GuzzleHttp\Exception\ClientException
     */
	public function testOrderModel()
	{
        
    $order_data = Array( 
        'CustomerOrderId' => 1,
        'Name' => 'Test Order 22:28',
        'BackOrderRequested' => 'false',
        'PartialAllowed' => 'true',
        'SiteId' => 2391,
        'AreaCodeSearchAndOrderType' => Array(
                                              'AreaCode' => 435,
                                              'Quantity' => 1
                                              ),
        'PartialAllowed', true
                         );
        
        
    $order = $this->orders->create($order_data);

    $this->assertFalse(is_null($order->id), "Id should be present");

		$order->Name = "Test Name(unittest)";

		$this->assertEquals("Test Name(unittest)", $order->Name, "Name should be updated");

		$order->save();

		$id = $order->id;

		$order_reloaded = $this->orders->get_by_id($id);

		$this->assertEquals("Test Name(unittest)", $order_reloaded->Name, "Name should be updated for reloaded order");

	}

}
