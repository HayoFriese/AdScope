<?php
	ini_set("session.save_path", "../sessionData");
	session_start();
  	include "server/db.php";
	include "functions.php";
	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
	    header("Location: signin.php");
  	} else{	
	echo pageIni("Hour Tracker - Adscope");
?>
	<body id="back-end-pages">
<?php
	echo nav("", "", " id=\"active\"", "", "", "", "", "");
	echo head("Hour Logger", $_SESSION['username'], $_SESSION['iduser']);
?>
	<div id="back-end-body" class="tracker">
		<section id="logger-body">
		<?php
			$userid = $_SESSION['iduser'];

			$sqlTime = "SELECT * FROM logger WHERE user = '$userid' AND status != 'temp' AND status != 'done'";
			$rTime = mysqli_query($conn, $sqlTime) or die(mysqli_error($conn));

			$sqlProj = "SELECT DISTINCT idproject, title FROM relatedcontact 
					INNER JOIN project ON relatedcontact.project = project.idproject
					WHERE contact = '$userid'";
			$rProj = mysqli_query($conn, $sqlProj) or die(mysqli_error($conn));

			if(mysqli_num_rows($rTime) === 1){
				$sqlTiming = "SELECT * FROM logger WHERE user = '$userid' AND (status = 'active' || status = 'paused')";
				$rTiming = mysqli_query($conn, $sqlTiming) or die(mysqli_error($conn));
				while($rowAct = mysqli_fetch_assoc($rTiming)){
					$status = $rowAct['status'];
					$idlogger = $rowAct['idlogger'];
					$idproject = $rowAct['project'];
					$idtask = $rowAct['task'];
					$starttime = $rowAct['timerstart'];
					$lastpause = $rowAct['lastpause'];
					$pauselength = $rowAct['pauselength'];
					if($status === "paused"){
						$now = 0;
						$lastp = strtotime($lastpause);
						$startM = strtotime($starttime);
						$pauseL = strtotime($pauselength);

						$timemath = strtotime($lastpause)-strtotime($starttime)-strtotime($pauselength);
						$timeform = gmdate("H:i:s", $timemath);
						$timeArr = explode(':', $timeform);
						$h = $timeArr[0];
						$m = $timeArr[1];
						$s = $timeArr[2];
					} else {
						$now = strtotime(date("Y-m-d H:i:s"));
						$lastp = strtotime($lastpause);
						$startM = strtotime($starttime);
						$pauseL = $pauselength;

						$timemath = $now-strtotime($pauselength)-strtotime($starttime);
						$timeform = gmdate("H:i:s", $timemath);
						$timeArr = explode(':', $timeform);
						$h = $timeArr[0];
						$m = $timeArr[1];
						$s = $timeArr[2];
					}
				}
				echo "<form>
					<input type=\"hidden\" name=\"logger-id\" id=\"logger-id\" value=\"$idlogger\">
					<input type=\"hidden\" name=\"user-id\" id=\"user-id\" value=\"$userid\">
					<input type=\"hidden\" name=\"status\" id=\"timer-status\" value=\"$status\">
				<article>
					<label for=\"project\">Select Project</label>
						<select id=\"project\">";
							while($rowP = mysqli_fetch_assoc($rProj)){
								$projid = $rowP['idproject'];
								$ptitle = $rowP['title'];
								echo "<option value=\"$projid\">$ptitle</option>";
							}
							echo "<option value=\"other\">Other</option>
							<option value=\"none\">No Task</option>";
						echo "</select>
					<label for=\"task\">Task</label>
						<select id=\"task\">";
								//SELECT ALL TASKS IN PROJECT
								//ADD OPTION FOR NEW TASK

							echo "<option value=\"new\">Other Task</option>
						</select>
				</article>
				<h4>
					<span id=\"log-hours\">$h</span>:<span id=\"log-minutes\">$m</span>:<span id=\"log-seconds\">$s</span>
				</h4>
				<input type=\"hidden\" name=\"total-time\" id=\"total-time\">
				<div>
					<a href=\"#\" id=\"pause\"><span>Pause</span></a>
					<a href=\"#\" id=\"stop\"><span>Stop</span></a>
					<a href=\"#\" id=\"new\"><span>New</span></a>
				</div>

				<article>
					<p>Started: <span>$starttime</span></p>
				</article>
				<p><a href=\"#\">View Past Logs</a></p>
			</form>";
			}
			//Not new, an active one is running
			else {
				$sqlCheckTemp = "SELECT * FROM logger WHERE user = '$userid' AND status = 'temp'";
				$rCheckTemp = mysqli_query($conn, $sqlCheckTemp) or die(mysqli_error($conn));
				if(mysqli_num_rows($rCheckTemp) === 1){
					while($rowTemp = mysqli_fetch_assoc($rCheckTemp)){
						$idlog = $rowTemp['idlogger'];
						$tempstatus = $rowTemp['status'];
					}
				} elseif(mysqli_num_rows($rCheckTemp) === 0){
					$tempname = "Temporary";
					$tempstatus = "temp";

					$sqlTemp = "INSERT INTO logger(logger, user, status) 
					VALUES(?, ?, ?)";
					$stmtT = mysqli_prepare($conn, $sqlTemp) or die(mysqli_error($conn));
    				mysqli_stmt_bind_param($stmtT, "sds", $tempname, $userid, $tempstatus) or die(mysqli_error($conn));
    				mysqli_stmt_execute($stmtT) or die(mysqli_error($conn));
    				mysqli_stmt_close($stmtT);

    				$idlog = mysqli_insert_id($conn);
				}
				
				echo "<form>
					<input type=\"hidden\" name=\"logger-id\" id=\"logger-id\" value=\"$idlog\">
					<input type=\"hidden\" name=\"user-id\" id=\"user-id\" value=\"$userid\">
					<input type=\"hidden\" name=\"status\" id=\"timer-status\" value=\"$tempstatus\">
				<article>
					<label for=\"project\">Select Project</label>
						<select id=\"project\">
							<option value=\"empty\">-- Choose --</option>";
							while($rowP = mysqli_fetch_assoc($rProj)){
								$projid = $rowP['idproject'];
								$ptitle = $rowP['title'];
								echo "<option value=\"$projid\">$ptitle</option>";
							}
							echo "<option value=\"other\">Other</option>
							<option value=\"none\">No Task</option>";
						echo "</select>
					<label for=\"task\">Task</label>
						<select id=\"task\">
							<option value=\"empty\">-- Choose--</option>";

								//SELECT ALL TASKS IN PROJECT
								//ADD OPTION FOR NEW TASK

							echo "<option value=\"new\">Other Task</option>
						</select>
				</article>
				<h4>
					<span id=\"log-hours\">00</span>:<span id=\"log-minutes\">00</span>:<span id=\"log-seconds\">00</span>
				</h4>
				<input type=\"hidden\" name=\"total-time\" id=\"total-time\">
				<div>
					<a href=\"#\" id=\"start\"><span>Start</span></a>
					<a href=\"#\" id=\"stop\"><span>Stop</span></a>
					<a href=\"#\" id=\"new\"><span>New</span></a>
				</div>

				<article>
					<p>Started: <span></span></p>
				</article>
				<p><a href=\"#\">View Past Logs</a></p>
			</form>";
			}
			?>
		</section>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="server/js/general.js"></script>
	<script src="server/js/tracker.js"></script>
<?php
	echo pageClose();
	}
?>