<?php
	include "db.php";

	$status = filter_has_var(INPUT_POST, 'status') ? $_POST['status']: null;
	$status = trim($status);

	$id = $_POST['id'];

	$sqlS = "UPDATE user SET user.status = ? WHERE user.iduser = ?";
    $stmt2 = mysqli_prepare($conn, $sqlS) or die(mysqli_error($conn)); 
    mysqli_stmt_bind_param($stmt2, "ss", $status, $id) or die(mysqli_error($conn)); 
    mysqli_stmt_execute($stmt2) or die(mysqli_error($conn)); 
    mysqli_stmt_close($stmt2);

    echo $status;
?>
