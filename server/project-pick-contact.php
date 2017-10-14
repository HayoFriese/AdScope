<?php
	include "db.php";
	$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid']:null;
	$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid']:null;

				if($cid != "" || isset($cid) || $cid != null){
							echo " <div id=\"adding-contact\">
								<input type=\"hidden\" id=\"relatedcontact\" name=\"relatedcontact\">
							</div>
							<div id=\"select-contact\">";

								$sqlFirst = "SELECT * FROM contact 
									WHERE NOT EXISTS (SELECT 1 FROM relatedcontact WHERE contact.idcontact = relatedcontact.contact AND relatedcontact.project = $pid) 
									AND (contact.client IS NULL OR contact.client = 0 OR contact.client = $cid)";
								$rFirst = mysqli_query($conn, $sqlFirst) or die(mysqli_error($conn));
								if(mysqli_num_rows($rFirst) != 0){
									$sqlcontact = "SELECT * FROM contact WHERE contact.client IS NULL OR contact.client = 0 OR (contact.client = $cid AND NOT EXISTS
										(SELECT 1 FROM relatedcontact WHERE contact.idcontact = relatedcontact.contact AND relatedcontact.project = $pid))";
									$rcontact = mysqli_query($conn, $sqlcontact) or die(mysqli_error($conn));
									while($row = mysqli_fetch_assoc($rcontact)){
										$idcontact = $row['idcontact'];
										$type = $row['type'];
										$name = $row['firstname']." ".$row['lastname'];
										$position = $row['position'];
										$email = $row['email'];
										$phone = $row['phone'];
										$src = "../".$row['src'];
										$client = $row['client'];
	
										$sqlcheck = "SELECT * FROM relatedcontact WHERE relatedcontact.contact = $idcontact AND relatedcontact.project = $cid";
										$rcheck = mysqli_query($conn, $sqlcheck) or die(mysqli_error($conn));
										
										if(mysqli_num_rows($rcheck) === 0){
	
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
														<ul>";
															if($src === ""){
																echo "<li id=\"toggle-add\"><a href=\"#\" data-contact-id=\"$idcontact\" data-src=\"../resources/img/icon/avatar.svg\">Add Contact</a></li>";
															} else {
																echo "<li id=\"toggle-add\"><a href=\"#\" data-contact-id=\"$idcontact\" data-src=\"$src\">Add Contact</a></li>";
															}
															echo "<li id=\"toggle-view\"><a href=\"#\" data-contact-id=\"$idcontact\">View Contact</a></li>
														</ul>
													</li>
												</ul>
												<div id=\"box-details\">";
					
													if($src === ""){
														echo "<div style=\"background-image:url('../resources/img/icon/avatar.svg'); background-size:60%;\">";
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
									}
								} else {
									echo "<h4>No Applicable Contact Available</h4>";
								}
							echo "</div>";
						} else {
							echo "<h4>Please save the client first</h4>";
						}
						
?>