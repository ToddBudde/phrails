<?php
/**
 * Mysql adapter
 *
 * @package db
 * @author Justin Palmer
 */				
class Mysql extends Adapter
{
	
	/**
	 * Constructor
	 *
	 * @return Mysql
	 * @author Justin Palmer
	 **/
	public function __construct($model)
	{
		self::getConfig();
		parent::__construct($model, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::$Config->encoding));
	}
	
	/**
	 * Show columns
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function showColumns()
	{
		$table_name = $this->model->table_name();
		return parent::showColumns("SHOW COLUMNS FROM `$table_name`");
	}
}
