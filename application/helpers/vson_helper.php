<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Web Service from View Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/array_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('generateFromView')) {
	/**
	 * fromView
	 *
	 * Generated json based on view name provided
	 * If the element is empty it returns NULL (or whatever you specify as the default value.)
	 *
	 * @param view view name from which JSON will be generated
	 * @param condition array of where condition For instance : $array = array('name !=' => $name, 'id <' => $id, 'date >' => $date);
	 */
	function generateFromView($view, $condition = NULL, $index = 0, $limit = 20, $order = null) {
		$CI = &get_instance();
		$CI -> load -> database();
		// echo "condition";
		// print_r($condition);
		if ($condition) {
			$CI -> db -> where($condition);
		}

		$CI -> db -> where_in('status', array(1, 2));

		if ($order) {
			$CI -> db -> order_by($order, 'DESC');
		}

		// if(isset($index) && $index > 0)
		// {
		$CI -> db -> limit($limit, $index);
		// }

		$justArray = array();

		$query = $CI -> db -> get($view);
		$result = $query -> result();

		// for ($i=0; $i < sizeof($result); $i++) {
		// array_push($justArray, $result[$i]);
		// }
		//echo json_encode(array_values($result));
		return array_values($result);
	}

}

if (!function_exists('generateFromQuery')) {
	/**
	 * fromView
	 *
	 * Generated json based on view name provided
	 * If the element is empty it returns NULL (or whatever you specify as the default value.)
	 *
	 * @param view view name from which JSON will be generated
	 * @param condition array of where condition For instance : $array = array('name !=' => $name, 'id <' => $id, 'date >' => $date);
	 */
	function generateFromQuery($query) {
		$CI = &get_instance();
		$CI -> load -> database();

		$query = $CI -> db -> query($query);
		$result = $query -> result();

		if ($query -> num_rows() > 0) {
			return $result[0];
		} else {
			return null;
		}
	}

}
