<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {
	
	
	function getCount() {
		$this -> db -> select("COUNT(*) AS count");
		$query = $this -> db -> get('users');
		$result = $query -> result();
		return $result;
	}
	
	
	/*
	 * Returns all records from registration table
	 * */
	function getAll() {
		$this -> db -> select("*");
		$query = $this -> db -> get('vi_users');
		$result = $query -> result();
		return $result;
	}

	function create()
	{
		
	}

	
}
