<?php
	include "db.php";

	if(isset($_POST['clientid'])){
		$id = $_POST['clientid'];
		$info = $_POST['info'];
		$title = $_POST['title'];
		$uploader = $_POST['uploader'];

		$rm = "README.txt";
		$readme = fopen($rm, "w") or die("Unable to open file!");
		fwrite($readme, $info);
		fclose($readme);

		if(isset($_FILES['clientfiles']) && $_FILES['clientfiles']['error'][0] == 0){
			$zipArray = [];
			$zipName = ("../resources/client/".$id."/files/".$id."-".$title.".zip");
			$zipDir = ("../resources/client/".$id."/files/");
			$zipTable = ("resources/client/".$id."/files/".$id."-".$title.".zip");

			foreach($_FILES['clientfiles']['name'] as $f => $name){

				if (($_FILES['clientfiles']['size'][$f] < 1073741824) ){

					if($_FILES['clientfiles']['error'][$f] > 0){
						echo "Return Code: ".$_FILES['clientfiles']['error'][$f]."<br />";
					
					} else {
						if(!file_exists($zipDir)){
							mkdir($zipDir, 0777, true);
						}
						if(file_exists($zipName)){
							$zip = new ZipArchive();
							if($zip->open($zipName) === TRUE){
								$zip->addFile($_FILES['clientfiles']['tmp_name'][$f], $_FILES['clientfiles']['name'][$f]);
								$zip->close();
							}
						} else {
							$names = $_FILES['clientfiles']['tmp_name'][$f];
							$zip = new ZipArchive();
							$zip->open($zipName, ZipArchive::CREATE);
							$zip->addFile($rm, $rm);
							$zip->addFile($_FILES['clientfiles']['tmp_name'][$f], $_FILES['clientfiles']['name'][$f]);
							
							$zip->close();
						}

					}
				}	
			}
			$sqlAttach = "INSERT INTO clientfile(client, filesrc, name, uploader) VALUES(?, ?, ?, ?)";
			$stmt = mysqli_prepare($conn, $sqlAttach) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmt, "dssd", $id, $zipName, $title, $uploader) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
			mysqli_stmt_close($stmt) or die(mysqli_error($conn));
			echo "File has been uploaded";

			$userME = "";
			$sqlMe = "SELECT firstname, lastname FROM user WHERE iduser = '$uploader'";
			$rMe = mysqli_query($conn, $sqlMe) or die(mysqli_error($conn));
			while($rowME = mysqli_fetch_assoc($rMe)){
				$userME = $rowME['firstname']." ".$rowME['lastname'];
			}
			

			$sqlSel = "SELECT contact.idcontact, client.name FROM relatedcontact 
				INNER JOIN contact ON relatedcontact.contact = contact.idcontact 
				INNER JOIN client ON relatedcontact.client = client.idclient 
				WHERE contact.type='Colleague' AND relatedcontact.client ='$id' AND project = '0'";
			$rSel = mysqli_query($conn, $sqlSel) or die(mysqli_error($conn));
			while($rowS = mysqli_fetch_assoc($rSel)){
				$conID = $rowS['idcontact'];
				$clientName = $rowS['name'];

				$message = $userME." just uploaded a file for client ".$clientName;
				$type = "client";
				$datetime = date("Y-m-d H:i:s", time());

				$sqlNotify = "INSERT INTO notification(message, tonote, since, type) VALUES(?, ?, ?, ?)";
				$stmtN = mysqli_prepare($conn, $sqlNotify) or die(mysqli_error($conn));
				mysqli_stmt_bind_param($stmtN, "sdss", $message, $conID, $datetime, $type) or die(mysqli_error($conn));
				mysqli_stmt_execute($stmtN) or die(mysqli_error($conn));
				mysqli_stmt_close($stmtN);
			}
		} else {
			echo "undefined";
		}
	}
?>