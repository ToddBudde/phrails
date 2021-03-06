<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class UsMoneyRule extends PregRule
{
	/**
	 * constructor
	 *
	 * @return NameRule
	 * @author Justin Palmer
	 **/
	public function __construct($message='%s should be in the format: ##.##')
	{
		parent::__construct("/^([0-9]+.[0-9]{2})$/", $message);
	}
} // END class Rule