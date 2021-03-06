<?php
/**
 * @package helpers 
 * @subpackage view
 */

/**
 * Render a partial or file to the current view.
 *
 * @example examples/render.php
 * @param string
 * @param array
 * @return string
 * @author Justin Palmer
 */
function render(){
	$args = func_get_args();
	return TemplatePartial::render($args);
}

/**
 * Create a link tag for the stylesheet
 *
 * @example examples/stylesheet-link-tag.php
 * @param string $file
 * @param string $options=''
 * @return string
 * @author Justin Palmer
 **/
function stylesheet_link_tag($file, $options='')
{
	return new LinkCss($file, $options);
}
/**
 * Create an img tag.
 *
 * @return void
 * @author Justin Palmer
 **/
function image_tag($source, $options='')
{
	return new Img($source, $options);
}
/**
 * Load a javascript library from google.
 *
 * @param string $library
 * @param string $version
 * @param boolean $jsapi - Include the google jsapi library?
 * @return string
 * @author Justin Palmer
 **/
function google_javascript_include_tag($library='jquery', $version='1.4.2', $jsapi=true)
{
	$js = '';
	if($jsapi == true)
		$js .= '<script src="https://www.google.com/jsapi"></script>' . "\n";
	$js .= '<script language="JavaScript" type="text/javascript">google.load("' . $library . '", "' . $version . '");</script>' . "\n";
	return $js;
}
/**
 * Load a javascript file from the local.
 *
 * @param string $source
 * @param string $options
 * @return string
 * @author Justin Palmer
 **/
function javascript_include_tag($source, $options='')
{
	return new Script($source . '.js', $options);
}
/**
 * Set the content for a certain var from the view.
 * 
 * Use of this method will over right any vars set in the controller 
 * that are used in the layout. 
 *
 * @param string $key 
 * @param string $value 
 * @return void
 * @author Justin Palmer
 */
function content_for($key, $value){
	(isset(Template::$ContentFor->$key)) 
					? Template::$ContentFor->$key = Template::$ContentFor->$key . $value
					: Template::$ContentFor->$key = $value;
}
/**
 * Get the value of an existing content_for key.
 *
 * @param string $key
 * @return string $key
 * @author Justin Palmer
 **/
function get_content_for($key){
	return (isset(Template::$ContentFor->$key)) ? Template::$ContentFor->$key : null ;
}
/**
 * Is there content for the specified key?
 *
 * @param string $key
 * @return boolean
 * @author Justin Palmer
 **/
function has_content_for($key)
{
	return isset(Template::$ContentFor->$key);
}

/**
 * Flash the message on the screen.
 * 
 * You can create your own Flash by extending the <code>Flash</code> class 
 * and implementing the <code>display()</code> method.
 *
 * @return string
 * @author Justin Palmer
 **/
function flash_it($flash)
{
	if($flash instanceof Flash){
		return $flash->display();
	}else{
		if($flash != '')
			$flash = '<div class="flash">' . $flash . '</div>';
	}
	return $flash;
}

/**
 * Cycle through to given strings
 *
 * @return string
 * @author Justin Palmer
 **/
function cycle($one='one', $two='')
{
	static $count;
	$count ++;
	return ($count % 2) ? $one : $two;
}