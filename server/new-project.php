<?php
	include "db.php";
	if(isset($_REQUEST['id'])){
		$myid = $_REQUEST['id'];
		echo "<section id=\"add-new\">
			<article>
				<form method=\"post\" action=\"server/create-new-project.php\">
					<a id=\"close-create\" href=\"#\"><img src=\"resources/img/icon/cancel.svg\"></a>
					<h1>Create New Project</h1>
					<div>
						<label for=\"project-title\">Project Title</label>
						<input type=\"text\" id=\"project-title\" name=\"project-title\" required>	
						<input type=\"hidden\" name=\"project-manager\" value=\"$myid\"/>		
					</div>
					<div>
						<label for=\"client\">Client</label>";
							$sql = "SELECT idclient, name FROM client";
							$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
							if(mysqli_num_rows($r) > 0){
								echo "<select id=\"client\" name=\"client\" required>";
								while($row = mysqli_fetch_assoc($r)){
									$idclient = $row['idclient'];
									$client = $row['name'];
	
									echo "<option value=\"$idclient\">$client</option>";
								}
								echo "</select>";
							} else {
								echo "<p>There are no clients available!</p>";
							}
					echo "</div>
					<div>
						<label for=\"project-type\">Type</label>
						<select id=\"project-type\" name=\"project-type\">";
							$sqltype = "SELECT * FROM projecttype";
							$rtype = mysqli_query($conn, $sqltype) or die(mysqli_error($conn));
							while($rowtype = mysqli_fetch_assoc($rtype)){
								$idtype = $rowtype['idprojecttype'];
								$typename = $rowtype['projecttype'];

								echo "<option value=\"$idtype\">$typename</option>";
							}
						echo "</select>
					</div>
	
					<div id=\"form-checklist\">
						<h2>Permissions</h2>
						<label><input type=\"checkbox\" name=\"include\" value=\"1\">Include all client members</label>
						<label><input type=\"checkbox\" name=\"notify\" value=\"1\">Enable all notifications</label>
						<label><input type=\"checkbox\" name=\"upload\" value=\"1\">Allow file uploads</label>
						<label><input type=\"checkbox\" name=\"email\" value=\"1\">Automatically send files to all members</label>
						<label><input type=\"checkbox\" name=\"schedule\" value=\"1\">Auto-schedule tasks/meetings for included members</label>
					</div>
	
					<input type=\"submit\" name=\"new-project\" id=\"new-project\" value=\"Create Project\">
				</form>
			</article>
		</section>";
	}
		
?>