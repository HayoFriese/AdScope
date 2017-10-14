<?php
	include "db.php";

	if(isset($_REQUEST['id'])){
		$projectid = $_REQUEST['id'];
		$data = [];
		$sql = "SELECT idtask, task FROM task WHERE project = '$projectid' AND done !='1'";
		$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
		if(mysqli_num_rows($r) > 0){
			while($row = mysqli_fetch_assoc($r)){
				$idtask = $row['idtask'];
				$task = $row['task'];

				$datapush = [
					'id' => $idtask,
					'task' => $task
				];

				$data[] = $datapush;
			}

			echo json_encode($data, JSON_PRETTY_PRINT);
		}
		
	} 
?>