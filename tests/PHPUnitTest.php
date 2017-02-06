<?php

/**
 * Created by PhpStorm.
 * User: louis.suo
 * Date: 10/25/16
 * Time: 3:12 PM
 */

class Atlas_Events_Listeners_TestBase extends PHPUnit_Framework_TestCase
{
	public function test() {

		$this->assertTrue(1 > 0);
		$this->assertFalse(1 == 0);
	}
}