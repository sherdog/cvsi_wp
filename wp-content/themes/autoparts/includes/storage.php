<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage AUTOPARTS
 * @since AUTOPARTS 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('autoparts_storage_get')) {
	function autoparts_storage_get($var_name, $default='') {
		global $AUTOPARTS_STORAGE;
		return isset($AUTOPARTS_STORAGE[$var_name]) ? $AUTOPARTS_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('autoparts_storage_set')) {
	function autoparts_storage_set($var_name, $value) {
		global $AUTOPARTS_STORAGE;
		$AUTOPARTS_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('autoparts_storage_empty')) {
	function autoparts_storage_empty($var_name, $key='', $key2='') {
		global $AUTOPARTS_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($AUTOPARTS_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($AUTOPARTS_STORAGE[$var_name][$key]);
		else
			return empty($AUTOPARTS_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('autoparts_storage_isset')) {
	function autoparts_storage_isset($var_name, $key='', $key2='') {
		global $AUTOPARTS_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($AUTOPARTS_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($AUTOPARTS_STORAGE[$var_name][$key]);
		else
			return isset($AUTOPARTS_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('autoparts_storage_inc')) {
	function autoparts_storage_inc($var_name, $value=1) {
		global $AUTOPARTS_STORAGE;
		if (empty($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = 0;
		$AUTOPARTS_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('autoparts_storage_concat')) {
	function autoparts_storage_concat($var_name, $value) {
		global $AUTOPARTS_STORAGE;
		if (empty($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = '';
		$AUTOPARTS_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('autoparts_storage_get_array')) {
	function autoparts_storage_get_array($var_name, $key, $key2='', $default='') {
		global $AUTOPARTS_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($AUTOPARTS_STORAGE[$var_name][$key]) ? $AUTOPARTS_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($AUTOPARTS_STORAGE[$var_name][$key][$key2]) ? $AUTOPARTS_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('autoparts_storage_set_array')) {
	function autoparts_storage_set_array($var_name, $key, $value) {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if ($key==='')
			$AUTOPARTS_STORAGE[$var_name][] = $value;
		else
			$AUTOPARTS_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('autoparts_storage_set_array2')) {
	function autoparts_storage_set_array2($var_name, $key, $key2, $value) {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if (!isset($AUTOPARTS_STORAGE[$var_name][$key])) $AUTOPARTS_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$AUTOPARTS_STORAGE[$var_name][$key][] = $value;
		else
			$AUTOPARTS_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('autoparts_storage_merge_array')) {
	function autoparts_storage_merge_array($var_name, $key, $value) {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if ($key==='')
			$AUTOPARTS_STORAGE[$var_name] = array_merge($AUTOPARTS_STORAGE[$var_name], $value);
		else
			$AUTOPARTS_STORAGE[$var_name][$key] = array_merge($AUTOPARTS_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('autoparts_storage_set_array_after')) {
	function autoparts_storage_set_array_after($var_name, $after, $key, $value='') {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if (is_array($key))
			autoparts_array_insert_after($AUTOPARTS_STORAGE[$var_name], $after, $key);
		else
			autoparts_array_insert_after($AUTOPARTS_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('autoparts_storage_set_array_before')) {
	function autoparts_storage_set_array_before($var_name, $before, $key, $value='') {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if (is_array($key))
			autoparts_array_insert_before($AUTOPARTS_STORAGE[$var_name], $before, $key);
		else
			autoparts_array_insert_before($AUTOPARTS_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('autoparts_storage_push_array')) {
	function autoparts_storage_push_array($var_name, $key, $value) {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($AUTOPARTS_STORAGE[$var_name], $value);
		else {
			if (!isset($AUTOPARTS_STORAGE[$var_name][$key])) $AUTOPARTS_STORAGE[$var_name][$key] = array();
			array_push($AUTOPARTS_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('autoparts_storage_pop_array')) {
	function autoparts_storage_pop_array($var_name, $key='', $defa='') {
		global $AUTOPARTS_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($AUTOPARTS_STORAGE[$var_name]) && is_array($AUTOPARTS_STORAGE[$var_name]) && count($AUTOPARTS_STORAGE[$var_name]) > 0) 
				$rez = array_pop($AUTOPARTS_STORAGE[$var_name]);
		} else {
			if (isset($AUTOPARTS_STORAGE[$var_name][$key]) && is_array($AUTOPARTS_STORAGE[$var_name][$key]) && count($AUTOPARTS_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($AUTOPARTS_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('autoparts_storage_inc_array')) {
	function autoparts_storage_inc_array($var_name, $key, $value=1) {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if (empty($AUTOPARTS_STORAGE[$var_name][$key])) $AUTOPARTS_STORAGE[$var_name][$key] = 0;
		$AUTOPARTS_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('autoparts_storage_concat_array')) {
	function autoparts_storage_concat_array($var_name, $key, $value) {
		global $AUTOPARTS_STORAGE;
		if (!isset($AUTOPARTS_STORAGE[$var_name])) $AUTOPARTS_STORAGE[$var_name] = array();
		if (empty($AUTOPARTS_STORAGE[$var_name][$key])) $AUTOPARTS_STORAGE[$var_name][$key] = '';
		$AUTOPARTS_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('autoparts_storage_call_obj_method')) {
	function autoparts_storage_call_obj_method($var_name, $method, $param=null) {
		global $AUTOPARTS_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($AUTOPARTS_STORAGE[$var_name]) ? $AUTOPARTS_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($AUTOPARTS_STORAGE[$var_name]) ? $AUTOPARTS_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('autoparts_storage_get_obj_property')) {
	function autoparts_storage_get_obj_property($var_name, $prop, $default='') {
		global $AUTOPARTS_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($AUTOPARTS_STORAGE[$var_name]->$prop) ? $AUTOPARTS_STORAGE[$var_name]->$prop : $default;
	}
}
?>