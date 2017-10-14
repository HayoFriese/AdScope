<?php
	include "db.php";

	if(isset($_POST['projectid'])){
		$id = $_POST['projectid'];
		$info = $_POST['info'];
		$title = $_POST['title'];
		$uploader = $_POST['uploader'];

		$rm = "README.txt";
		$readme = fopen($rm, "w") or die("Unable to open file!");
		fwrite($readme, $info);
		fclose($readme);

		if(isset($_FILES['projectfiles']) && $_FILES['projectfiles']['error'][0] == 0){
			$zipArray = [];
			$zipName = ("../resources/project/".$id."/files/".$id."-".$title.".zip");
			$zipDir = ("../resources/project/".$id."/files/");
			$zipTable = ("resources/project/".$id."/files/".$id."-".$title.".zip");

			foreach($_FILES['projectfiles']['name'] as $f => $name){

				if (($_FILES['projectfiles']['size'][$f] < 1073741824) ){

					if($_FILES['projectfiles']['error'][$f] > 0){
						echo "Return Code: ".$_FILES['projectfiles']['error'][$f]."<br />";
					
					} else {
						if(!file_exists($zipDir)){
							mkdir($zipDir, 0777, true);
						}
						if(file_exists($zipName)){
							$zip = new ZipArchive();
							if($zip->open($zipName) === TRUE){
								$zip->addFile($_FILES['projectfiles']['tmp_name'][$f], $_FILES['projectfiles']['name'][$f]);
								$zip->close();
							}
						} else {
							$names = $_FILES['projectfiles']['tmp_name'][$f];
							$zip = new ZipArchive();
							$zip->open($zipName, ZipArchive::CREATE);
							$zip->addFile($rm, $rm);
							$zip->addFile($_FILES['projectfiles']['tmp_name'][$f], $_FILES['projectfiles']['name'][$f]);
							
							$zip->close();
						}
					}
				}	
			}
			$sqlAttach = "INSERT INTO projectfile(project, filesrc, name, uploader) VALUES(?, ?, ?, ?)";
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

			$sqlSel = "SELECT contact.idcontact, project.title FROM relatedcontact 
				INNER JOIN contact ON relatedcontact.contact = contact.idcontact 
				INNER JOIN project ON relatedcontact.client = project.idproject 
				WHERE contact.type='Colleague' AND project = '$id'";
			$rSel = mysqli_query($conn, $sqlSel) or die(mysqli_error($conn));
			while($rowS = mysqli_fetch_assoc($rSel)){
				$conID = $rowS['idcontact'];
				$projectitle = $rowS['title'];

				$message = $userME." just uploaded a file for project ".$projectitle;
				$type = "project";
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