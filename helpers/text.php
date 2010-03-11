<?php
/**
 * Text helpers
 *
 * @package helpers
 * @subpackage text
 * @author Justin Palmer
 */
/**
 * @see String::$escape()
 **/
function h($string)
{
	return String::escape($string);
}

/**
 * @see String::$decode()
 **/
function decode()
{
	return String::decode($string);
}

/**
 * @see Inflections::$pluralize()
 **/
function pluralize($string)
{
	return Inflections::pluralize($string);
}

/**
 * @see Inflections::$singularize()
 **/
function singularize($string)
{
	return Inflections::singularize($string);
}
/**
 * @see String::$truncate()
 **/
function truncate($string, $limit, $pad='...', $break='.')
{
	return String::truncate($string, $limit, $pad, $break);
}