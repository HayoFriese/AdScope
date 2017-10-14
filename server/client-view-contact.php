<?php
	include "db.php";
	echo "<div id=\"view-contacts\">";
								$contid = isset($_REQUEST['id']) ? $_REQUEST['id']:null;
								
								$sqlcontact = "SELECT * FROM relatedcontact
								INNER JOIN contact ON relatedcontact.contact = contact.idcontact
								WHERE relatedcontact.client = $contid";

								$rcontact = mysqli_query($conn, $sqlcontact) or die(mysqli_error($conn));
								if(mysqli_num_rows($rcontact) > 0){
									while($row = mysqli_fetch_assoc($rcontact)){
	
										$idcontact = $row['idcontact'];
										$type = $row['type'];
										$name = $row['firstname']." ".$row['lastname'];
										$position = $row['position'];
										$email = $row['email'];
										$phone = $row['phone'];
										$src = "../".$row['src'];
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
												<li><a href=\"#\"><img src=\"../resources/img/icon/more.svg\"></a>
													<ul>
														<li id=\"toggle-view\"><a href=\"#\" data-contact-id=\"$idcontact\">View Contact</a></li>";
														if($type === "Colleague"){
															echo "<li id=\"toggle-agenda\"><a href=\"#\" data-contact-id=\"$idcontact\">View Agenda</a></li>
															<li id=\"toggle-remove\"><a href=\"#\" data-contact-id=\"$idcontact\">Remove Contact</a></li>";
														}else if($type === "Other"){
															echo "<li id=\"toggle-remove\"><a href=\"#\" data-contact-id=\"$idcontact\">Remove Contact</a></li>";
														}
														echo "<li id=\"toggle-message\"><a href=\"#\" data-contact-id=\"$idcontact\">Message</a></li>
													</ul>
												</li>
											</ul>
											<div id=\"box-details\">";
												if($src === "" || $src === "../"){
													if($type === "Colleague"){
														echo "<div style=\"background-image:url('../resources/img/icon/avatar.svg'); background-size:60%; background-color:#E4E4E4; opacity:0.2;\">";
													} elseif($type === "Client"){
														echo "<div style=\"background-image:url('../resources/img/icon/avatar.svg'); background-size:60%; background-color:$hex; opacity:0.2;\">";
													} elseif($type === "Other"){
														echo "<div style=\"background-image:url('../resources/img/icon/avatar.svg'); background-size:60%; background-color:black; opacity:0.2;\">";
													}
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
								} else {
									echo "<h4>No Contacts Added! Why not add some? Select \"Add Contacts\" to attach existing ones to this client, or select \"New Contact\" to add a new one for this client.</h4>";
								}
							echo "</div>";
?>