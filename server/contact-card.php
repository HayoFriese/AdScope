<?php
	include "db.php";
	if(isset($_POST['id'])){
		$idcontact = $_POST['id'];
		$sqlView = "SELECT * FROM contact WHERE contact.idcontact = '$idcontact'";
		$rView = mysqli_query($conn, $sqlView) or die(mysqli_error($conn));
		while($rwview = mysqli_fetch_assoc($rView)){
			$idcontact = $rwview['idcontact'];
			$type = $rwview['type'];
			$name = $rwview['firstname']." ".$rwview['lastname'];
			$position = $rwview['position'];
			$email = $rwview['email'];
			$phone = $rwview['phone'];
			$src = $rwview['src'];

			$adline1 = $rwview['adline1'];
			$adline2 = $rwview['adline2'];
			$postcode = $rwview['postcode'];
			$city = $rwview['city'];
			$country = $rwview['country'];

			$client = $rwview['client'];

			if($client != null || $client != "" || $client != "undefined" || !$client){
				$sqlCol = "SELECT hex FROM client WHERE client.idclient = '$client'";
				$rCol = mysqli_query($conn, $sqlCol) or die(mysqli_error($conn));

				$color = mysqli_fetch_assoc($rCol)['hex'];
			}
		}
		echo "<section id=\"address-view\">
			<article>";
				if($type === "Colleague"){
					echo "<div style=\"border-left: 30px solid #E4E4E4;\">";
				} elseif($type === "Client"){
					echo "<div style=\"border-left: 30px solid $color;\">";
				} elseif($type === "Other"){
					echo "<div style=\"border-left: 30px solid black;\">";
				}
				echo "<div>
						<a id=\"close-card\" href=\"#\"><img src=\"resources/img/icon/cancel.svg\"></a>
						<ul>
							<li><img src=\"resources/img/icon/more.svg\">
								<ul>
									<li><a href=\"#\">Manage Details</a></li>";
									if($type==="Colleague"){
										echo "<li><a href=\"#\">View Agenda</a></li>";
									}
								echo "</ul>
							</li>
						</ul>
					</div>
					<div>
						<div>
							<div>";
								if(!isset($src) || $src ==="" || $src === null){
									if($type === "Colleague"){
										echo "<div style=\"background-image:url(resources/img/icon/avatar.svg); background-size:60%; background-color:#E4E4E4; opacity:0.2;\"></div>";
									} elseif($type === "Client"){
										echo "<div style=\"background-image:url(resources/img/icon/avatar.svg); background-size:60%; background-color:$color; opacity:0.2;\"></div>";
									} elseif($type === "Other"){
										echo "<div style=\"background-image:url(resources/img/icon/avatar.svg); background-size:60%; background-color:black; opacity:0.2;\"></div>";
									}
								} else{
									echo "<div style=\"background-image:url('$src'); background-size:cover;\">";
									if($type === "Colleague"){
										$sqlViewStatus = "SELECT status FROM user WHERE user.email = '$email'";
										$rViewStatus = mysqli_query($conn, $sqlViewStatus) or die(mysqli_error($conn));
									
										while($status = mysqli_fetch_assoc($rViewStatus)['status']){
											if($status == "Online"){
												echo "<div id=\"view-status\" style=\"background-color:#88D54F;\"></div>";
											} else if($status == "Busy"){
												echo "<div id=\"view-status\" style=\"background-color:#A80F0F;\"></div>";
											} else if($status == "Out of Office"){
												echo "<div id=\"view-status\" style=\"background-color:gray;\"></div>";
											} else if($status == "Away"){
												echo "<div id=\"view-status\" style=\"background-color:#FACF00;\"></div>";
											} else if($status == "Do Not Disturb"){
												echo "<div id=\"view-status\" style=\"background-color:#FF0000;\"></div>";
											} else if($status == "Offline"){
												echo "<div id=\"view-status\" style=\"background-color:white; border:1px solid rgba(0, 0, 0, 0.4);\"></div>";
											}
										}
									}
									echo "</div>";
								}
								echo "<h1>$name</h1>
								<h2>$position</h2>
								<p>$email</p>
								<p>$phone</p>
							</div>
							<div></div>
							<ul id=\"address-card\">
								<h3>Address</h3>
								<li>$adline1</li>
								<li>$adline2</li>
								<li>$postcode</li>
								<li>$city</li>
								<li>$country</li>
							</ul>
							<div></div>
							<ul>
								<h3>Active Projects</h3>
								<li>
									<h4><a href=\"#\">You Unite Others - World Unity Day Campaign</a></h4>
									<p>Jennifer Garner</p>
									<p><span>FB-408-12315</span></p>
								</li>
								<li>
									<h4><a href=\"#\">You Unite Others - World Unity Day Campaign</a></h4>
									<p>Project Manager</p>
									<p><span>Project ID</span></p>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</article>
		</section>
		<script>
			$(\"#close-card\").on(\"click\", function(){
				$(\"#address-view\").remove();
			});
		</script>";
	}
?>