<?php
	include "db.php";

	if($_SERVER['REQUEST_METHOD'] == 'POST'){	
		$title = filter_has_var(INPUT_POST, 'project-title') ? $_POST['project-title']:null;
		$manager = filter_has_var(INPUT_POST, 'project-manager') ? $_POST['project-manager']: null;
		$client = filter_has_var(INPUT_POST, 'client') ? $_POST['client']:null;
		$type = filter_has_var(INPUT_POST, 'project-type') ? $_POST['project-type']:null;

		$include = filter_has_var(INPUT_POST, 'include') ? $_POST['include']:0;
		$notify = filter_has_var(INPUT_POST, 'notify') ? $_POST['notify']:0;
		$upload = filter_has_var(INPUT_POST, 'upload') ? $_POST['upload']:0;
		$email = filter_has_var(INPUT_POST, 'email') ? $_POST['email']:0;
		$schedule = filter_has_var(INPUT_POST, 'schedule') ? $_POST['schedule']:0;

  		$notes = "";

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
        $sqlNewNot = "SELECT DISTINCT contact FROM relatedcontact INNER JOIN contact on relatedcontact.contact = contact.idcontact WHERE contact.type = 'Colleague' AND relatedcontact.client = '$client'";
        $rNewNot = mysqli_query($conn, $sqlNewNot) or die(mysqli_error($conn));
                
        while($rowNN = mysqli_fetch_assoc($rNewNot)){
          $datetime = date("Y-m-d H:i:s", time());
          $to = $rowNN['contact'];
          $message = "A new project has been started: ".$title;
          $type = "client";
                  
          $sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
          $stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
          mysqli_stmt_bind_param($stmtN, "sdss", $message, $to, $datetime, $type) or die(mysqli_error($conn));
          mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
          mysqli_stmt_close($stmtN);
        }
   			header("Location: project-base.php?id=$id2");

  		} 
	} else {
		header("Location: ../projects.php");
	}
?>