<?php
	include "db.php";

	if(isset($_REQUEST['id'])){
		$data = [];

		$userid = $_REQUEST['id'];

		$sqlInvites = "SELECT idinvites, fromInvite, contact.firstname, contact.lastname, event.event, sent FROM invites 
		INNER JOIN event ON invites.forInvite = event.idevent 
		INNER JOIN contact ON invites.fromInvite = contact.idcontact
		WHERE toInvite = '$userid' AND response = '1' AND responded IS NULL AND contact.type = 'Colleague'";
		$rInvites = mysqli_query($conn, $sqlInvites) or die(mysqli_error($conn));
		if(mysqli_num_rows($rInvites) > 0){
			while($rowI = mysqli_fetch_assoc($rInvites)){
				$from = $rowI['fromInvite'];
				$name = $rowI['firstname']." ".$rowI['lastname'];
				$for = $rowI['event'];
				$evid = $rowI['idinvites'];
				$sent = $rowI['sent'];

				$datapush = [
					'evid' => $evid,
					'for' => $for,
					'from' => $from,
					'name' => $name,
					'sent' => $sent
				];
				$data[] = $datapush;
			}

			echo json_encode($data, JSON_PRETTY_PRINT);
		}

	}
?>