<?php

/* Unit tests for accounts. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * accounts/9500249
 */

class AccountsTest extends PHPUnit_Framework_TestCase {

	public function testGet()
	{
		$account = new Iris\Account;
		$account->get();
		
		$this->assertTrue((bool) $account->balance);	
	}

}