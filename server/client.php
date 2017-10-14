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
		$cid = $_REQUEST['id'];

		$sqlMain = "SELECT * FROM client WHERE client.idclient = $cid";
		$rClient = mysqli_query($conn, $sqlMain) or die(mysqli_error($conn));
		while($rowClient = mysqli_fetch_assoc($rClient)){
			$idclient = $rowClient['idclient'];
			$name = $rowClient['name'];
			$website = $rowClient['website'];
			$hex = $rowClient['hex'];

			$clientCol = "<span style=\"color:$hex\">$name</span>";

			$view = $rowClient['view'];
			$add = $rowClient['add'];
			$attach = $rowClient['attach'];

			$notes = $rowClient['notes'];
		}
	}
	echo nav2(" id=\"active\"", "", "", "", "", "", "", "");
	echo head2($clientCol, $_SESSION['username'], $_SESSION['iduser']);

	
?>
	<div id="back-end-body">
		<form id="base" action="submit-new-client.php" method="post" enctype="multipart/form-data">
			<section id="base-nav">
				<ul>
					<li><a href="#" class="active">Client Information</a></li>
					<li><a href="#">Contacts</a></li>
					<li><a href="#">Financial Details</a></li>
					<li><a href="#">Files</a></li>
					<li><a href="#">Notes</a></li>
				</ul>
			</section>
			<section>
				<article id="info" style="display:block;">
					<div>
						<label for="client-name">Client Name</label>
						<?php
							if(!isset($cid) || !$cid || $cid === "" || $cid === null){
								echo "<input type=\"hidden\" id=\"client-id\" name=\"client-id\" value=\"\">
								<input type=\"text\" id=\"client-name\" name=\"client-name\" required>";
							} else {
								echo "<input type=\"hidden\" id=\"client-id\" name=\"client-id\" value=\"$idclient\">
								<input type=\"text\" id=\"client-name\" name=\"client-name\" value=\"$name\" required>";
							}
						?>
						
					</div>

					<div>
						<label for="website">Main Website</label>
						<?php
							if(!isset($cid) || !$cid || $cid === "" || $cid === null){
								echo "<input type=\"text\" id=\"website\" name=\"website\" required>";
							} else {
								echo "<input type=\"text\" id=\"website\" name=\"website\" value=\"$website\" required>";
							}
						?>
					</div>

					<div>
						<label for="color">Color</label>
						<?php
							if(!isset($cid) || !$cid || $cid === "" || $cid === null){
								echo "<input type=\"text\" maxlength=\"6\" size=\"6\" id=\"color\" name=\"color\" required>";
							} else {
								echo "<input type=\"text\" maxlength=\"6\" size=\"6\" id=\"color\" name=\"color\" value=\"$hex\" required>";
							}
						?>
					</div>

					<div id="form-checklist">
						<h2>Permissions</h2>
						<?php
							if(!isset($cid) || !$cid || $cid === "" || $cid === null){
								echo "<label><input type=\"checkbox\" name=\"view\" value=\"1\">Only selected people can view this client and their contacts</label>
								<label><input type=\"checkbox\" name=\"add\" value=\"1\">Only selected people can add contacts to this client</label>
								<label><input type=\"checkbox\" name=\"attach\" value=\"1\">Only selected people can attach clients to projects</label>";
							} else {
								if((!isset($cid) || !$cid || $cid === "" || $cid === null) && $view === 0){
									echo "<label><input type=\"checkbox\" name=\"view\" value=\"1\">Only selected people can view this client and their contacts</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"view\" value=\"1\" checked>Only selected people can view this client and their contacts</label>";
								}
								if((!isset($cid) || !$cid || $cid === "" || $cid === null) && $add === 0){
									echo "<label><input type=\"checkbox\" name=\"add\" value=\"1\">Only selected people can add contacts to this client</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"add\" value=\"1\" checked>Only selected people can add contacts to this client</label>";
								}
								if((!isset($cid) || !$cid || $cid === "" || $cid === null) && $attach === 0){
									echo "<label><input type=\"checkbox\" name=\"attach\" value=\"1\">Only selected people can attach clients to projects</label>";
								} else{
									echo "<label><input type=\"checkbox\" name=\"attach\" value=\"1\" checked>Only selected people can attach clients to projects</label>";
								}
							}
						?>
					</div>
				</article>
				<article id="contacts">
					<?php	
						if(!isset($_REQUEST['id'])){
							echo "<h4>Please save the client first</h4>";
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
							include "client-view-contact.php";
							echo "</div>";
						}		
					?>
				</article>
				<article id="finances" class="client-finances">
						<ul id="divisor">
							<li id="active"><a href="#">Projects</a></li>
							<li><a href="#">Tasks</a></li>
							<li><a href="#">Events</a></li>
						</ul>
						<div id="finance-projects">
							<?php
								$ptotalcost = 0;

								$sqlFinP = "SELECT title, projecttype.projecttype, started, totalcost, user.firstname, user.lastname
								FROM project 
								INNER JOIN projecttype ON project.type = projecttype.idprojecttype
								INNER JOIN user ON project.projectmanager = user.iduser";
								$rFinP = mysqli_query($conn, $sqlFinP) or die(mysqli_error($conn));
								while($rowFP = mysqli_fetch_assoc($rFinP)){
									$fptitle = $rowFP['title'];
									$fptype = $rowFP['projecttype'];
									$fpstart = date("Y-m-d", strtotime($rowFP['started']));
									$fpproman = $rowFP['firstname']." ".$rowFP['lastname'];

									$fpcost = round($rowFP['totalcost'], 2);
									$ptotalcost += $fpcost;
									$ptotalcost = round($ptotalcost, 2);
									echo "<ul>
										<li>$fptitle</li>
										<li>$fptype</li>
										<li>$fpstart</li>
										<li>$fpproman</li>
										<li>$fpcost</li>
									</ul>";
								}
								echo "<ul>
									<li><br /></li>
									<li><br /></li>
									<li><br /></li>
									<li>Total Cost:</li>
									<li>&pound;$ptotalcost</li>
								</ul>";
							?>
						</div>
						<div id="finance-tasks">
							<?php
								$ttotalcost = 0;

								$sqlFinT = "SELECT task, project.title, done, cost FROM task INNER JOIN project ON task.project = project.idproject WHERE project.client = '$cid'";
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

								$sqlFinE = "SELECT event, project.title, startdate, cost FROM event INNER JOIN project ON event.project = project.idproject WHERE project.client = '$cid'";
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
				</article>
				<article id="files">
						<div id="file-list">
							<?php
							if(isset($_REQUEST['id'])){
								$cfid = $_REQUEST['id'];
								include "client-file-list.php";
								echo filelist($cfid);
							}
							?>
						</div>
						<div>
							<label for="file-subject">Add New File</label>
							<input type="text" id="file-subject" name="file-subject" placeholder="File Description">
							<input type="file" id="client-file" name="client-file[]" multiple>

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
						<div id="client-notes" class="notes texteditor" contenteditable>
							<?php
								if(!isset($cid) || !$cid || $cid === "" || $cid === null){
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
	<script type="text/javascript" src="js/colorpicker.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
	<script src="js/client.js"></script>
	<script src="js/texteditor.js"></script>
	<script src="js/general-2.js"></script>	
<?php
	echo pageClose();
	}
?>