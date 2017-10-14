<?php
	include "db.php";

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$clientid = isset($_POST['clientid']) ? $_POST['clientid'] : null;
		$contactid = isset($_POST['contactid']) ? $_POST['contactid'] : null;
		$projectid = isset($_POST['projectid']) ? $_POST['projectid'] : null;

		$sql4 = "INSERT INTO relatedcontact(contact, project, client) 
			VALUES(?, ?, ?)";
			$stmt4 = mysqli_prepare($conn, $sql4) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt4, "ddd", $clientid, $projectid, $contactid) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt4) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt4);

    	echo $clientid;
	}
?>