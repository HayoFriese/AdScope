<?php
	include "db.php";

	$username = $_POST['username'] ? $_POST['username']:"none";

	if($username != "none"){
		$sql = "SELECT src FROM user WHERE user.username = '$username'";
		$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		if(mysqli_num_rows($r) > 0){
			$src = mysqli_fetch_assoc($r)['src'];
			echo $src;
		} else {
			echo "none";
		}
	}
?>