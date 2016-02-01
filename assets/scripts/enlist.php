<?php
$user = $_POST['user'];
$pass = $_POST['pass'];

try {

	$dbh = new PDO("mysql:host=162.251.83.139;dbname=cashew", "cashew", "kaju@007");
	$sql = "SELECT id, user_name, contact, addr1, addr2, pin, city, email, DATE_FORMAT(order_date, '%b %d %Y') AS order_date, DATE_FORMAT(order_date, '%h:%i %p') AS order_time, CASE WHEN DATEDIFF(order_date, now()) > 0 THEN 1 ELSE 0 END AS isnew, order_status, quantity FROM registrations";
	$statement = $dbh->prepare($sql);
	$statement->execute();
	$results=$statement->fetchAll(PDO::FETCH_ASSOC);
	$json=json_encode($results);
	echo $json;
} catch(PDOException $e) {
	echo $e -> getMessage();
}
?>