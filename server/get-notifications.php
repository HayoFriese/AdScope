<?php
	include "db.php";

	if(isset($_REQUEST['id'])){

		$data = [];

		$userid = $_REQUEST['id'];

		$sqlNotif = "SELECT idnotification, message, since, notification.type FROM notification 
		INNER JOIN contact ON notification.tonote = contact.idcontact
		WHERE contact.type = 'Colleague' AND dismissed = '0' AND notification.tonote = '$userid'";
		$rNotif = mysqli_query($conn, $sqlNotif) or die(mysqli_error($conn));
		if(mysqli_num_rows($rNotif) > 0){
			while($rowN = mysqli_fetch_assoc($rNotif)){
				$notid = $rowN['idnotification'];
				$message = $rowN['message'];
				$since = $rowN['since'];
				$type = $rowN['type'];

				$datapush = [
					'notid' => $notid,
					'message' => $message,
					'since' => $since,
					'type' => $type
				];
				$data[] = $datapush;
			}

			echo json_encode($data, JSON_PRETTY_PRINT);
		}

	}
?>