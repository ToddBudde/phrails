<?php
/**
* 
*/
class ATest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function With_no_options()
	{
		$a = '<a href="/">home</a>';
		$o = new A('home', '/');
		$this->assertEquals($a, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function With_options()
	{
		$a = '<a href="/" class="foo" id="bar">home</a>';
		$o = new A('home', '/', 'class:foo,id:bar');
		$this->assertEquals($a, $o->__toString());
	}
}
