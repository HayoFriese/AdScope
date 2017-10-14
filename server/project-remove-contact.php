<?php
	include "db.php";
	$id = isset($_POST['id']) ? $_POST['id']:null;
	$projectid = isset($_POST['project']) ? $_POST['project']:null;

	if($id != "" && $id != null && $projectid != "" && $projectid != null){
		$sql1 = "SELECT type FROM contact WHERE contact.idcontact = $id";
		$r1 = mysqli_query($conn, $sql1) or die(mysqli_error($conn));
		if(mysqli_num_rows($r1) > 0){
			$type = mysqli_fetch_assoc($r1)['type'];
			if($type != "Client"){
				$sql = "DELETE FROM relatedcontact WHERE relatedcontact.contact = ? AND relatedcontact.project = ?";
				$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    			mysqli_stmt_bind_param($stmt, 'dd', $id, $projectid);
    			if (!mysqli_stmt_execute($stmt)){
    			    
    			    mysqli_close($conn);
    			    die("The system is not available, try again later");
    			
    			}
    			if(mysqli_stmt_store_result($stmt)){
    			   echo "Delete successful!";
    			}
			} else{
				echo "Is a project";
			}
		} else{
			echo "Contact does not exist";
		}
	}
?>