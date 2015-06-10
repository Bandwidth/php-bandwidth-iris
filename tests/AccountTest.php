<?php

/* Unit tests for accounts. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * accounts/14
 */
require_once("lib/Client.php");

class AccountsTest extends PHPUnit_Framework_TestCase {

	public function testGet()
	{
		$account = new Iris\Account(14, new TestClient('', ''));
		$response = $account->get();
		$this->assertEquals("CWI Hosting", $response->Account->CompanyName);
	}

}
