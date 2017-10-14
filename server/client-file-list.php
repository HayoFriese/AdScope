<?php

	function filelist($cfid){
		include "db.php";
		if($cfid){
			$cfid = $_REQUEST['id'];
			$sql = "SELECT * FROM clientfile WHERE clientfile.client = '$cfid' ORDER BY clientfile.idclientfile DESC";
			$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	
			if(mysqli_num_rows($r) > 0){
				echo "<h1>Client Files</h1>";
			
				while($row = mysqli_fetch_assoc($r)){
					$src = $row['filesrc'];
					$title = $row['name'];
					$by = $row['uploader'];
					echo "<p id=\"file\">
						<a href=\"$src\">
							<img src=\"../resources/img/icon/attachment.svg\">$title";

					$sqlname = "SELECT firstname, lastname FROM user where user.iduser= $by";
					$rName = mysqli_query($conn, $sqlname) or die(mysqli_error($conn));
					if(mysqli_num_rows($rName) === 0){
						echo "<span>(former employee)</span>";
					} else{
						while($rown = mysqli_fetch_assoc($rName)){
							$uploader = $rown['firstname']." ".$rown['lastname'];
							echo "<span>$uploader</span>";
						}
					}
					echo "</a>
					</p>";
				}
			}
		}
	}
?>