<?php
	include "db.php";

	if(isset($_POST['type'])){
		$type = $_POST['type'];

		if($type == 'new') {
			$title = filter_has_var(INPUT_POST, 'event-title') ? $_POST['event-title']:null;
			$startdate = filter_has_var(INPUT_POST, 'start-date') ? $_POST['start-date']:null;
			$enddate = filter_has_var(INPUT_POST, 'end-date') ? $_POST['end-date']:null;
			$project = filter_has_var(INPUT_POST, 'project') ? $_POST['project']:null;
			$allday = filter_has_var(INPUT_POST, 'allday') ? $_POST['allday']:0;
			$userid = filter_has_var(INPUT_POST, 'event-by') ? $_POST['event-by']:null;

			$sqlClient = "SELECT client FROM project WHERE idproject = $project";
			$rClient = mysqli_query($conn, $sqlClient) or die(mysqli_error($conn));
			$client = mysqli_fetch_assoc($rClient)['client'];

			$sqlSalary = "SELECT salary FROM user WHERE iduser = '$userid'";
			$rSalary = mysqli_query($conn, $sqlSalary) or die(mysqli_error($conn));
			$salary = mysqli_fetch_assoc($rSalary)['salary'];
			$wage = $salary/3600;

			$lastid;

			$description = filter_has_var(INPUT_POST, 'description') ? $_POST['description']:null;

			if((isset($_POST['start-time']) || isset($_POST['end-time'])) && ($_POST['start-time'] != "" || $_POST['start-time'] != null || $_POST['end-time'] != "" || $_POST['end-time'] != null)){
				$starttime = filter_has_var(INPUT_POST, 'start-time') ? $_POST['start-time']:null;
				$endtime = filter_has_var(INPUT_POST, 'end-time') ? $_POST['end-time']:null;

				$start = date("Y-m-d H:i:s", strtotime("$startdate $starttime"));
				$end = date("Y-m-d H:i:s", strtotime("$enddate $endtime"));
				$math = (strtotime($end)-strtotime($start))*$wage;

				$cost = round($math, 2);

				$sqlinsert = "INSERT INTO event(event, startdate, starttime, enddate, endtime, description, client, project, allday, eventby, cost) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stmtIN = mysqli_prepare($conn, $sqlinsert) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtIN, "ssssssdddsd", $title, $startdate, $starttime, $enddate, $endtime, $description, $client, $project, $allday, $userid, $cost) or die(mysqli_error($conn));
    			mysqli_stmt_execute($stmtIN) or die(mysqli_error($conn));
    			mysqli_stmt_close($stmtIN);

    			$lastid = mysqli_insert_id($conn);

			} else{
				$math = 28800*$wage;
				$cost = round($math, 2);

				$sqlinsert = "INSERT INTO event(event, startdate, enddate, description, client, project, allday, eventby, cost) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$stmtIN = mysqli_prepare($conn, $sqlinsert) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtIN, "ssssdddsd", $title, $startdate, $enddate, $description, $client, $project, $allday, $userid, $cost) or die(mysqli_error($conn));
    			mysqli_stmt_execute($stmtIN) or die(mysqli_error($conn));
    			mysqli_stmt_close($stmtIN);

    			$lastid = mysqli_insert_id($conn);

    			$sqlProject = "UPDATE project SET totalcost = totalcost + ? WHERE idproject = ?";
				$stmtProj = mysqli_prepare($conn, $sqlProject) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtProj, "sd", $cost, $project) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmtProj) or die(mysqli_error($conn));
				mysqli_stmt_close($stmtProj) or die(mysqli_error($conn));
			}


			

			if(isset($_POST['invitearray'])){

				$invitearray = filter_has_var(INPUT_POST, 'invitearray') ? $_POST['invitearray']:null;
				$invite = explode(",", $invitearray);

				$sent = date("Y-m-d H:i:s", time());

    			foreach ($invite as $value) {
    				if($value === $userid){	
    					$status = 2;
    					$responded = date("Y-m-d H:i:s", time());

    					$sqlME = "INSERT INTO invites(fromInvite, forInvite, toInvite, sent, response, responded) VALUES (?, ?, ?, ?, ?, ?)";
    					$stmtME = mysqli_prepare($conn, $sqlME) or die(mysqli_error($conn));
	    				mysqli_stmt_bind_param($stmtME, "dddsds", $userid, $lastid, $value, $sent, $status, $responded) or die(mysqli_error($conn));
	    				mysqli_stmt_execute($stmtME) or die(mysqli_error($conn));
	    				mysqli_stmt_close($stmtME);
    				} else {
    					$status = 1;

    					$sqlcontact = "INSERT INTO invites(fromInvite, forInvite, toInvite, sent, response) VALUES (?, ?, ?, ?, ?)";
	    				$stmtcontact = mysqli_prepare($conn, $sqlcontact) or die(mysqli_error($conn));
    					mysqli_stmt_bind_param($stmtcontact, "dddsd", $userid, $lastid, $value, $sent, $status) or die(mysqli_error($conn));
    					mysqli_stmt_execute($stmtcontact) or die(mysqli_error($conn));
    					mysqli_stmt_close($stmtcontact);
    				}
    			}
			}

			$color = $project;

			$sqlCol = "SELECT hex FROM client WHERE idclient = '$client'";
			$rCol = mysqli_query($conn, $sqlCol) or die(mysqli_error($conn));

			while($rowColor = mysqli_fetch_assoc($rCol)){
				$color = $rowColor['hex'];
			}

			$data = ['id' => $lastid, 'title' => $title, 'color'=> $color];
			$datasend = json_encode($data);
			echo $datasend;
		}

		elseif($type == 'move'){
			//Remove the cost of the original cost, then add the xost of the new event.
			$eventid = filter_has_var(INPUT_POST, 'eventid') ? $_POST['eventid']:null;
			$startdate = filter_has_var(INPUT_POST, 'start') ? $_POST['start']:null;
			$enddate = filter_has_var(INPUT_POST, 'end') ? $_POST['end']:null;
			$allday = filter_has_var(INPUT_POST, 'allday') ? $_POST['allday']:0;

			if(strpos($startdate, "T") !== false || strpos($enddate, "T") !== false){

				$stringset = explode("T", $startdate);
				$start = date("Y-m-d", strtotime($stringset[0]));
				$startT = date('H:i', strtotime($stringset[1]));

				$stringgroup = explode("T", $enddate);
				$end = date("Y-m-d", strtotime($stringgroup[0]));
				$endT = date('H:i', strtotime($stringgroup[1]));

				if($allday === 1){
					$endT = null;
					$startT = null;
				}

				$sqlUpdate = "UPDATE event SET startdate = ?, starttime = ?, enddate = ?, endtime = ?, allday = ? WHERE idevent = ?";
				$stmtUP = mysqli_prepare($conn, $sqlUpdate) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtUP, "ssssdd", $start, $startT, $end, $endT, $allday, $eventid) or die(mysqli_error($conn));
	    		mysqli_stmt_execute($stmtUP) or die(mysqli_error($conn));
	    		mysqli_stmt_close($stmtUP);

    			echo $start." at ".$startT;

			}else{
				if($allday === 1){
					$endT = null;
					$startT = null;
				}
				$sqlUpdate = "UPDATE event SET startdate = ?, starttime = ?, enddate = ?, endtime = ?, allday = ? WHERE idevent = ?";
				$stmtUP = mysqli_prepare($conn, $sqlUpdate) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtUP, "ssssdd", $startdate, $startT, $enddate, $endT, $allday, $eventid) or die(mysqli_error($conn));
    			mysqli_stmt_execute($stmtUP) or die(mysqli_error($conn));
    			mysqli_stmt_close($stmtUP);

    			echo $startdate;
			}
		} 

		elseif($type == 'cancel'){
			$status = "Cancelled";
			$eventid = filter_has_var(INPUT_POST, 'id') ? $_POST['id']:null;


			$sqlDelete = "UPDATE event SET status = ? WHERE idevent = ?";
				$stmtDEL = mysqli_prepare($conn, $sqlDelete) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtDEL, "sd", $status, $eventid) or die(mysqli_error($conn));
	    		mysqli_stmt_execute($stmtDEL) or die(mysqli_error($conn));
	    		mysqli_stmt_close($stmtDEL);

	    	$sqlTitle = "SELECT event FROM event WHERE idevent = $eventid";
	    	$rTitle = mysqli_query($conn, $sqlTitle) or die(mysqli_error($conn));
	    	$title = mysqli_fetch_assoc($rTitle)['event'];

	    	echo "Title";
		}
		else{
			echo "OOPS! Something went horribly wrong";
		}
	} else { 
		echo "Data has not been set";
	}
	
?>