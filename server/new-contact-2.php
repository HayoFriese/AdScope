<?php
	include "db.php";
	if(isset($_REQUEST['contact'])){
		echo "<section id=\"address-new\">
			<article>
				<form action=\"new-contact-submit-2.php\" method=\"post\" enctype=\"multipart/form-data\">
					<div>
						<a href=\"#\" id=\"close-new\"><img src=\"../resources/img/icon/cancel.svg\"></a>
						<h3>Contact Image</h3>
						<div id=\"image-preview\" style=\"background-image:url(../resources/img/icon/avatar.svg); background-size:70%; opacity:0.4\"></div>
						<a id=\"avatar\" href=\"#\">Upload Avatar</a>
						<input type=\"file\" name=\"avatar[]\" id=\"avatar-toggle\">
					</div>
					<div>
						<h3>Personal Details</h3>
						<div id=\"text-group\">
							<p>
								<label>First Name</label>
								<input type=\"text\" name=\"firstname\" id=\"firstname\">
							</p>
							<p>
								<label>Last Name</label>
								<input type=\"text\" name=\"lastname\" id=\"lastname\">
							</p>
						</div>
						<div id=\"text-group\">
							<label>Job Title</label>
							<input type=\"text\" name=\"position\" id=\"position\">
						</div>
						<div id=\"text-group\">
							<p>
								<label>Email</label>
								<input type=\"text\" name=\"email\" id=\"email\">
							</p>
							<p>
								<label>Phone Number</label>
								<input type=\"text\" name=\"phone\" id=\"phone\">
							</p>
						</div>
						<div id=\"text-group\">";
							if($_REQUEST['contact'] === "other"){
								echo "<label>Type</label>
								<select id=\"client\" name=\"client\">
									<option value=\"\">Other</option>
								</select>
								<input type=\"hidden\" value=\"Other\" name=\"type\">";
							}
							elseif($_REQUEST['contact'] === "client"){
								$sqlClient = "SELECT idclient, name FROM client";
								$rClient = mysqli_query($conn, $sqlClient) or die(mysqli_error($conn));

								echo "<label>Type / Client</label>
								<select id=\"client\" name=\"client\">
									<option>-- Select Client--</option>";
									while($rowC = mysqli_fetch_assoc($rClient)){
										$idclient = $rowC['idclient'];
										$name = $rowC['name'];

										echo "<option value=\"$idclient\">$name</option>";
									}
								echo "</select>";
							}
							echo "<input type=\"hidden\" name=\"type\" id=\"type\" value=\"Client\">
						</div>
					</div>
					<div>
						<h3>Address</h3>
						<div id=\"text-group\">
							<label>Address Line 1</label>
							<input type=\"text\" name=\"adline1\" id=\"adline1\">
						</div>
						<div id=\"text-group\">
							<label>Address Line 2</label>
							<input type=\"text\" name=\"adline2\" id=\"adline2\">
						</div>
						<div id=\"text-group\">
							<p>
								<label>City</label>
								<input type=\"text\" name=\"city\" id=\"city\">
							</p>
							<p>
								<label>Zip Code</label>
								<input type=\"text\" name=\"postcode\" id=\"postcode\">
							</p>
						</div>
						<div id=\"text-group\">
							<label>Country</label>
							<input type=\"text\" name=\"country\" id=\"country\">
						</div>
						<input type=\"submit\" name=\"create-new\" id=\"create-new\" value=\"Add Contact\">
					</div>
				</form>
			</article>
		</section>";
	}
?>