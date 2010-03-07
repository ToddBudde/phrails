<?php
/**
* The record could not be found.
* @package exceptions
*/
class RecordNotFoundException extends Exception
{
	
	function __construct($query)
	{
		parent::__construct("The record could not be found. Query prepared: $query");
	}
}
