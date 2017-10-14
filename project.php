<?php
	ini_set("session.save_path", "../sessionData");
	session_start();
  include "server/db.php";
	include "functions.php";
	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
	    header("Location: signin.php");
  	} else{	
	echo pageIni("Project List - Adscope");
?>
	<body id="back-end-pages">
<?php
	echo nav(" id=\"active\"", "", "", "", "", "", "", "");
	echo head("Projects", $_SESSION['username'], $_SESSION['iduser']);
?>
	<div id="back-end-body" class="projects">
		<section id="projects-body">
			<article id="clients">
				<ul id="divisor">
					<li id="active"><a href="#">Clients</a></li>
					<li><a href="#">Labels</a></li>
					<li><a href="#">Filters</a></li>
				</ul>
				<div id="table">
					<div id="tablehead">
						<ul>
							<li>ID <img src="resources/img/icon/dropdown.png"></li>
							<li>Client Name <img src="resources/img/icon/dropdown.png"></li>
							<li></li>
  						</ul>
					</div>
  					<div id="tablebody">
              <?php
                $sqlClient = "SELECT idclient, name, hex FROM client";
                $rClient = mysqli_query($conn, $sqlClient) or die(mysqli_error($conn));
                while($row = mysqli_fetch_assoc($rClient)){
                  $idclient = $row['idclient'];
                  $client = $row['name'];
                  $clienthex = $row['hex'];

                  echo "<ul>
                    <li><div style=\"background-color:$clienthex;\"></div></li>
                    <li><a href=\"server/client.php?id=$idclient\">$client</a></li>
                    <li><a href=\"server/client.php?id=$idclient\"><img src=\"resources/img/icon/more.svg\"></a></li>
                  </ul>";
                }
              ?>
    				</div>
				</div>
				<form>
					<label for="show-past">Show Past Clients
						<input type="checkbox" name="show-past" id="show-past">
					</label>
				</form>
			</article>
			<article id="projects">
				<ul id="divisor">
					<li id="active"><a href="#">Projects</a></li>
					<li><a href="#">Tasks</a></li>
				</ul>
				<form>
					<div><a>+</a></div>
					<select id="project-new">
						<option value="">New...</option>
						<option value="project">New Project</option>
						<option value="client">New Client</option>
					</select>
					<ul></ul>
				</form>
				<div id="table">
					<div id="tablehead">
						<ul>
							<li>ID <img src="resources/img/icon/dropdown.png"></li>
							<li>Project Title <img src="resources/img/icon/dropdown.png"></li>
							<li>Project Manager <img src="resources/img/icon/dropdown.png"></li>
							<li># Incomplete Tasks <img src="resources/img/icon/dropdown.png"></li>
							<li>Next Deadline <img src="resources/img/icon/dropdown.png"></li>
							<li>Client <img src="resources/img/icon/dropdown.png"></li>
  						</ul>
					</div>
  					<div id="tablebody">
              <?php
                $sqlProject = "SELECT idproject, id, title, client.name, client.hex, user.firstname, user.lastname, incompletetask 
                FROM project
                INNER JOIN client ON project.client = client.idclient
                INNER JOIN user ON project.projectmanager = user.iduser
                ORDER BY project.started DESC";

                $rProject = mysqli_query($conn, $sqlProject) or die(mysqli_error($conn));
                while($row = mysqli_fetch_assoc($rProject)){
                  $pid = $row['idproject'];
                  $idproject = $row['id'];
                  $title = $row['title'];
                  $client = $row['name'];
                  $clienthex = $row['hex'];
                  $manager = $row['firstname']." ".$row['lastname'];
                  $incomplete = $row['incompletetask'];

                  echo "<ul data-project-id=\"$pid\">
                    <li>$idproject</li>
                    <li>$title</li>
                    <li>$manager</li>
                    <li>($incomplete)</li>
                    <li>September 5</li>
                    <li><span style=\"color:$clienthex;\">$client</span></li>
                  </ul>";
                }
              ?>
    				</div>
				</div>
			</article>
		</section>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="server/js/projects.js"></script>
	<script src="server/js/general.js"></script>
<?php
	echo pageClose();
	}
?>