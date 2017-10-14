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
	echo nav("", "", "", "", "", "", " id=\"active\"", "");
	echo head("Address Book", $_SESSION['username'], $_SESSION['iduser']);
?>
	<div id="back-end-body">
		<section id="address-filter">
			<article>
				<h1>GENERAL</h1>
				<ul>
				<?php
					$sql = "SELECT idcontact FROM contact";
					$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
						$allnum = mysqli_num_rows($r);
						echo "<li>
								<a href=\"#\" id=\"active\">
									<img src=\"resources/img/icon/avatar-red.svg\">
									<p>All Contacts <span>$allnum</span></p>
								</a>
							</li>";

					$sql2 = "SELECT idcontact FROM contact WHERE contact.type='Colleague'";
						$r2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
						$colleaguenum = mysqli_num_rows($r2);
						echo "<li>
								<a href=\"#\">
									<img src=\"resources/img/icon/avatar.svg\">
									<p>Colleagues <span>$colleaguenum</span></p>
								</a>
							</li>";

					$sql3 = "SELECT idcontact FROM contact WHERE contact.type='Other'";
						$r3 = mysqli_query($conn, $sql3) or die(mysqli_error($conn));
						$colleaguenum = mysqli_num_rows($r3);
						echo "<li>
								<a href=\"#\">
									<img src=\"resources/img/icon/avatar.svg\">
									<p>Others <span>$colleaguenum</span></p>
								</a>
							</li>";
				?>
			</article>
			<article>
				<?php
					$sqlc = "SELECT idclient, name, hex, view, contacts FROM client";
					$rc = mysqli_query($conn, $sqlc) or die(mysqli_error($conn));
					if(mysqli_num_rows($rc) > 0){
						echo "<h1>CLIENTS</h1>
						<ul>";
						while ($rwc = mysqli_fetch_assoc($rc)) {
							$cname = $rwc['name'];
							$cid = $rwc['idclient'];
							$chex = $rwc['hex'];
							$ccontacts = $rwc['contacts'];
							echo "<li>
								<a href=\"#\" data-id=\"#cid\">
									<img src=\"resources/img/icon/avatar.svg\">
									<p>$cname <span>$ccontacts</span></p>
								</a>
							</li>";
						}
						echo "</ul>";
					}
				?>
			</article>
			<article>

			</article>
		</section>
		<section id="address-body">
			<article>
				<input type="text" placeholder="Search..." id="address-search">
				<form>
					<div><a>+</a></div>
					<select id="new-contact-select">
						<option>New...</option>
						<option value="client">Client Contact</option>
						<option value="other">Other Contact</option>
					</select>
					<ul></ul>
				</form>
			</article>
			<article>
				<?php
					$sqlcontact = "SELECT * FROM contact";
					$rcontact = mysqli_query($conn, $sqlcontact) or die(mysqli_error($conn));
					while($row = mysqli_fetch_assoc($rcontact)){
						$idcontact = $row['idcontact'];
						$type = $row['type'];
						$name = $row['firstname']." ".$row['lastname'];
						$position = $row['position'];
						$email = $row['email'];
						$phone = $row['phone'];
						$src = $row['src'];
						$client = $row['client'];

						echo "<div id=\"contact-box\">";

						if($client === "" || $client === null || $client === "undefined"){
							echo "<div style=\"background-color:#E4E4E4;\"><input type=\"hidden\" name=\"contact-id\" value=\"$idcontact\"></div>";
						} elseif($client === "0" && $type === "Other"){
							echo "<div style=\"background-color:black;\"><input type=\"hidden\" name=\"contact-id\" value=\"$idcontact\"></div>";
						} 
						else {
							$sqlHex = "SELECT hex FROM client WHERE client.idclient='$client'";
							$rHex = mysqli_query($conn, $sqlHex) or die(mysqli_error($conn));
							$hex = mysqli_fetch_assoc($rHex)['hex'];
							echo "<div style=\"background-color: $hex;\"><input type=\"hidden\" name=\"contact-id\" value=\"$idcontact\"></div>";
						}
							echo "<ul>
								<li><a href=\"#\"><img src=\"resources/img/icon/more.svg\"></a>
									<ul>
										<li id=\"toggle-view\"><a href=\"#\" data-contact-id=\"$idcontact\">View Contact</a></li>
										<li><a href=\"#\">Manage Details</a></li>";
										if($type === "Colleague"){
											echo "<li><a href=\"#\">View Agenda</a></li>";
										}
									echo "</ul>
								</li>
							</ul>
							<div id=\"box-details\">";

								if($src === ""){
									echo "<div style=\"background-image:url('resources/img/icon/avatar.svg'); background-size:60%;\">";
								} else{
									echo "<div style=\"background-image:url('$src');\">";
								}
								
								if($type === "Colleague"){
									$sqlStatus = "SELECT status FROM user WHERE user.email = '$email'";
									$rStatus = mysqli_query($conn, $sqlStatus) or die(mysqli_error($conn));
									
									while($status = mysqli_fetch_assoc($rStatus)['status']){
										if($status == "Online"){
											echo "<div id=\"colleague-status\" style=\"background-color:#88D54F;\"></div>";
										} else if($status == "Busy"){
											echo "<div id=\"colleague-status\" style=\"background-color:#A80F0F;\"></div>";
										} else if($status == "Out of Office"){
											echo "<div id=\"colleague-status\" style=\"background-color:gray;\"></div>";
										} else if($status == "Away"){
											echo "<div id=\"colleague-status\" style=\"background-color:#FACF00;\"></div>";
										} else if($status == "Do Not Disturb"){
											echo "<div id=\"colleague-status\" style=\"background-color:#FF0000;\"></div>";
										} else if($status == "Offline"){
											echo "<div id=\"colleague-status\" style=\"background-color:white; border:1px solid rgba(0, 0, 0, 0.4);\"></div>";
										}
									}
								}
							echo "</div>
								<div>
									<h1>$name</h1>
									<h2>$position</h2>
									<ul>
										<li>$email</li>
										<li>$phone</li>
									</ul>
								</div>
							</div>
						</div>";
					}
				?>
			</article>
		</section>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="server/js/address-book.js"></script>
	<script src="server/js/general.js"></script>
<?php
	echo pageClose();
	}
?>