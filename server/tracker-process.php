<?php
	include "db.php";

	if($_POST['process'] === "start-timer"){
		$starttime = date("Y-m-d H:i:s");
		$status = "active";
		$timerid = $_POST['timerid'];
		$projectid = $_POST['projectid'];
		$taskid = $_POST['taskid'];

		$sqlStart = "UPDATE logger SET status = ?, timerstart = ?, task = ?, project = ? WHERE idlogger = ?";
		$stmtS = mysqli_prepare($conn, $sqlStart) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtS, "ssddd", $status, $starttime, $taskid, $projectid, $timerid) or die(mysqli_error($conn));;
		mysqli_stmt_execute($stmtS) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtS) or die(mysqli_error($conn));
		echo $starttime;

	} elseif($_POST['process'] === "pause-timer"){
		$timerid = $_POST['timerid'];
		$pausetime = date("Y-m-d H:i:s");
		$status = "paused";

		$sqlMin = "UPDATE logger SET status = ?, lastpause = ? WHERE idlogger = ?";
		$stmtM = mysqli_prepare($conn, $sqlMin) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtM, "ssd", $status, $pausetime, $timerid) or die(mysqli_error($conn));;
		mysqli_stmt_execute($stmtM) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtM) or die(mysqli_error($conn));
		echo $pausetime;

	} elseif($_POST['process'] === "resume-timer"){
		$timerid = $_POST['timerid'];
		$status = "active";
		$current = date('Y-m-d H:i:s');
		$now = date("Y-m-d H:i:s");

		$sqlPaus = "SELECT lastpause, pauselength FROM logger WHERE idlogger = '$timerid'";
		$rPaus = mysqli_query($conn, $sqlPaus) or die(mysqli_error($conn));
		while($rowP = mysqli_fetch_assoc($rPaus)){
			$pausetime = $rowP['lastpause'];
			$pauselength = $rowP['pauselength'];
			$num;
			if($pauselength === null){
				$num = 0;
			} else {
				$num = $pauselength;
			}
			$length = (strtotime($now)-strtotime($pausetime))+$num;
			
			$sqlMin = "UPDATE logger SET status = ?, pauselength = ? WHERE idlogger = ?";
			$stmtM = mysqli_prepare($conn, $sqlMin) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtM, "ssd", $status, $length, $timerid) or die(mysqli_error($conn));;
			mysqli_stmt_execute($stmtM) or die(mysqli_error($conn));
			mysqli_stmt_close($stmtM) or die(mysqli_error($conn));
			echo $length;
		}

	} elseif($_POST['process'] === "date-timer"){
		$timerid = $_POST['timerid'];
		$status = "done";
		$endtime = date("Y-m-d H:i:s");

		$sqlEnd = "UPDATE logger SET status = ?, timerend = ? WHERE idlogger = ?";
		$stmtE = mysqli_prepare($conn, $sqlEnd) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtE, "ssd", $status, $endtime, $timerid) or die(mysqli_error($conn));;
		mysqli_stmt_execute($stmtE) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtE) or die(mysqli_error($conn));
		
		echo $endtime;

	} elseif($_POST['process'] === "stop-timer"){
		$timerid = $_POST['timerid'];
		$timerTitle = $_POST['title'];
		$userid = $_POST['userid'];
		$projectid = $_POST['projectid'];

		$cost;
		$taskid;

		$sqlT = "SELECT timerstart, timerend, task, pauselength FROM logger WHERE idlogger = '$timerid'";
		$rT = mysqli_query($conn, $sqlT) or die(mysqli_error($conn));
		while($rowT = mysqli_fetch_assoc($rT)){
			$timerstart = $rowT['timerstart'];
			$timerend = $rowT['timerend'];
			$pauselength = $rowT['pauselength'];
				if(!$pauselength){
					$pauselength = 0;
				}
			$sqlC = "SELECT salary FROM user WHERE iduser = '$userid'";
			$rC = mysqli_query($conn, $sqlC) or die(mysqli_error($conn));
			$hrwage = mysqli_fetch_assoc($rC)['salary'];

			$secwage = $hrwage/3600;

			$math = (strtotime($timerend)-strtotime($timerstart)-$pauselength)*$secwage;
			$cost = round($math, 2);
			$taskid = $rowT['task'];
		}

		
		$sqlEnd = "UPDATE logger SET logger = ?, cost = ? WHERE idlogger = ?";
		$stmtE = mysqli_prepare($conn, $sqlEnd) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtE, "ssd", $timerTitle, $cost, $timerid) or die(mysqli_error($conn));;
		mysqli_stmt_execute($stmtE) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtE) or die(mysqli_error($conn));

		$sqlTask = "UPDATE task SET cost = cost + ? WHERE idtask = ?";
		$stmtTASK = mysqli_prepare($conn, $sqlTask) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtTASK, "sd", $cost, $taskid) or die(mysqli_error($conn));
		mysqli_stmt_execute($stmtTASK) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtTASK) or die(mysqli_error($conn));

		$sqlProject = "UPDATE project SET totalcost = totalcost + ? WHERE idproject = ?";
		$stmtProj = mysqli_prepare($conn, $sqlProject) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtProj, "sd", $cost, $projectid) or die(mysqli_error($conn));
		mysqli_stmt_execute($stmtProj) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtProj) or die(mysqli_error($conn));

	} elseif($_POST['process'] === "discard-timer"){
		$timerid = $_POST['timerid'];

		$sqlDel = "DELETE FROM logger WHERE idlogger = ?";
		$stmtD = mysqli_prepare($conn, $sqlEnd) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtD, "d", $timerid) or die(mysqli_error($conn));;
		mysqli_stmt_execute($stmtD) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtD) or die(mysqli_error($conn));
		echo "Timer Deleted";
	} elseif($_POST['process'] === "new-timer"){
		$userid = $_POST['userid'];
		$timerid = $_POST['timerid'];

		$sqlDel = "DELETE FROM logger WHERE idlogger = ?";
		$stmtD = mysqli_prepare($conn, $sqlDel) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmtD, "d", $timerid) or die(mysqli_error($conn));;
		mysqli_stmt_execute($stmtD) or die(mysqli_error($conn));
		mysqli_stmt_close($stmtD) or die(mysqli_error($conn));

		$tempname = "Temporary";
		$tempstatus = "temp";

		$sqlTemp = "INSERT INTO logger(logger, user, status) 
		VALUES(?, ?, ?)";
		$stmtT = mysqli_prepare($conn, $sqlTemp) or die(mysqli_error($conn));
    	mysqli_stmt_bind_param($stmtT, "sds", $tempname, $userid, $tempstatus) or die(mysqli_error($conn));
    	mysqli_stmt_execute($stmtT) or die(mysqli_error($conn));
    	mysqli_stmt_close($stmtT);
	}
?>