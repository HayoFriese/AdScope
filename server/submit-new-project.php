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
			
			$id = filter_has_var(INPUT_POST, 'project-id') ? $_POST['project-id']: null;
			$title = filter_has_var(INPUT_POST, 'project-title') ? $_POST['project-title']:null;
			$manager = filter_has_var(INPUT_POST, 'project-manager') ? $_POST['project-manager']: null;
			$client = filter_has_var(INPUT_POST, 'client-id') ? $_POST['client-id']:null;
			$type = filter_has_var(INPUT_POST, 'project-type') ? $_POST['project-type']:null;
	
			$include = filter_has_var(INPUT_POST, 'include') ? $_POST['include']:0;
			$notify = filter_has_var(INPUT_POST, 'notify') ? $_POST['notify']:0;
			$upload = filter_has_var(INPUT_POST, 'upload') ? $_POST['upload']:0;
			$email = filter_has_var(INPUT_POST, 'email') ? $_POST['email']:0;
			$schedule = filter_has_var(INPUT_POST, 'schedule') ? $_POST['schedule']:0;

			$notes = filter_has_var(INPUT_POST, 'notes') ? $_POST['notes']: null;	
			
			$relatedcontactarr = filter_has_var(INPUT_POST, 'relatedcontact') ? $_POST['relatedcontact']: 0;

			
			if($id === null || !$id || $id === ""){
				$started = date('Y-m-d H:i:s');

  				$iddate = date('d-m-y');
  				$iddate = str_replace(array('-','0'), '', $iddate);
		
  				$sqlAcro = "SELECT acronym, yearCreated FROM client WHERE client.idclient = '$client'";
  				$rAcro = mysqli_query($conn, $sqlAcro) or die(mysqli_error($conn));
  				if(mysqli_num_rows($rAcro) != 0){
  					while($row = mysqli_fetch_assoc($rAcro)){
  						$acronym = $row['acronym'];
  						$yearSince = $row['yearCreated'];
  						$yearNow = date("Y");
		
  						$years = (int)$yearNow - (int)$yearSince;
  					}
  					$sqlNum = "SELECT idproject FROM project WHERE project.client = '$client'";
  					$rNum = mysqli_query($conn, $sqlNum) or die(mysqli_error($conn));
  					$num = mysqli_num_rows($rNum);
  					if(strlen($num) < 2){
  						if(strlen($years) < 2){
  							$num = "0".$num;
  						} else{
  							$num = $num;
  						}
  					}
				}

  				$projectid = $acronym."-".$years.$num."-".$iddate;

				$sql = "INSERT INTO project(project.id, project.title, project.client, project.type, project.include, project.notify, project.upload, project.email, project.schedule, project.notes, project.started, project.projectmanager) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));  		
        		mysqli_stmt_bind_param($stmt, "ssdsdddddssd", $projectid, $title, $client, $type, $include, $notify, $upload, $email, $schedule, $notes, $started, $manager) or die(mysqli_error($conn));
        		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
   				mysqli_stmt_close($stmt);

    			$id2 = mysqli_insert_id($conn);

    			if(isset($id2)){
        			$sqlPM = "INSERT INTO relatedcontact(relatedcontact.contact, relatedcontact.project, relatedcontact.client) VALUES(?, ?, ?)";
        			$stmtPM = mysqli_prepare($conn, $sqlPM) or die(mysqli_error($conn));      
        			mysqli_stmt_bind_param($stmtPM, "ddd", $manager, $id2, $client) or die(mysqli_error($conn));
        			mysqli_stmt_execute($stmtPM) or die(mysqli_error($conn));
        			mysqli_stmt_close($stmtPM);

        			$sqlCon = "SELECT idcontact FROM contact WHERE contact.type = 'Client' AND contact.client = '$client'";
        			$rCon = mysqli_query($conn, $sqlCon) or die(mysqli_error($conn));
        			
        			while($rowCon = mysqli_fetch_assoc($rCon)){
        				$idcont = $rowCon['idcontact'];
        			 
        				$sql2 = "INSERT INTO relatedcontact(relatedcontact.contact, relatedcontact.project, relatedcontact.client) VALUES(?, ?, ?)";
        				$stmt2 = mysqli_prepare($conn, $sql2) or die(mysqli_error($conn));      
        				mysqli_stmt_bind_param($stmt2, "ddd", $idcont, $id2, $client) or die(mysqli_error($conn));
        				mysqli_stmt_execute($stmt2) or die(mysqli_error($conn));
        				mysqli_stmt_close($stmt2);
        			}
        		}

    			if(isset($relatedcontactarr)){
    				$relatedcontact = explode(",", $relatedcontactarr);
    				foreach ($relatedcontact as $value) {
	    				$sqltest = "SELECT idrelatedcontact FROM relatedcontact WHERE relatedcontact.contact = '$value' AND relatedcontact.client = '$client' AND relatedcontact.project = '$id'";
	    				$rtest = mysqli_query($conn, $sqltest) or die(mysqli_error($conn));
	    				
	    				if(mysqli_num_rows($rtest) === 0){
	    					$sqlcontact = "INSERT INTO relatedcontact(contact, project, client) VALUES (?, ?, ?)";
	    					$stmtcontact = mysqli_prepare($conn, $sqlcontact) or die(mysqli_error($conn));
    						mysqli_stmt_bind_param($stmtcontact, "ddd", $value, $id, $client) or die(mysqli_error($conn));
    						mysqli_stmt_execute($stmtcontact) or die(mysqli_error($conn));
    						mysqli_stmt_close($stmtcontact);

                $sqlNewCheck = "SELECT type FROM contact WHERE idcontact = '$value' AND type='Colleague'";
                $rNewCheck = mysqli_query($conn, $sqlNewCheck) or die(mysqli_error($conn));
                if(mysqli_num_rows($rNewCheck) > 0){
                  $message = "You have been assigned to new project ".$title;
                  $type = "project";

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
				$sql = "UPDATE project SET project.title = ?, project.client = ?, project.type = ?, project.include = ?, project.notify = ?, project.upload = ?, project.email = ?, project.schedule = ?, project.notes = ?, project.projectmanager = ?
				WHERE project.idproject = ?";

				$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    			mysqli_stmt_bind_param($stmt, "sdddddddsdd", $title, $client, $type, $include, $notify, $upload, $email, $schedule, $notes, $manager, $id) or die(mysqli_error($conn));
    			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    			mysqli_stmt_close($stmt);

    			if(isset($relatedcontactarr)){
    				$relatedcontact = explode(",", $relatedcontactarr);
    				foreach ($relatedcontact as $value) {
	    				$sqltest = "SELECT idrelatedcontact FROM relatedcontact WHERE relatedcontact.contact = '$value' AND relatedcontact.client = '$client' AND relatedcontact.project = '$id'";
	    				$rtest = mysqli_query($conn, $sqltest) or die(mysqli_error($conn));
	    				
	    				if(mysqli_num_rows($rtest) === 0){
	    					$sqlcontact = "INSERT INTO relatedcontact(contact, project, client) VALUES (?, ?, ?)";
	    					$stmtcontact = mysqli_prepare($conn, $sqlcontact) or die(mysqli_error($conn));
    						mysqli_stmt_bind_param($stmtcontact, "ddd", $value, $id, $client) or die(mysqli_error($conn));
    						mysqli_stmt_execute($stmtcontact) or die(mysqli_error($conn));
    						mysqli_stmt_close($stmtcontact);

                $sqlColCheck = "SELECT type FROM contact WHERE idcontact = '$value' AND type='Colleague'";
                $rColCheck = mysqli_query($conn, $sqlColCheck) or die(mysqli_error($conn));
                if(mysqli_num_rows($rColCheck) > 0){
                  $message = "You have been attached to the project ".$title;
                  $type = "project";

                  $sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
                  $stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
                  mysqli_stmt_bind_param($stmtN, "sdss", $message, $value, $datetime, $type) or die(mysqli_error($conn));
                  mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
                  mysqli_stmt_close($stmtN);
                }
	    				}
    				}
    			}
			}


			mysqli_close($conn);


			// echo "<p>".$name."</p>\n<p>".$website."</p>\n<p>".$color."</p>";
			// echo "<p>".$view."</p>\n<p>".$add."</p>\n<p>".$attach."</p>";

			// echo "<div>$notes</div>";
			header("Location: ../project.php");
		}
	}
	echo pageClose();
?>