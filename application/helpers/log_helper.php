<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Call this method if you encounter any missing argument
 */
if (!function_exists('debug')) {
	function debug($message) {
		log_message('debug', $message);
	}

}

if (!function_exists('error')) {
	function error($message) {
		log_message('error', $message);
	}

}

if (!function_exists('info')) {
	function info($message) {
		log_message('info', $message);
	}

}
?>