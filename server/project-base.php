<?php
	ini_set("session.save_path", "../../sessionData");
	session_start();
	include "db.php";
	include "../functions.php";

	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
    	echo pageIni("Access Denied");
    	echo "nope";
  	} else {
	
	echo pageIni2("Client - Adscope");
?>

	<body id="back-end-pages">
<?php
	if(isset($_REQUEST['id'])){
		$pid = $_REQUEST['id'];
		$sqlMain = "SELECT * FROM project WHERE project.idproject = '$pid'";
		$rProj = mysqli_query($conn, $sqlMain) or die(mysqli_error($conn));
		while($rowProj = mysqli_fetch_assoc($rProj)){

			$idproject = $rowProj['idproject'];
			$title = $rowProj['title'];
			$client = $rowProj['client'];
			$type = $rowProj['type'];

			$sqlC = "SELECT name, hex FROM client WHERE client.idclient = '$client'";
			$rC = mysqli_query($conn, $sqlC) or die(mysqli_error($conn));
			while($rowC = mysqli_fetch_assoc($rC)){
				$clientname = $rowC['name'];
				$clienthex = $rowC['hex'];

				$titleC = "<span style=\"color:$clienthex\">$clientname</span>";
			}

			$include = $rowProj['include'];
			$notify = $rowProj['notify'];
			$upload = $rowProj['upload'];
			$email = $rowProj['email'];
			$schedule = $rowProj['schedule'];

			$notes = $rowProj['notes'];

			$manager = $rowProj['projectmanager'];
		}
	}
	echo nav2(" id=\"active\"", "", "", "", "", "", "", "");
	echo head2($titleC, $_SESSION['username'], $_SESSION['iduser']);
	
?>
	<div id="back-end-body">
			<form id="base" action="submit-new-project.php" method="post" enctype="multipart/form-data">
			<section id="base-nav">
				<ul>
					<li><a href="#" class="active">Project Details</a></li>
					<li><a href="#">Tasks</a></li>
					<li><a href="#">Contacts</a></li>
					<li><a href="#">Financials</a></li>
					<li><a href="#">Dates</a></li>
					<li><a href="#">Files</a></li>
					<li><a href="#">Notes</a></li>
				</ul>
			</section>
			<section>
				<article id="info" style="display:block;">
					<div>
						<label for="project-title">Project Title</label>
						<?php
							if(!isset($pid) || !$pid || $pid === "" || $pid === null){
								echo "<input type=\"hidden\" id=\"project-id\" name=\"project-id\" value=\"\">
								<input type=\"hidden\" id=\"project-title\" name=\"project-title\" value=\"\">
								<input type=\"text\" id=\"title\" name=\"title\" required>";
							} else {
								echo "<input type=\"hidden\" id=\"project-id\" name=\"project-id\" value=\"$idproject\">
								<input type=\"hidden\" id=\"project-title\" name=\"project-title\" value=\"$title\">
								<input type=\"text\" id=\"title\" name=\"title\" value=\"$title\" required readonly>";
							}
						?>
						
					</div>

					<div>
						<label for="client">Client</label>
						<?php
							if(!isset($pid) || !$pid || $pid === "" || $pid === null){
								echo "<input type=\"hidden\" id=\"client-id\" name=\"client-id\" value=\"\">
								<input type=\"text\" id=\"client\" name=\"client\" required>";
							} else {
								echo "<input type=\"hidden\" id=\"client-id\" name=\"client-id\" value=\"$client\">
								<input type=\"text\" id=\"client\" name=\"client\" value=\"$clientname\" required readonly style=\"color:$clienthex;\">";
							}
						?>
					</div>

					<div>
						<label for="project-manager">Project Manager</label>
						<?php
							if(isset($pid) || $pid || $pid != "" || $pid != null){

								echo "<select name=\"project-manager\" id=\"project-manager\">";
								$sqlCol = "SELECT * FROM user";
								$rCol = mysqli_query($conn, $sqlCol) or die(mysqli_error($conn));
								while($rowCol = mysqli_fetch_assoc($rCol)){
									$iduser = $rowCol['iduser'];
									$colName = $rowCol['firstname']." ".$rowCol['lastname'];
									if($manager != $iduser){
										echo "<option value=\"$iduser\">$colName</option>";
									} else{
										echo "<option value=\"$iduser\" selected>$colName</option>";
									}
									
								}
								echo "</select>";

							}
						?>
					</div>

					<div>
						<label for="project-type">Project Type</label>
						<?php
							if(isset($pid) || $pid || $pid != "" || $pid != null){

								echo "<select name=\"project-type\" id=\"project-type\">";
								$sqlCol = "SELECT * FROM projecttype";
								$rCol = mysqli_query($conn, $sqlCol) or die(mysqli_error($conn));
								while($rowCol = mysqli_fetch_assoc($rCol)){
									$typeid = $rowCol['idprojecttype'];
									$projecttype = $rowCol['projecttype'];
									if($type != $typeid){
										echo "<option value=\"$typeid\">$projecttype</option>";
									} else{
										echo "<option value=\"$typeid\" selected>$projecttype</option>";
									}
									
								}
								echo "</select>";

							}
						?>
					</div>

					<div id="form-checklist">
						<h2>Settings</h2>
						<?php
							if(isset($pid) || $pid || $pid != "" || $pid != null){
								if($include === '0'){
									echo "<label><input type=\"checkbox\" name=\"include\" value=\"1\">Include all client members</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"include\" value=\"1\" checked>Include all client members</label>";
								}
								if($notify === '0'){
									echo "<label><input type=\"checkbox\" name=\"notify\" value=\"1\">Enable all notifications</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"notify\" value=\"1\" checked>Enable all notifications</label>";
								}
								if($upload === '0'){
									echo "<label><input type=\"checkbox\" name=\"upload\" value=\"1\">Allow file uploads</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"upload\" value=\"1\" checked>Allow file uploads</label>";
								}
								if($email === '0'){
									echo "<label><input type=\"checkbox\" name=\"email\" value=\"1\">Automatically send files to all members</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"email\" value=\"1\" checked>Automatically send files to all members</label>";
								}
								if($schedule === '0'){
									echo "<label><input type=\"checkbox\" name=\"schedule\" value=\"1\">Auto-schedule tasks/meetings for included members</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"schedule\" value=\"1\" checked>Auto-schedule tasks/meetings for included members</label>";
								}
							}
						?>
					</div>
				</article>
				<article id="tasks">
					<div id="tasks-div">
						<div>
							<input type="text" name="task" id="task" placeholder="+ Add New Task...">
							<div>
								<label for="notify" id="notify-label">
									<img src="../resources/img/icon/bell-no.svg">
									<input type="radio" name="notify" id="notify" value="1">
								</label>
									<span></span>
								<label for="priority" id="priority-label">
									<img src="../resources/img/icon/star-faded.svg">
									<input type="radio" name="priority" id="priority" value="1">
								</label>
									<span></span>
								<label for="recurring" id="recurring-label">
									<img src="../resources/img/icon/two-circling-arrows-no.svg">
									<input type="radio" name="recurring" id="recurring" value="1">
								</label>

								<a href="#" id="add-task"><img src="../resources/img/icon/add-plus-button-red.svg"></a>
							</div>
						</div>
						<div>
							<ul>
								<?php
								include "view-tasks.php";
								?>
							</ul>
						</div>
					</div>
				</article>
				<article id="contacts">
					<?php	
						if(!isset($_REQUEST['id'])){
							echo "<h4>Please save the project first</h4>";
						} else{
							echo "<div id=\"button\">
							<div><a>+</a></div>
								<select id=\"new-contact-select\">
									<option value=\"view\">View Contacts</option>
									<option value=\"new\">New Contact</option>
									<option value=\"add\">Add Contacts</option>
								</select>
								<ul></ul>
							</div>
							<div id=\"contacts-div\">";
							include "project-view-contact.php";
							echo "</div>";
						}		
					?>
				</article>
				<article id="finances">
						<ul id="divisor">
							<li id="active"><a href="#">Tasks</a></li>
							<li><a href="#">Events</a></li>
						</ul>
						<div id="finance-task">
							<?php
								$ttotalcost = 0;

								$sqlFinT = "SELECT task, project.title, done, cost FROM task INNER JOIN project ON task.project = project.idproject WHERE task.project = '$pid'";
								$rFinT = mysqli_query($conn, $sqlFinT) or die(mysqli_error($conn));
								while($rowFT = mysqli_fetch_assoc($rFinT)){
									$ftaskname = $rowFT['task'];
									$fttitle = $rowFT['title'];
									$ftdone = $rowFT['done'];
									if($ftdone === "1"){
										$ftstatus = "Completed";
									} else {
										$ftstatus = "Active";
									}

									$ftcost = $rowFT['cost'];
									$ttotalcost += $ftcost;
									$ttotalcost = round($ttotalcost, 2);
									echo "<ul>
										<li>$ftaskname</li>
										<li>$fttitle</li>
										<li>$ftstatus</li>
										<li>$ftcost</li>
									</ul>";
								}
								echo "<ul>
									<li><br /></li>
									<li><br /></li>
									<li>Total Cost:</li>
									<li>&pound;$ttotalcost</li>
								</ul>";
							?>
						</div>
						<div id="finance-events">
							<?php
								$etotalcost = 0;

								$sqlFinE = "SELECT event, project.title, startdate, cost FROM event INNER JOIN project ON event.project = project.idproject WHERE event.project = '$pid'";
								$rFinE = mysqli_query($conn, $sqlFinE) or die(mysqli_error($conn));
								while($rowFE = mysqli_fetch_assoc($rFinE)){
									$feventname = $rowFE['event'];
									$fttitle = $rowFE['title'];
									$festart = $rowFE['startdate'];

									$ftcost = $rowFE['cost'];
									$etotalcost += $ftcost;
									$etotalcost = round($etotalcost, 2);

									echo "<ul>
										<li>$feventname</li>
										<li>$fttitle</li>
										<li>$festart</li>
										<li>$ftcost</li>
									</ul>";
								}
								echo "<ul>
									<li><br /></li>
									<li><br /></li>
									<li>Total Cost:</li>
									<li>&pound;$etotalcost</li>
								</ul>";
							?>
						</div>
				</article>
				<article id="dates">
					<div id="dates-div">
						<div>
							<input type="text" name="add-date" id="add-date" placeholder="+ Add New Date...">
							<label for="periodic"><input type="checkbox" name="periodic" id="periodic"> Periodic</label>
							<label for="from"><input type="date" id="from" name="from"></label>
							<label for="to"><input type="date" id="to" name="to"></label>
							<div>
								<label for="notify" id="notify-label">
									<img src="../resources/img/icon/bell-no.svg">
									<input type="radio" name="notify" id="notify" value="1">
								</label>
									<span></span>
								<label for="priority" id="priority-label">
									<img src="../resources/img/icon/star-faded.svg">
									<input type="radio" name="priority" id="priority" value="1">
								</label>

								<a href="#" id="add-dates"><img src="../resources/img/icon/add-plus-button-red.svg"></a>
							</div>
						</div>
						<div>
							<ul>
								<li>
									<a href="#">Make Markers</a>
									<label for="periodic"><input type="checkbox" name="periodic" id="periodic"> Periodic</label>
									<label for="from"><input type="date" id="from" name="from"></label>
									<label for="to"><input type="date" id="to" name="to"></label>
									<p>
										<a href="#"><img src="../resources/img/icon/bell.svg"></a>
										<span></span>
										<a href="#"><img src="../resources/img/icon/star.svg"></a>

										<a href="#" id="cancel-date"><img src="../resources/img/icon/cancel.svg"></a>
									</p>
								</li>
							</ul>
						</div>
					</div>
				</article>
				<article id="files">
						<div id="file-list">
							<?php
								if(isset($_REQUEST['id'])){
									$pfid = $_REQUEST['id'];
									include "project-file-list.php";
									echo filelist($pfid);
								}
							?>
						</div>
						<div>
							<label for="file-subject">Add New File</label>
							<input type="text" id="file-subject" name="file-subject" placeholder="File Description">
							<input type="file" id="project-file" name="project-file[]" multiple>

							<label for="file-description">File Information</label>
							<textarea name="file-description" id="file-description"></textarea>
							<a href="#" id="add-file">Add File</a>
						</div>
				</article>
				<article id="notes">
					<div>
						<div class="text-editor-bar">
							<div class="text-wrapper">Text Types
								<ul>
							  		<li><a href="#" data-command="h1"><h1>Header 1</h1></a></li>
							  		<li><a href="#" data-command="h2"><h2>Header 2</h2></a></li>
							  		<li><a href="#" data-command="h3"><h3>Header 3</h3></a></li>
							  		<li><a href="#" data-command="h4"><h4>Header 4</h4></a></li>
							  		<li><a href="#" data-command="p"><p>Normal Text</p></a></li>
							  	</ul>
							</div>
							
							<div class="font-wrapper"><i class='fa fa-text-height'></i>
							  	<ul>
							  		<li><a href="#" data-command="fontSize" data-value="1"><font size="1">X-Small</font></a></li>
							  		<li><a href="#" data-command="fontSize" data-value="2"><font size="1">Small</font></a></li>
							  		<li><a href="#" data-command="fontSize" data-value="3"><font size="3">Normal</font></a></li>
							  		<li><a href="#" data-command="fontSize" data-value="4"><font size="4">Large</font></a></li>
							  		<li><a href="#" data-command="fontSize" data-value="5"><font size="5">X-Large</font></a></li>
							  		<li><a href="#" data-command="fontSize" data-value="6"><font size="6">XX-Large</font></a></li>
							  		<li><a href="#" data-command="fontSize" data-value="7"><font size="7">Largest</font></a></li>
							  	</ul>
							</div>
							
							<div class="editor-break"></div>
							  
							<div class="fore-wrapper"><i class='fa fa-font'></i>
							   	<div class="fore-palette">
							   	</div>
							</div>
								<a href="#" data-command="bold"><i class="fa fa-bold"></i></a>
								<a href="#" data-command="italic"><i class="fa fa-italic"></i></a>
								<a href="#" data-command="underline"><i class="fa fa-underline"></i></a>
								<a href="#" data-command="strikeThrough"><i class="fa fa-strikethrough"></i></a>
								<a href="#" data-command="blockquote"><i class="fa fa-quote-right"></i></a>
							
							<div class="editor-break"></div>
							
								<a href="#" data-command="justifyLeft"><i class="fa fa-align-left"></i></a>
								<a href="#" data-command="justifyCenter"><i class="fa fa-align-center"></i></a>
								<a href="#" data-command="justifyRight"><i class="fa fa-align-right"></i></a>
								<a href="#" data-command="justifyFull"><i class="fa fa-align-justify"></i></a>
							
							<div class="editor-break"></div>
				
								<a href="#" data-command="insertOrderedList"><i class="fa fa-list-ol"></i></a>
								<a href="#" data-command="insertUnorderedList"><i class="fa fa-list-ul"></i></a>
								<a href="#" data-command="indent"><i class="fa fa-indent"></i></a>
								<a href="#" data-command="outdent"><i class="fa fa-outdent"></i></a>
							
							<div class="editor-break"></div>
								<a href="#" data-command="insertImage"><i class="fa fa-image"></i></a>
							</div>
						<div id="project-notes" class="notes texteditor" contenteditable>
							<?php
								if(!isset($pid) || !$pid || $pid === "" || $pid === null){
									echo "";
								} else {
									echo $notes;
								}
							?>
						</div>
						<textarea name="notes" id="note-submit"></textarea>
						<div class="image-list">
	
						</div> 
					</div>
				</article>
				<input id="float-submit" type="image" src="../resources/img/icon/save-white.svg">
			</section>
		</form>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="js/project-base.js"></script>
	<script src="js/texteditor.js"></script>
	<script src="js/general-2.js"></script>	
<?php
	echo pageClose();
	}
?>