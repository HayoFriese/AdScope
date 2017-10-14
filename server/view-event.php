<?php
	echo "<section id=\"view-event\">";
		include "db.php";
		$eventid = $_REQUEST['id'];
		$hex = $_REQUEST['color'];

		$sqlEv = "SELECT idevent, event, startdate, starttime, enddate, endtime, event.description, client.name, project.title, project.id, eventby, user.firstname, user.lastname 
		FROM event INNER JOIN user ON event.eventby = user.iduser 
		INNER JOIN project on event.project = project.idproject 
		INNER JOIN client ON project.client = client.idclient 
		WHERE idevent = '$eventid'";
		$rEv = mysqli_query($conn, $sqlEv) or die(mysqli_error($conn));
		if(mysqli_num_rows($rEv) > 0){
			while($rowEv = mysqli_fetch_assoc($rEv)){
				$title = $rowEv['event'];
				$by = $rowEv['firstname']." ".$rowEv['lastname'];

				$startdate = $rowEv['startdate'];
				$startdate = date("M d", strtotime($startdate));

				$starttime = $rowEv['starttime'];
				$starttime = date("H:i", strtotime($starttime));

				$enddate = $rowEv['enddate'];
				$enddate = date("M d", strtotime($enddate));

				$endtime = $rowEv['endtime'];
				$endtime = date("H:i", strtotime($endtime));

				$projectid = $rowEv['id'];
				$projectname = $rowEv['title'];
				$clientname = $rowEv['name'];
				$byid = $rowEv['eventby'];

				$notes = $rowEv['description'];

				if($notes === "" || $notes === null){
					$notes = "(no description given)";
				}
			}
			echo "<article>
				<div style=\"border-left:10px solid $hex;\">
					<a id=\"close-create\" href=\"#\"><img src=\"resources/img/icon/cancel.svg\"></a>
					<ul id=\"more\">
						<li><img src=\"resources/img/icon/more.svg\">
							<ul>
								<li><a href=\"#\">Manage Details</a></li>
								<li id=\"cancel\" data-event-id=\"$eventid\"><a href=\"#\">Cancel</a></li>
							</ul>
						</li>
					</ul>
					<h1>$title</h1>
					<p>$by</p>
					<ul id=\"divisor\">
						<li id=\"active\"><a href=\"#\">Details</a></li>
						<li><a href=\"#\">Invites</a></li>
					</ul>
					<div id=\"event-details\">
						<div>
							<p>From: $starttime on $startdate<span>To: $endtime on $enddate</span></p>
							<p>Client: $clientname</p>
							<p>Project: $projectname</p>
							<p>ID: $projectid</p>
						</div>
						<div>
							<h2>Notes</h2>
							<p>$notes</p>
						</div>
					</div>
					<div id=\"event-invites\">";
						echo "<ul>";
						$sql = "SELECT type, firstname, lastname, src, response FROM invites INNER JOIN contact ON invites.toInvite = contact.idcontact WHERE forInvite = '$eventid'";
						$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
						while($rowI = mysqli_fetch_assoc($r)){
							$name = $rowI['firstname']." ".$rowI['lastname'];
							$src = $rowI['src'];
							$type = $rowI['type'];
							if($src === null){
								$src = "resources/img/icon/avatar.svg); background-size:60%; opacity:0.5;";
							}
							$response = $rowI['response'];

							if($response === '1' && ($type === "Colleague" || $type === "Other")){
								$response2 = "(pending)";
								echo "<li>
									<span style=\"border: 3px solid #EDBE6D;\"></span>
									<div style=\"background-image:url($src);\"></div>
									<p>$name</p>
								</li>";
							} elseif($response === '2' && ($type === "Colleague" || $type === "Other")){
								$response2 = "Attending";
								echo "<li>
									<span style=\"border: 3px solid #6DED7E;\"></span>
									<div style=\"background-image:url($src);\"></div>
									<p>$name</p>
								</li>";
							} elseif($response === '0' && ($type === "Colleague" || $type === "Other")){
								$response2 = "Declined";
								echo "<li>
									<span style=\"border: 3px solid #EB3D3D;\"></span>
									<div style=\"background-image:url($src);\"></div>
									<p>$name</p>
								</li>";
							} else{
								echo "<li>
									<span style=\"border: 3px solid #E4E4E4;\"></span>
									<div style=\"background-image:url($src);\"></div>
									<p>$name</p>
								</li>";
							}
						}
						echo "</ul>
					</div>
				</div>
			</article>
		</section>";
		}
			
?>