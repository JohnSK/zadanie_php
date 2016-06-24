<?php
	include_once "config.php";
	$db_table_mesta = "mesta";
	$db_table_urady = "urady";
	
	header('Content-Type: application/json; charset=utf-8');
	
	$q = $_GET["q"];
	$id = $_GET["id"];
	
	if ($q) {
		if (strlen($q) > 0) {
			$hint = "";
			
			$query = "SELECT * FROM $db_table_mesta 
					  WHERE nazov LIKE :q OR 
					  starosta LIKE :q OR 
					  email LIKE :q OR 
					  web LIKE :q";
			$query_params = array(
				"q" => '%' . $q . '%');
			
			try {
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			} catch (PDOException $ex) {
				die("DB error1:<br>" . $ex);
			}
			
			$k = 0;
			while(($row = $stmt->fetch()) && $k < 20) {
				$hint = $hint . "<a name='". $row["id"] ."' class='dropdown-item' onclick='showInfo(this.name)'>" . $row['nazov'] . "</a><br>";
				$k++;
			}
		}
		
		if ($hint == "") {
			$response["success"] = 0;
			$response["result"] = "Žiadne návrhy";
		} else {
			$response["success"] = 1;
			$response["result"] = $hint;
		}
	} else if ($id && is_numeric($id)) {
		$query = "SELECT * FROM $db_table_mesta 
				  WHERE id = :id";
		$query_params = array(
			"id" => $id);
					
		try {
			$stmt = $db->prepare($query);
			$result = $stmt->execute($query_params);
		} catch (PDOException $ex) {
			die("DB error2:<br>" . $ex);
		}
		
		if ($row = $stmt->fetch()) {
			$query = "SELECT * FROM $db_table_urady 
				  WHERE id = :id";
			$query_params = array(
				"id" => $row['urad']);
			
			try {
				$stmt = $db->prepare($query);
				$result = $stmt->execute($query_params);
			} catch (PDOException $ex) {
				die("DB error3:<br>" . $ex);
			}
			
			$urad = $stmt->fetch();
			
			$row["urad"] = $urad['ulica'] . ', ' . $urad['mesto'] . ', ' . $urad['psc'];
			
			$response["success"] = 1;
			$response["result"] = $row;
		} else {
			$response["success"] = 0;
			$response["result"] = "";
		}
	}
	
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>