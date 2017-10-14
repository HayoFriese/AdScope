<?php
	include "db.php";

	if(isset($_REQUEST['id'])){

		$notid = $_REQUEST['id'];

		$sqlNotif = "SELECT idnotification FROM notification WHERE idnotification = '$notid'";
		$rN = mysqli_query($conn, $sqlNotif) or die(mysqli_error($conn));
		if(mysqli_num_rows($rN) > 0){
			$id = mysqli_fetch_assoc($rN)['idnotification'];

			$sqlDel = "DELETE FROM notification WHERE idnotification = ?";
			$stmtD = mysqli_prepare($conn, $sqlDel) or die(mysqli_error($conn));
			mysqli_stmt_bind_param($stmtD, "d", $id) or die(mysqli_error($conn));
			mysqli_stmt_execute($stmtD) or die(mysqli_error($conn));
			mysqli_stmt_close($stmtD);

			$sqlCheck = "SELECT idnotification, message, since, type FROM notification WHERE idnotification = '$notid'";
			$rN = mysqli_query($conn, $sqlNotif) or die(mysqli_error($conn));
			if(mysqli_num_rows($rN) === 0){
				echo "dismissed";
			}
		} else {
			echo $notid;
		}

	} 
?>