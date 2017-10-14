<?php
	include "db.php";

	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : null;
		$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : null;
		$position = isset($_POST['position']) ? $_POST['position'] : null;
		$email = isset($_POST['email']) ? $_POST['email'] : null;
		$phone = isset($_POST['phone']) ? $_POST['phone'] : null;
		$client = isset($_POST['client']) ? $_POST['client'] : null;
		$type = isset($_POST['type']) ? $_POST['type'] : null;

		$adline1 = isset($_POST['adline1']) ? $_POST['adline1'] : null;
		$adline2 = isset($_POST['adline2']) ? $_POST['adline2'] : null;
		$city = isset($_POST['city']) ? $_POST['city'] : null;
		$postcode = isset($_POST['postcode']) ? $_POST['postcode'] : null;
		$country = isset($_POST['country']) ? $_POST['country'] : null;


		$sql4 = "INSERT INTO contact(type, firstname, lastname, position, email, phone, adline1, adline2, postcode, city, country, client) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt4 = mysqli_prepare($conn, $sql4) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt4, "sssssssssssd", $type, $firstname, $lastname, $position, $email, $phone, $adline1, $adline2, $postcode, $city, $country, $client) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt4) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt4);

    	$id2 = mysqli_insert_id($conn);

    	if($type === "Client"){

			$sql2 = "UPDATE client SET contacts = contacts + 1 WHERE client.idclient = ?";
			$stmt = mysqli_prepare($conn, $sql2) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt, "d", $client) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt);

    		$sql3 = "INSERT INTO relatedcontact(contact, client) VALUES(?, ?)";
    		$stmt3 = mysqli_prepare($conn, $sql3) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt3, "dd", $id2, $client) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt3) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt3);

    		$sqlNewNot = "SELECT DISTINCT contact, client.name FROM relatedcontact 
    		INNER JOIN contact ON relatedcontact.contact = contact.idcontact 
    		INNER JOIN client ON relatedcontact.client = client.idclient
    		WHERE contact.type = 'Colleague' AND relatedcontact.client = '$client'";
    		$rNewNot = mysqli_query($conn, $sqlNewNot) or die(mysqli_error($conn));
    				
    		while($rowNN = mysqli_fetch_assoc($rNewNot)){
    			$name = $rowNN['name'];
    			$to = $rowNN['contact'];
    			$message = $firstname." ".$lastname." has been added as a contact for ".$name;
    			$type = "contact";
    			$datetime = date("Y-m-d H:i:s", time());
    					
    			$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
				$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtN, "sdss", $message, $to, $datetime, $type) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
				mysqli_stmt_close($stmtN);
			}
		}
		if($type != "Client"){
			$sqlNewNot = "SELECT idcontact FROM contact WHERE type = 'Colleague'";
    		$rNewNot = mysqli_query($conn, $sqlNewNot) or die(mysqli_error($conn));
    				
    		while($rowNN = mysqli_fetch_assoc($rNewNot)){
    			$to = $rowNN['idcontact'];
    			$message = $firstname." ".$lastname." has been added as a contact";
    			$type = "contact";
    			$datetime = date("Y-m-d H:i:s", time());
    					
    			$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
				$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtN, "sdss", $message, $to, $datetime, $type) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
				mysqli_stmt_close($stmtN);
			}
		}

		if(isset($_FILES['avatar']) && $_FILES['avatar']['name'][0] != null && $_FILES['avatar']['name'][0] != ""){
			foreach($_FILES['avatar']['name'] as $f => $name){
				$target_dir = ("../resources/img/contact-pics/".$id2."/");
   		 		$dir = ("resources/img/contact-pics/".$id2."/");
				
				$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG", "GIF");
				$temp = explode(".", $name);
				$extension = end($temp);

				if ((($_FILES['avatar']['type'][$f] == "image/gif") || ($_FILES['avatar']['type'][$f] == "image/GIF") 
				|| ($_FILES['avatar']['type'][$f] == "image/jpeg") || ($_FILES['avatar']['type'][$f] == "image/JPEG") 
				|| ($_FILES['avatar']['type'][$f] == "image/jpg") || ($_FILES['avatar']['type'][$f] == "image/JPG") 
				|| ($_FILES['avatar']['type'][$f] == "image/png") || ($_FILES['avatar']['type'][$f] == "image/PNG"))
				&& ($_FILES['avatar']['size'][$f] < 1073741824)
				&& in_array($extension, $allowedExts)){
					if($_FILES['avatar']['error'][$f] > 0){
						echo "There was a problem uploading your profile picture. Return Code: ".$_FILES['avatar']['error'][$f]."<br />";
					} else {
						if(file_exists($target_dir)){
						} else {
							mkdir($target_dir, 0777, true);
						}
						if(file_exists($target_dir.$name)){
							$pathname = $dir.$name;
						} else {
							$names = $_FILES['avatar']['tmp_name'][$f];
							if(move_uploaded_file($names, "$target_dir/$name")){
								$pathname = ($dir.$name);

    							$sqlimg2 = "UPDATE contact SET contact.src = ? WHERE idcontact = ?";
								$stmt2 = mysqli_prepare($conn, $sqlimg2) or die(mysqli_error($conn));
    							mysqli_stmt_bind_param($stmt2, "sd", $pathname, $id2) or die(mysqli_error($conn));
    							mysqli_stmt_execute($stmt2) or die(mysqli_error($conn));
    							mysqli_stmt_close($stmt2);
							} else{
								echo "pathname is incorrect";
							}
						}
					}
				} else{
					echo "File is too big";
				}
	   					
			}
		}
		echo $client."&%".$id2;
			
	}

?>