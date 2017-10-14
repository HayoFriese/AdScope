<?php
	include "db.php";
	$projectid = isset($_REQUEST['id']) ? $_REQUEST['id']:null;

	$sql = "SELECT * FROM task WHERE project = '$projectid' AND done != '1'";
	$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	if(mysqli_num_rows($r) > 0){
		while($row = mysqli_fetch_assoc($r)){
			$taskid = $row['idtask'];
			$taskname = $row['task'];
			$notify = $row['notify'];
			$priority = $row['priority'];
			$recurring = $row['recurring'];

			if($notify === "1"){
				$notifyimg = "../resources/img/icon/bell.svg";
			} else{
				$notifyimg = "../resources/img/icon/bell-no.svg";
			}

			if($priority === "1"){
				$priorityimg = "../resources/img/icon/star-filled-black.svg";
			} else {
				$priorityimg = "../resources/img/icon/star-faded.svg";
			}

			if($recurring === "1"){
				$recurringimg = "../resources/img/icon/two-circling-arrows.svg";
			} else {
				$recurringimg = "../resources/img/icon/two-circling-arrows-no.svg";
			}

			echo "<li>
					<a href=\"#\">$taskname</a>
					<p>
						<a href=\"#\"><img src=\"$notifyimg\"></a>
						<span></span>
						<a href=\"#\"><img src=\"$priorityimg\"></a>
						<span></span>
						<a href=\"#\"><img src=\"$recurringimg\"></a>

						<a href=\"#\" id=\"done-task\" data-task-id=\"$taskid\"><img src=\"../resources/img/icon/checkmark.svg\"></a>
					</p>
				</li>\n";
		}
	}
?>