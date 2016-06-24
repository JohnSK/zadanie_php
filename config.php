<?php
	// database config	
	$username = "username";
    $password = "password";
    $host = "localhost";
    $dbname = "zadPHP_db";
	
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
	
	try {
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
		$db ->exec("SET CHARACTER SET utf8");
    } catch(PDOException $ex) {
        die("Failed to connect to the database: " . $ex->getMessage());
    }
	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
	header('Content-Type: text/html; charset=utf-8');
	
	//google apikey
	$google_apikey = ""; //tu vlozte svoj google api kluc pre pracu s geocoding api
?>