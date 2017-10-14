<?php
	$conn = mysqli_connect('localhost', 'root', '', 'adscope');

	if(isset($_REQUEST['userid'])){
		$userid = $_REQUEST['userid'];

		$sql = "SELECT event.idevent, event.event, event.startdate, event.starttime, event.enddate, event.endtime, event.client FROM invites
		INNER JOIN event ON invites.forInvite = event.idevent
		WHERE event.status != 'Cancelled' AND invites.toInvite = '$userid' AND invites.response = '2'"; //line needs to be edited to show only events created or attending
		$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		$data = [];
		while($row = mysqli_fetch_assoc($r)){
			$id = $row['idevent'];
			$title = $row['event'];
			$start = $row['startdate'];
			$end = $row['enddate'];
			if(isset($row['starttime']) || $row['starttime'] != NULL){
				$start = $row['startdate']."T".$row['starttime'];
				$end = $row['enddate']."T".$row['endtime'];
			}
			$client = $row['client'];
			if(isset($client)){
				$sqlHex = "SELECT hex FROM client WHERE idclient = '$client'";
				$rHex = mysqli_query($conn, $sqlHex) or die(mysqli_error($conn));
				$color = mysqli_fetch_assoc($rHex)['hex'];
			} else{
				$color = "";
			}

			$datapush = [
					'id' => $id,
					'title' => $title,
					'start' => $start,
					'end' => $end,
					'color' => $color
			];
			$data[] = $datapush;
		}

		echo json_encode($data, JSON_PRETTY_PRINT);

		mysqli_close($conn);	
	} else {
		echo "SHIT";
	}
	
?>