<?php
	include "db.php";

	if(isset($_POST['id'])){

		$notid = $_POST['id'];
		$choice = $_POST['choice'];
		$userid = $_POST['userid'];

		$datetime = date("Y-m-d H:i:s", time());

		$sqlDel = "UPDATE invites SET response = ?, responded = ? WHERE idinvites = ?";
		$stmtD = mysqli_prepare($conn, $sqlDel) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtD, "dsd", $choice, $datetime, $notid) or die(mysqli_error($conn));
		mysqli_stmt_execute($stmtD) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtD);

			
		$sqlU = "SELECT fromInvite, event.idevent, user.firstname, user.lastname, user.salary, event.event, event.startdate, event.starttime, event.enddate, event.endtime, event.project FROM invites
			INNER JOIN user ON invites.toInvite = user.iduser 
			INNER JOIN event ON invites.forInvite = event.idevent 
			WHERE iduser = '$userid' AND idinvites = '$notid'";
		$rU = mysqli_query($conn, $sqlU) or die(mysqli_error($conn));
		
		while($rowU = mysqli_fetch_assoc($rU)){
			$to = $rowU['fromInvite'];
			$username = $rowU['firstname']." ".$rowU['lastname'];
			$eventTitle = $rowU['event'];

			$eventid = $rowU['idevent'];
			$salary = $rowU['salary'];

			$startD = $rowU['startdate'];
			$startT = $rowU['starttime'];
			$endD = $rowU['enddate'];
			$endT = $rowU['endtime'];

			$projectid = $rowU['project'];

			$wage = $salary/3600;

			$math;

			$message = "";

			if($choice === "0"){
				$message = $username." declined the invite to ".$eventTitle;
				$cost = 0;
			} elseif ($choice === "2"){
				$message = $username." accepted the invite to ".$eventTitle;

				if($startD === $endD && $startT === null && $endT === null){
					$math = 28800*$wage;
				} else {
					$start = date("Y-m-d H:i:s", strtotime("$startD $startT"));
					$end = date("Y-m-d H:i:s", strtotime("$endD $endT"));
					$math = (strtotime($end)-strtotime($start))*$wage;
				}

				$cost = round($math, 2);
			}

			$type = "invite";

			$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
			$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtN, "sdss", $message, $to, $datetime, $type) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
			mysqli_stmt_close($stmtN);

			$sqlCost = "UPDATE event SET cost = cost + ? WHERE idevent = ?";
			$stmtC = mysqli_prepare($conn, $sqlCost) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtC, "sd", $cost, $eventid) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmtC) or die(mysqli_error($conn));
			mysqli_stmt_close($stmtC);

			$sqlProject = "UPDATE project SET totalcost = totalcost + ? WHERE idproject = ?";
			$stmtProj = mysqli_prepare($conn, $sqlProject) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtProj, "sd", $cost, $projectid) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmtProj) or die(mysqli_error($conn));
			mysqli_stmt_close($stmtProj) or die(mysqli_error($conn));

			if($choice === "0"){
				echo "You have declined the invite.";
			} elseif($choice === "2"){
				echo "You have accepted the invite.";
			}
		}
	}
?>