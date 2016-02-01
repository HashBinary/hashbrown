<?php
error_reporting(E_ALL);
$user = $_POST["user"];
$password = $_POST["pass"];
session_start();
echo "Session started \n";
if ($user == 'hash' && $password == 'ch@11~') {
	echo "login success";
	// session_register("hashuser");
	$_SESSION['login_user'] = $user;
	header("location: ../../home.php");
} else {
	$error = "Your Login Name or Password is invalid";
	header("location: ../../signin.html");
}
?>