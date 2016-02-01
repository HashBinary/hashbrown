<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Call this method if you encounter any missing argument
 */
if (!function_exists('createSuccess')) {
	function createSuccess($message) {
		$array = array("code" => "800", "message"=>$message);
		return json_encode($array);
	}

}

if (!function_exists('createFail')) {
	function createFail($message) {
		$array = array("code" => "801", "message"=>$message);
		return json_encode($array);
	}

}

if (!function_exists('throwError')) {
	function throwError($code, $message) {
		$array = array("code" => $code, "error"=>$message);
		return json_encode($array);
	}

}

if (!function_exists('noRecords')) {
	function noRecords() {
		$array = array("message" => "no records");
		return json_encode($array);
	}

}

?>