<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends CI_Model {
	
	
	function isUserValid($userName, $password) {
		$this -> db -> select("id");
		$this -> db ->where('email', $userName);
		$this -> db ->where('password', $password);
		$query = $this -> db -> get('users');
		if ($query->num_rows() > 0)
		{
			return true;
		}
		
		return false;
	}
}