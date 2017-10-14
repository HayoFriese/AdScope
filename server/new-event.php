<?php
	include "db.php";

	$start = $_REQUEST['start'];
	$end = $_REQUEST['end'];

	if(strpos($start, "T") !== false || strpos($end, "T") !== false){
		$stringset = explode("T", $start);
		$start = date("Y-m-d", strtotime($stringset[0]));
		$startT = date('H:i', strtotime($stringset[1]));

		$stringgroup = explode("T", $end);
		$end = date("Y-m-d", strtotime($stringgroup[0]));
		$endT = date('H:i', strtotime($stringgroup[1]));
	}

	$userid = $_REQUEST['userid'];

	echo "<section id=\"add-new\">
		<article>
			<form method=\"post\" action=\"server/create-new-event.php\" id=\"adding-new-event\">
				<a id=\"close-create\" href=\"#\"><img src=\"resources/img/icon/cancel.svg\"></a>
				<h1>New Event</h1>
				<ul id=\"divisor\">
					<li id=\"active\"><a href=\"#\">Details</a></li>
					<li><a href=\"#\">Additional Info</a></li>
					<li><a href=\"#\">Attendees</a></li>
				</ul>

				<div id=\"event-details\">
					<div>
						<label for=\"event-title\">Event Title</label>
						<input type=\"text\" id=\"event-title\" name=\"event-title\" required>
						<input type=\"hidden\" name=\"event-by\" value=\"$userid\">		
					</div>
					<div>
						<label for=\"project\">Project</label>
						<select id=\"project\" name=\"project\">
							<option>-- Select a Project--</option>";
	
							$sqlProj = "SELECT project.idproject, project.title FROM relatedcontact INNER JOIN project ON relatedcontact.project = project.idproject WHERE contact = '$userid'";
							$rProj = mysqli_query($conn, $sqlProj) or die(mysqli_error($conn));
							while($rowProj = mysqli_fetch_assoc($rProj)){
								$idproj = $rowProj['idproject'];
								$titleproj = $rowProj['title'];
								echo "<option value=\"$idproj\">$titleproj</option>";
							}
						echo "</select>
					</div>
					<div>
						<div>
							<label for=\"start-date\">Start</label>
							<input type=\"date\" id=\"start-date\" name=\"start-date\" value=\"$start\" required>
						</div>
						<div>
							<label for=\"start-time\">Time</label>
							<input type=\"time\" id=\"start-time\" name=\"start-time\" value=\"$startT\">
						</div>
					</div>
					<div>
						<div>
							<label for=\"end-date\">End</label>
							<input type=\"date\" id=\"end-date\" name=\"end-date\" value=\"$start\" required>
						</div>
						<div>
							<label for=\"end-time\">Time</label>
							<input type=\"time\" id=\"end-time\" name=\"end-time\" value=\"$endT\">
						</div>
					</div>
					<div id=\"form-checklist\">
						<label><input type=\"checkbox\" name=\"allday\" value=\"1\">All Day</label>
					</div>
				</div>
				<div id=\"event-description\">
					<div>
						<h2>Description</h2>
						<textarea name=\"description\"></textarea>
					</div>
				</div>
				<div id=\"event-invites\">
					<div>
						<input type=\"hidden\" name=\"invitearray\" id=\"invitearray\" value=\"$userid\">
						<div id=\"invite-arr\">
							<span>Click on the contacts you wish to invite...</span>
						</div>
					</div>
					<div>";
						$projarr = [];
						$cliarr = [];

						$sqlSelect = "SELECT DISTINCT project, client FROM relatedcontact WHERE contact = $userid";
						$rSelect = mysqli_query($conn, $sqlSelect) or die(mysqli_error($conn));
						while($rowSelect = mysqli_fetch_assoc($rSelect)){
							$projectid = $rowSelect['project'];
							$clientid = $rowSelect['client'];

							array_push($projarr, $projectid);
							array_push($cliarr, $clientid);
						}
						
						$arrP = implode(',', $projarr);
						$arrC = implode(',', $cliarr);			

						$sqlFin = "SELECT DISTINCT contact.idcontact, contact.type, contact.firstname, contact.lastname, contact.src, contact.client FROM relatedcontact 
						INNER JOIN contact ON relatedcontact.contact = contact.idcontact WHERE project IN ($arrP) AND relatedcontact.client IN ($arrC) AND contact.idcontact != '$userid'";
						$rFin = mysqli_query($conn, $sqlFin) or die(mysqli_error($conn));
						
						while($rowFin = mysqli_fetch_assoc($rFin)){
							$contactid = $rowFin['idcontact'];
							$type = $rowFin['type'];
							$name = $rowFin['firstname']." ".$rowFin['lastname'];
							$src = $rowFin['src'];
							$client = $rowFin['client'];

							if($src === null){
								$src = "resources/img/icon/avatar.svg";
							}
						
							if($client != null){
								$sqlColor = "SELECT hex FROM client WHERE idclient = $client";
								$rColor = mysqli_query($conn, $sqlColor) or die(mysqli_error($conn));
								$hex = mysqli_fetch_assoc($rColor)['hex'];
								echo "<div id=\"invite-contact\" style=\"border-left:4px solid $hex;\">";
							} elseif($client === 0) {
								echo "<div id=\"invite-contact\" style=\"border-left:4px solid black;\">";
							} else{
								echo "<div id=\"invite-contact\" style=\"border-left:4px solid #E4E4E4;\">";
							}
						
								echo "<a href=\"#\" data-id=\"$contactid\" data-src=\"$src\">
									<div style=\"background-image: url($src);\"></div>
									<p>$name</p>
								</a>
							</div>";
						}

					echo "</div>
				</div>

				<input type=\"submit\" name=\"new-event\" id=\"new-event\" value=\"Create Event\">
			</form>
		</article>
	</section>";
?>