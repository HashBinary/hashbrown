<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderModel extends CI_Model {
	
	function getDashStats() {
		$this -> db -> select("SUM(delivery_cost) AS delivery_cost, SUM(cost) AS income, SUM(quantity) AS boxes_sold
		, SUM(plain) AS plain, SUM(salted) AS salted, SUM(masala) AS masala, SUM(chilli) AS chilli, SUM(strawberry) AS strawberry, SUM(garlic) AS garlic");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result[0];
	}

	function getCount() {
		$this -> db -> select("COUNT(*) AS count");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result;
	}
	
	function getSalesGraphData() {
		$this -> db -> select("SUM(quantity) AS quantity, DATE_FORMAT(order_date, '%Y-%m-%d') AS norder_date");
		$this->db->group_by("norder_date"); 
		$this->db->order_by("norder_date", "desc"); 
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result;
	}
	
	function getDeliveryCost() {
		$this -> db -> select("SUM(delivery_cost) AS cost");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result[0]->cost;
	}
	
	function getIncome() {
		$this -> db -> select("SUM(cost) AS cost");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result[0]->cost;
	}
	
	function getBoxesSold() {
		$this -> db -> select("SUM(quantity) AS cost");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result[0]->cost;
	}

	/*
	 * Returns all records from registration table
	 * */
	function getAll() {
		$this -> db -> select("id, user_name, contact, addr1, addr2, pin, city, email, DATE_FORMAT(order_date, '%b %d %Y') AS order_date, 
		DATE_FORMAT(order_date, '%h:%i %p') AS order_time, CASE WHEN DATEDIFF(order_date, now()) > 0 THEN 1 ELSE 0 END AS isnew, 
		CASE order_status WHEN 0 THEN 'Pending' WHEN 1 THEN 'Confirmed' WHEN 2 THEN 'Rejected' WHEN 3 THEN 'Completed' ELSE 'Undefined' END AS order_status, quantity");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result;
	}

	/*
	 * Returns all records from registration table
	 * */
	function confirmOrder() {
		$sql = "SELECT  FROM ";
		$this -> db -> select("id, user_name, contact, addr1, addr2, pin, city, email, DATE_FORMAT(order_date, '%b %d %Y') AS order_date, DATE_FORMAT(order_date, '%h:%i %p') AS order_time, CASE WHEN DATEDIFF(order_date, now()) > 0 THEN 1 ELSE 0 END AS isnew, order_status, quantity");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result;
	}

	/*
	 * Returns all records from registration table
	 * */
	function rejectOrder() {
		$sql = "SELECT  FROM ";
		$this -> db -> select("id, user_name, contact, addr1, addr2, pin, city, email, DATE_FORMAT(order_date, '%b %d %Y') AS order_date, DATE_FORMAT(order_date, '%h:%i %p') AS order_time, CASE WHEN DATEDIFF(order_date, now()) > 0 THEN 1 ELSE 0 END AS isnew, order_status, quantity");
		$query = $this -> db -> get('registrations');
		$result = $query -> result();
		return $result;
	}

	/*
	 * marks an order as complete
	 * */
	function completeOrder($orderId, $pack, $deli, $cost, $delicost, $plain, $salted, $masala, $garlic, $chilli, $strawberry) {
		$data = array('box_type' => $pack, 'delivery_type'=>$deli, 'cost'=>$cost, 'delivery_cost'=>$delicost, 'order_status'=>'3', 
		'plain'=>$plain, 'salted'=>$salted, 'masala'=>$masala, 'garlic'=>$garlic, 'chilli'=>$chilli, 'strawberry'=>$strawberry);
		$this -> db -> where('id', $orderId);
		$result = $this -> db -> update('registrations', $data);
		if (!$result) {
			return false;
		}
		return true;
	}

}
