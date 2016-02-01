<?php
$user = $_POST['user'];
$pass = $_POST['pass'];
$data = $_POST['data'];
$value = $_POST['value'];
$id = $_POST['id'];
$action = $_POST['action'];

try {
	echo "ACTION : $action \n";
	$dbh = new PDO("mysql:host=162.251.83.139;dbname=cashew", "cashew", "kaju@007");
	if ($action == 'confirm') {
		$status = 1;
		$sql = "UPDATE registrations SET order_status = :status WHERE id=$id";
	} else if ($action == 'reject') {
		$status = 2;
		$sql = "UPDATE registrations SET order_status = :status WHERE id=$id";
	} else if ($action == 'update') {

		$sql = "UPDATE registrations SET $data = '$value' WHERE id=$id";
		$statement = $dbh -> prepare($sql);
		$statement -> execute();

		//$results=$statement->fetchAll(PDO::FETCH_ASSOC);
		//$json=json_encode($results);
	}
	$stmt = $dbh -> prepare($sql);
	$stmt -> bindParam(':status', $status, PDO::PARAM_INT);
	//echo "$sql";
	//$statement = $dbh -> prepare($sql);
	$stmt -> execute();
} catch(PDOException $e) {
	//echo $e -> getMessage();
	echo 0;
}
?>