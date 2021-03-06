<?php
/**
 * Parses the options string that are sent in.
 *
 * @package util
 * @author Justin Palmer
 */
class OptionsParser
{
	private static $options;
	/**
	 * Parse and return a string in html attribute format.
	 *
	 * @param string $options 
	 * @return string
	 * @author Justin Palmer
	 */
	public static function toHtmlProperties($options)
	{
		return self::parse($options);
	}
	/**
	 * Parse and return an array of key/value pairs.
	 *
	 * @param string $options 
	 * @return array
	 * @author Justin Palmer
	 */
	public static function toArray($options)
	{
		return self::parse($options, true);
	}
	/**
	 * @nodoc
	 */
	private function parse($options, $array=false)
	{	
		//var_dump($options);
		//Initialize return var
		($array) ? $ret = array() : $ret = '';
		//Do some processing if we actually have some options.
		if($options !== null && $options != ''){
			//All options are comma seperated, make an array out of them.
			$options = explode(',', $options);
			foreach($options as $value){
				//Get the key/value an array
				$option = explode(':', $value);
				$key = trim($option[0]);
				$value = trim($option[1]);
				//Set the option into the appropriate return type.
				($array) ? $ret[$key] = $value
						 : $ret .= ' ' . $key . '="' . $value . '"';
			}
		}
		return $ret;
	}
	
	/**
	 * Turn a string from an array
	 *
	 * Returns a string in format 'key:value,key:value'
	 * 
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function toStringFromArray(array $array)
	{
		$string = '';
		foreach($array as $key => $value)
			$string .= "$key:$value,";
		$string = rtrim($string, ',');
		return $string;
	}
	/**
	 * Search for an option and return it if it exists
	 *	
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public static function find($key, $options)
	{
		$array = self::toArray($options);
		return (array_key_exists($key, $array)) ? $array[$key] 
												: false;
	}
	/**
	 * Destroy an option
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function destroy($key, $options)
	{
		$array = self::toArray($options);
		if(array_key_exists($key, $array))
			unset($array[$key]);
		self::$options = self::toStringFromArray($array);
		return self::$options;
	}
	
	/**
	 * Search and destroy the key and return it if it exists
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public static function findAndDestroy($key, $options)
	{
		$value = self::find($key, $options);
		self::destroy($key, $options);
		return $value;
	}
	/**
	 * Get the options that are currently available.
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function getOptions()
	{
		return self::$options;
	}
}
