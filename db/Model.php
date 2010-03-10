<?php
/**
 * The base model.
 *
 * 
 * @package db
 * @author Justin Palmer
 **/
abstract class Model
{
	/**
	 * The primary key.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	protected $primary_key = 'id';
	/**
	 * The models table name.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	protected $table_name;
	/**
	 * The alias for the model.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	protected $alias;
	/**
	 * The Adapter for the project
	 *
	 * @author Justin Palmer
	 * @var Adapter
	 */
	protected static $db;
	/**
	 * The Schema for the model.
	 *
	 * @author Justin Palmer
	 * @var Schema
	 */
	protected $schema;
	/**
	 * The properties for the model set by the user.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */	
	protected $props;
	/**
	 * The valid columns for the model from the db.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	protected $columns;
	/**
	 * The current errors, if any during the validation process.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	protected $errors;
	
	/**
	 * Constructor
	 *
	 * @param array $array
	 * @return Model
	 * @author Justin Palmer
	 **/
	public function __construct($array=array())
	{
		$Config = Registry::get('pr-db-config');
		$Adapter = ucwords($Config->driver);
		Adapter::checkDriver();
		//Generate the table name if it is not set.
		if($this->table_name === null)
			$this->table_name = Inflections::tableize(get_class($this));
		$this->schema = new Schema($this);
		$this->alias = Inflections::singularize($this->table_name);
		$this->errors = new Hash();
		//Store the db adapter.
		self::$db = new $Adapter($this);
		//Hold the columns from the db to make sure properties, rules and relationships set actually exist.
		$this->columns = $this->prepareShowColumns($this->showColumns());
		$this->setProperties($array);
		$this->init();
	}
	/**
	 * Export the current model object to array.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function export()
	{
		return $this->props->export();
	}
	/**
	 * Remove a property.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function removeProperty($key)
	{
		$this->props->remove($key);
	}
	/**
	 * Overload the save method in the db so that we can run validation
	 *
	 * @todo add error messages to some where.
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function save()
	{
		$boolean = $this->validate();
		if($boolean){
			return self::$db->saveNow();
		}
	}
	/**
	 * Validate the model
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function validate()
	{
		//print '<pre>';
		$boolean = true;
		$errors = array();
		$last_prop_name = '';
		//Run validation before calling save.
		$props = $this->props->export();
		$rules = $this->schema->rules();
		//var_dump($props);
		//Loop through the set properties.
		foreach($props as $name => $value){
			if(empty($errors))
				$last_prop_name = $name;
			if(!empty($errors) && $last_prop_name != $name){
				$this->errors->set($this->alias() . '[' . $last_prop_name . ']', $errors);
				$last_prop_name = $name;
				$errors = array();
			}
			//If there are rules for the property let's go through them.
			if($rules->isKey($name)){
				//print $name . ':' . $value . '<br/><br/>';
				//Get the rules.
				$prop_rules = $rules->get($name);
				//var_dump($prop_rules);
				foreach($prop_rules as $key => $rule){
					$rule->value = $value;
					if(!$rule->run()){
						if($boolean)
							$boolean = false;
						//Add the error message to some sort of array. So that we can add it to a flash.
						$errors[] = $rule->message;
					}
				}
			}
		}
		if(!empty($errors)){
			$this->errors->set($this->alias() . '[' . $name . ']', $errors);
		}
		if(!$this->errors()->isEmpty())
			$boolean = false;
		return $boolean;
	}
	/**
	 * Does the model actually have the property specified.
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function hasProperty($column)
	{	
		if(!$this->columns->isKey($column))
			throw new NoColumnInTableException($column, $this->table_name());
		return true;
	}
	/**
	 * Does this model have validation errors
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function hasErrors()
	{
		return (empty($this->errors)) ? false : true;
	}
	/**
	 * Turn showColumns call into a hash.
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	public function prepareShowColumns(ResultSet $ResultSet)
	{
		$array = array();
		foreach($ResultSet as $value){
			$array[$value->Field] = $value;
		}
		return new Hash($array);
	}
	/**
	 * Call the method on the db if it does not exist here
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function __call($method, $arguments=array())
	{
		//print('we tried to call' . $method);
		$object = null;
		if(method_exists(self::$db, $method)){
			$object = self::$db;
		}else if(method_exists(self::$db->builder, $method)){
			$object = self::$db->builder;
		}else{
			throw new Exception('We do not have that method. Tried to call: ' . $method);
		}
		return call_user_func_array(array($object, $method), $arguments);
	}

	/**
	 * __get data
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function __get($key)
	{
		return $this->props->get($key);
	}

	/**
	 * __set vars
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __set($key, $value)
	{
		if(!$this->columns()->isKey($key))
			throw new NoColumnInTableException($key, $this->table_name());
		$this->props->set($key, $value);
	}
	/**
	 * Close the connection
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __destruct()
	{
		self::$db = null;
	}
	/**
	 * Set properties from an array(), this will throw an exception if a property in the array is invalid;
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function setProperties($array)
	{	
		$this->props = new Hash();
		if(is_array($array)){
			foreach($array as $key => $value)
				$this->$key = $value;
		}
	}
	/**
	 * Get the error array
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function errors()
	{
		return $this->errors;
	}
	/**
	 * Get the valid columns
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	public function columns()
	{
		return $this->columns;
	}
	/**
	 * Get the current properties set
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	public function props()
	{
		return $this->props;
	}
	/**
	 * Get the schema object.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	public function schema()
	{
		return $this->schema;
	}
	/**
	 * Get the current db connection
	 *
	 * @return Adapter
	 * @author Justin Palmer
	 **/
	public static function db()
	{
		return self::$db;
	}
	/**
	 * Get the alias
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function alias()
	{
		return $this->alias;
	}
	/**
	 * Get the table name.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function table_name()
	{
		return $this->table_name;
	}
	/**
	 * Get the primary_key value
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function primary_key()
	{
		return $this->primary_key;
	}
	/**
	 * init
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	abstract public function init();
} // END class Model