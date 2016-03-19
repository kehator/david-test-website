<?php
require_once('config.php');

try{
	$DB_PDO = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$DB_PDO -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $error) {
	echo $error->getMessage();
	echo "<br>Please set the config file: './includes/config.php'";
	exit();
}
?>