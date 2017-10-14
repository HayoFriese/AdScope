<?php
	include "db.php";

	if(isset($_POST['userid']) && isset($_POST['type'])){
		$type = $_POST['type'];

		if($type === "new"){
			$userid = filter_has_var(INPUT_POST, "userid") ? $_POST['userid']:null;
			$projectid = filter_has_var(INPUT_POST, "projectid") ? $_POST['projectid']:null;
			$taskname = filter_has_var(INPUT_POST, "taskname") ? $_POST['taskname']:null;
			$notify = filter_has_var(INPUT_POST, "notify") ? $_POST['notify']:null;
			$priority = filter_has_var(INPUT_POST, "priority") ? $_POST['priority']:null;
			$recurring = filter_has_var(INPUT_POST, "recurring") ? $_POST['recurring']:null;

			$done = 0;

			$sqlNew = "INSERT INTO task(task, notify, priority, recurring, done, postedby, project) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$stmtNEW = mysqli_prepare($conn, $sqlNew) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtNEW, "sdddddd", $taskname, $notify, $priority, $recurring, $done, $userid, $projectid) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmtNEW) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmtNEW);

    		echo "success";

    		$userME = "";
			$sqlMe = "SELECT firstname, lastname FROM user WHERE iduser = '$userid'";
			$rMe = mysqli_query($conn, $sqlMe) or die(mysqli_error($conn));
			while($rowME = mysqli_fetch_assoc($rMe)){
				$userME = $rowME['firstname']." ".$rowME['lastname'];
			}

			$sqlSel = "SELECT contact.idcontact, project.title FROM relatedcontact 
				INNER JOIN contact ON relatedcontact.contact = contact.idcontact 
				INNER JOIN project ON relatedcontact.client = project.idproject 
				WHERE contact.type='Colleague' AND project = '$projectid'";
			$rSel = mysqli_query($conn, $sqlSel) or die(mysqli_error($conn));
			while($rowS = mysqli_fetch_assoc($rSel)){
				$conID = $rowS['idcontact'];
				$projectitle = $rowS['title'];

				$message = $userME." added a new task in ".$projectitle;
				$type = "task";
				$datetime = date("Y-m-d H:i:s", time());

				$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
				$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtN, "sdss", $message, $conID, $datetime, $type) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
				mysqli_stmt_close($stmtN);
			}
		}
		elseif($type === "done"){
			$userid = filter_has_var(INPUT_POST, "userid") ? $_POST['userid']:null;
			$taskid = filter_has_var(INPUT_POST, "taskid") ? $_POST['taskid']:null;
			$done = 1;
			$on = date("Y-m-d H:i:s", time());

			$sqlDone = "UPDATE task SET done = ?, completedby = ?, completedon = ? WHERE idtask = ?";
			$stmtDON = mysqli_prepare($conn, $sqlDone) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtDON, "ddsd", $done, $userid, $on, $taskid) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmtDON) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmtDON); 

    		echo "success";

    		$sqlGet = "SELECT project FROM task WHERE idtask = '$taskid'";
    		$rGet = mysqli_query($conn, $sqlGet) or die(mysqli_error($conn));
    		$pid = mysqli_fetch_assoc($rGet)['project'];

    		$userME = "";
			$sqlMe = "SELECT firstname, lastname FROM user WHERE iduser = '$userid'";
			$rMe = mysqli_query($conn, $sqlMe) or die(mysqli_error($conn));
			while($rowME = mysqli_fetch_assoc($rMe)){
				$userME = $rowME['firstname']." ".$rowME['lastname'];
			}

			$sqlSel = "SELECT contact.idcontact, project.title FROM relatedcontact 
				INNER JOIN contact ON relatedcontact.contact = contact.idcontact 
				INNER JOIN project ON relatedcontact.client = project.idproject 
				WHERE contact.type='Colleague' AND project = '$pid'";
			$rSel = mysqli_query($conn, $sqlSel) or die(mysqli_error($conn));
			while($rowS = mysqli_fetch_assoc($rSel)){
				$conID = $rowS['idcontact'];
				$projectitle = $rowS['title'];

				$message = $userME." completed a task in ".$projectitle;
				$type = "task";
				$datetime = date("Y-m-d H:i:s", time());

				$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
				$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtN, "sdss", $message, $conID, $datetime, $type) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
				mysqli_stmt_close($stmtN);
			}
		}
	}
?>