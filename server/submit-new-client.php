<?php
	ini_set("session.save_path", "../../sessionData");
	session_start();
	include "db.php";
	include "../functions.php";

	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    	echo pageIni("Access Denied");
    	echo "nope";
  	} else {
		echo pageIni2("New Client - Adscope");
?>
	<body id="back-end-pages">
<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$datetime = date("Y-m-d H:i:s", time());
			
			$id = filter_has_var(INPUT_POST, 'client-id') ? $_POST['client-id']: null;
			$name = filter_has_var(INPUT_POST, 'client-name') ? $_POST['client-name']: null;
			$website = filter_has_var(INPUT_POST, 'website') ? $_POST['website']: null;
			$color = filter_has_var(INPUT_POST, 'color') ? $_POST['color']: null;
			if(stristr($color, '#') === FALSE) {
    			$color2 = "#".$color;
  			} else {
  				$color2 = $color;
  			}
			$notes = filter_has_var(INPUT_POST, 'notes') ? $_POST['notes']: null;	
			
			$view = filter_has_var(INPUT_POST, 'view') ? $_POST['view']: 0;
			$add = filter_has_var(INPUT_POST, 'add') ? $_POST['add']: 0;
			$attach = filter_has_var(INPUT_POST, 'attach') ? $_POST['attach']: 0;
			
			$relatedcontactarr = filter_has_var(INPUT_POST, 'relatedcontact') ? $_POST['relatedcontact']: 0;

			
			if($id === null || !$id || $id === ""){

				$sql = "INSERT INTO client(name, website, hex, view, add, attach, notes) 
				VALUES(?, ?, ?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    			mysqli_stmt_bind_param($stmt, "sssddds", $name, $website, $color2, $view, $add, $attach, $notes) or die(mysqli_error($conn));
    			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    			mysqli_stmt_close($stmt);

    			$id2 = mysqli_insert_id($conn);

    			$sqlNewNot = "SELECT idcontact FROM contact WHERE type = 'Colleague'";
    			$rNewNot = mysqli_query($conn, $sqlNewNot) or die(mysqli_error($conn));
    				
    			while($rowNN = mysqli_fetch_assoc($rNewNot)){

    				$to = $rowNN['idcontact'];
    				$message = "A new client has been added: ".$name;
    				$type = "client";
    					
    				$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
					$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmtN, "sdss", $message, $to, $datetime, $type) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
					mysqli_stmt_close($stmtN);
				}

    			if(isset($relatedcontactarr)){
    				$relatedcontact = explode(",", $relatedcontactarr);
    				foreach ($relatedcontact as $value) {
	    				$sqltest = "SELECT idrelatedcontact FROM relatedcontact WHERE relatedcontact.contact = '$value' AND relatedcontact.client = '$id'";
	    				$rtest = mysqli_query($conn, $sqltest) or die(mysqli_error($conn));
	    				
	    				if(mysqli_num_rows($rtest) === 0){
	    					$project = "";
	    					$sqlcontact = "INSERT INTO relatedcontact(contact, project, client) VALUES (?, ?, ?)";
	    					$stmtcontact = mysqli_prepare($conn, $sqlcontact) or die(mysqli_error($conn));
    						mysqli_stmt_bind_param($stmtcontact, "ddd", $value, $project, $id2) or die(mysqli_error($conn));
    						mysqli_stmt_execute($stmtcontact) or die(mysqli_error($conn));
    						mysqli_stmt_close($stmtcontact);

    						$sqlNewCheck = "SELECT type FROM contact WHERE idcontact = '$value' AND type='Colleague'";
    						$rNewCheck = mysqli_query($conn, $sqlNewCheck) or die(mysqli_error($conn));
    						if(mysqli_num_rows($rNewCheck) > 0){
    							$message = "You have been assigned to new client ".$name;
    							$type = "client";

    							$sqlNewNot = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
								$stmtNN = mysqli_prepare($conn, $sqlNewNot) or die(mysqli_error($conn));
								mysqli_stmt_bind_param($stmtNN, "sdss", $message, $value, $datetime, $type) or die(mysqli_error($conn));
								mysqli_stmt_execute($stmtNN) or die(mysqli_error($conn));
								mysqli_stmt_close($stmtNN);
    						}
	    				}
    				}


    			}
			} else{
				$sql = "UPDATE client SET client.name = ?, client.website = ?, client.hex = ?, client.view = ?, client.add = ?, client.attach = ?, client.notes = ? 
				WHERE client.idclient = ?";
				$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    			mysqli_stmt_bind_param($stmt, "sssdddsd", $name, $website, $color2, $view, $add, $attach, $notes, $id) or die(mysqli_error($conn));
    			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    			mysqli_stmt_close($stmt);

    			$sqlAtCheck = "SELECT type FROM contact WHERE client = '$id' AND type='Colleague'";
    			$rAtCheck = mysqli_query($conn, $sqlAtCheck) or die(mysqli_error($conn));
    			if(mysqli_num_rows($rAtCheck) > 0){
    				$message = $name." has been updated";
    				$type = "client";

    				$sqlEditNote = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
					$stmtEN = mysqli_prepare($conn, $sqlEditNote) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmtEN, "sdss", $message, $value, $datetime, $type) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmtEN) or die(mysqli_error($conn));
					mysqli_stmt_close($stmtEN);
    			}

    			if(isset($relatedcontactarr)){

    				$relatedcontact = explode(",", $relatedcontactarr);
    				foreach ($relatedcontact as $value) {
	    				$sqltest = "SELECT idrelatedcontact FROM relatedcontact WHERE relatedcontact.contact = '$value' AND relatedcontact.client = '$id'";
	    				$rtest = mysqli_query($conn, $sqltest) or die(mysqli_error($conn));
	    				
	    				if(mysqli_num_rows($rtest) === 0){
	    					$project = "";
	    					$sqlcontact = "INSERT INTO relatedcontact(contact, project, client) VALUES (?, ?, ?)";
	    					$stmtcontact = mysqli_prepare($conn, $sqlcontact) or die(mysqli_error($conn));
    						mysqli_stmt_bind_param($stmtcontact, "ddd", $value, $project, $id) or die(mysqli_error($conn));
    						mysqli_stmt_execute($stmtcontact) or die(mysqli_error($conn));
    						mysqli_stmt_close($stmtcontact);

    						$sqlColCheck = "SELECT type FROM contact WHERE idcontact = '$value' AND type='Colleague'";
    						$rColCheck = mysqli_query($conn, $sqlColCheck) or die(mysqli_error($conn));
    						if(mysqli_num_rows($rColCheck) > 0){
    							$message = "You have been attached to the client ".$name;
    							$type = "client";

    							$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
								$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
								mysqli_stmt_bind_param($stmtN, "sdss", $message, $value, $datetime, $type) or die(mysqli_error($conn));
								mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
								mysqli_stmt_close($stmtN);
    						}
	    				}
    				}

    				$sqlPickNot = "SELECT contact FROM relatedcontact WHERE client = '$id' AND project = '0'";
    				$rPickNot = mysqli_query($conn, $sqlPickNot) or die(mysqli_error($conn));
    				while($rowPN = mysqli_fetch_assoc($rPickNot)){
    					$to = $rowPN['contact'];
    					$message = "New contacts have been attached to ".$name;
    					$type = "contact";
    				
    					$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
						$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
						mysqli_stmt_bind_param($stmtN, "sdss", $message, $to, $datetime, $type) or die(mysqli_error($conn));
						mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
						mysqli_stmt_close($stmtN);
    				}
    			}
			}


			mysqli_close($conn);

			header("Location: ../project.php");
		}
	}
	echo pageClose();
?>