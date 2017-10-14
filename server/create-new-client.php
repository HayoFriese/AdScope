<?php
	include "db.php";

	if($_SERVER['REQUEST_METHOD'] == 'POST'){	
		$name = filter_has_var(INPUT_POST, 'client-name') ? $_POST['client-name']:null;
		$website = filter_has_var(INPUT_POST, 'website') ? $_POST['website']:null;
		$color = filter_has_var(INPUT_POST, 'color') ? $_POST['color']:null;
		$acronym = filter_has_var(INPUT_POST, 'acronym') ? $_POST['acronym']:null;

		$view = filter_has_var(INPUT_POST, 'view') ? $_POST['view']:0;
		$add = filter_has_var(INPUT_POST, 'add') ? $_POST['add']:0;
		$attach = filter_has_var(INPUT_POST, 'attach') ? $_POST['attach']:0;

  		$notes = "";

  		$yearCreated = date("Y");

  		$sql = "INSERT INTO client(client.name, client.acronym, client.website, client.hex, client.view, client.add, client.attach, client.notes, client.yearCreated) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt, "ssssdddss", $name, $acronym, $website, $color, $view, $add, $attach, $notes, $yearCreated) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt);

    	$id2 = mysqli_insert_id($conn);

    	$sqlNewNot = "SELECT idcontact FROM contact WHERE type = 'Colleague'";
    	$rNewNot = mysqli_query($conn, $sqlNewNot) or die(mysqli_error($conn));
    				
    	while($rowNN = mysqli_fetch_assoc($rNewNot)){
    		$datetime = date("Y-m-d H:i:s", time());
    		$to = $rowNN['idcontact'];
    		$message = "A new client has been added: ".$name;
    		$type = "client";
    					
    		$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
			$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtN, "sdss", $message, $to, $datetime, $type) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
			mysqli_stmt_close($stmtN);
		}

    	header("Location: client.php?id=$id2");
	} else {
		header("Location: ../projects.php");
	}
?>