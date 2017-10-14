<?php
	include "db.php";

	$userid = $_REQUEST['userid'];
	echo "<section id=\"view-logger\">
		<article>
			<div>
				<a id=\"close-create\" href=\"#\"><img src=\"resources/img/icon/cancel.svg\"></a>
				<h1>Past Loggers</h1>
				<div>
					<ul>
						<li>Name</li>
						<li>Status</li>
						<li>Length</li>
						<li>Task</li>
						<li>Project</li>
						<li>Cost</li>
					</ul>";
					$sql = "SELECT logger, status, timerstart, timerend, task, project, cost, pauselength FROM logger 
						WHERE user = '$userid' AND status != 'temp'";
					$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
					while($row = mysqli_fetch_assoc($r)){
						$name = $row['logger'];
						$status= $row['status'];
						$timerstart = $row['timerstart'];
						$timerend = $row['timerend'];


						$taskid = $row['task'];
						if($taskid != 0){
							$sqlT = "SELECT task FROM task WHERE idtask = '$taskid'";
							$rT = mysqli_query($conn, $sqlT) or die(mysqli_error($conn));
							$task = mysqli_fetch_assoc($rT)['task'];
						} else{
							$task = "Other Task";
						}
						$projectid = $row['project'];
						$sqlP = "SELECT title FROM project WHERE idproject = '$projectid'";
						$rP = mysqli_query($conn, $sqlP) or die(mysqli_error($conn));
						$project = mysqli_fetch_assoc($rP)['title'];

						$cost = $row['cost'];
						$pauselength = $row['pauselength'];
						if(!$pauselength){
							$pauselength = 0;
						}

						$math = strtotime($timerend)-strtotime($timerstart)-$pauselength;
						$duration = gmdate("H:i:s", $math);

						echo "<ul>
							<li>$name</li>
							<li>$status</li>
							<li>$duration</li>
							<li>$task</li>
							<li>$project</li>
							<li>&pound;$cost</li>
						</ul>";
					}
				echo "</div>
			</div>
		</article>
	</section>";
?>