<?php
/**
 * Text helpers
 *
 * @package helpers
 * @subpackage form
 * @author Justin Palmer
 */
/**
 * Image submit button
 *
 * @return string
 * @author Justin Palmer
 **/
function image_submit_tag($src, $alt, $options='')
{
	return new InputImage($src, $alt, $options);
}
/**
 * Submit button
 *
 * @return string
 * @author Justin Palmer
 **/
function submit_tag($name, $value, $options='')
{
	return new InputSubmit($name, $value, $options);
}
/**
 * Form tag
 *
 * @return string
 * @author Justin Palmer
 **/
function form_tag($action, $options='')
{
	return new Form($action, $options);
}
/**
 * Return the end form tag
 *
 * @return string
 * @author Justin Palmer
 **/
function end_form_tag()
{
	$form = new Form('');
	return $form->end();
}