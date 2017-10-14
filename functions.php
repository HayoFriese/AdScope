<?php
/* Navigation */
function nav($project, $schedule, $tracker, $rooms, $inbox, $log, $adbook, $settings){
    include "server/db.php";
	$uid = $_SESSION['iduser'];
	$sql = "SELECT status, src FROM user WHERE user.iduser = '$uid'";
	$stat = mysqli_prepare($conn, $sql) or die(mysqli_error($conn)); 
        mysqli_stmt_execute($stat) or die(mysqli_error($conn)); 
        mysqli_stmt_bind_result($stat, $status, $src) or die(mysqli_error($conn));
    
    if(mysqli_stmt_fetch($stat)){
    	mysqli_stmt_close($stat);
    	if($status == "Online"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#88D54F;\"></div>\n";
		} else if($status == "Busy"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#A80F0F;\"></div>\n";
		} else if($status == "Out of Office"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:gray;\"></div>\n";
		} else if($status == "Away"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#FACF00;\"></div>\n";
		} else if($status == "Do Not Disturb"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#FF0000;\"></div>\n";
		}
    }
    if($src === "" || $src === null){
    	$src = "url('resources/img/icon/user-white.svg'); opacity:0.2; padding:10px";
    } else{
    	$src = "url('".$src."')";
    }

    $nav = <<<NAV
		<nav>
			<ul>
				<li>
					<a href="dashboard.php">
						<div id="img" style="background-image: $src;"></div>
						$statuses
					</a>
					<ul>
						<li>
							<a href="#">
								<div style="background-color:#88D54F;"></div>
								<span>Online</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:#FACF00;"></div>
								<span>Away</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:#A80F0F;"></div>
								<span>Busy</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:#FF0000;"></div>
								<span>Do Not Disturb</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:gray;"></div>
								<span>Out of Office</span>
							</a>
						</li>
						<li>
							<img src="resources/img/icon/more.svg">
						</li>
						<li>
							<a href="#">
								<div></div>
								<span>Edit Account</span>
							</a>
						</li>
					</ul>	
				</li>
			</ul>
			<div>
				<ul>
					<li $project>
						<a href="project.php">
							<img title="Projects" src="resources/img/icon/checked-white.svg">
						</a>
					</li>
					<li $schedule>
						<a href="schedule.php">
							<img title="Schedule" src="resources/img/icon/calendar-white.svg">
						</a>
					</li>
					<li $tracker>
						<a href="tracker.php">
							<img title="Logger" src="resources/img/icon/timer-white.svg">
						</a>
					</li>
					<li $rooms>
						<a href="rooms.php">
							<img title="Rooms & Tools" src="resources/img/icon/business-group-white.svg">
						</a>
					</li>
					<li $inbox>
						<a href="inbox.php">
							<img title="Inbox" src="resources/img/icon/mail-white.svg">
						</a>
					</li>
					<li $log>
						<a href="log.php">
							<img title="Financial Logs" src="resources/img/icon/report-white.svg">
						</a>
					</li>
					<li $adbook>
						<a href="addressbook.php">
							<img title="Address Book" src="resources/img/icon/notebook-white.svg">
						</a>
					</li>
					<li $settings>
						<a href="settings.php">
							<img title="Settings" src="resources/img/icon/wrench-white.svg">
						</a>
					</li>
				</ul>
			</div>
		</nav>
NAV;
  $nav .="\n";
  return $nav;
}
function nav2($project, $schedule, $tracker, $rooms, $inbox, $log, $adbook, $settings){
    include "db.php";
	$uid = $_SESSION['iduser'];
	$sql = "SELECT status, src FROM user WHERE user.iduser = '$uid'";
	$stat = mysqli_prepare($conn, $sql) or die(mysqli_error($conn)); 
        mysqli_stmt_execute($stat) or die(mysqli_error($conn)); 
        mysqli_stmt_bind_result($stat, $status, $src) or die(mysqli_error($conn));
    
    if(mysqli_stmt_fetch($stat)){
    	mysqli_stmt_close($stat);
    	if($status == "Online"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#88D54F;\"></div>\n";
		} else if($status == "Busy"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#A80F0F;\"></div>\n";
		} else if($status == "Out of Office"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:gray;\"></div>\n";
		} else if($status == "Away"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#FACF00;\"></div>\n";
		} else if($status == "Do Not Disturb"){
			$statuses = "\n<div id=\"headstat\" style=\"background-color:#FF0000;\"></div>\n";
		}
    }
        if($src === "" || $src === null){
    	$src = "url('../resources/img/icon/user-white.svg'); opacity:0.2; padding:10px";
    } else{
    	$src = "url('../".$src."')";
    }

    $nav = <<<NAV
		<nav>
			<ul>
				<li>
					<a href="../dashboard.php">
						<div id="img" style="background-image: $src;"></div>
						$statuses
					</a>
					<ul>
						<li>
							<a href="#">
								<div style="background-color:#88D54F;"></div>
								<span>Online</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:#FACF00;"></div>
								<span>Away</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:#A80F0F;"></div>
								<span>Busy</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:#FF0000;"></div>
								<span>Do Not Disturb</span>
							</a>
						</li>
						<li>
							<a href="#">
								<div style="background-color:gray;"></div>
								<span>Out of Office</span>
							</a>
						</li>
						<li>
							<img src="../resources/img/icon/more.svg">
						</li>
						<li>
							<a href="#">
								<div></div>
								<span>Edit Account</span>
							</a>
						</li>
					</ul>	
				</li>
			</ul>
			<div>
				<ul>
					<li $project>
						<a href="../project.php">
							<img title="Projects" src="../resources/img/icon/checked-white.svg">
						</a>
					</li>
					<li $schedule>
						<a href="../schedule.php">
							<img title="Schedule" src="../resources/img/icon/calendar-white.svg">
						</a>
					</li>
					<li $tracker>
						<a href="../tracker.php">
							<img title="Tracker" src="../resources/img/icon/timer-white.svg">
						</a>
					</li>
					<li $rooms>
						<a href="../rooms.php">
							<img title="Rooms & Tools" src="../resources/img/icon/business-group-white.svg">
						</a>
					</li>
					<li $inbox>
						<a href="../inbox.php">
							<img title="Inbox" src="../resources/img/icon/mail-white.svg">
						</a>
					</li>
					<li $log>
						<a href="../log.php">
							<img title="Financial Logs" src="../resources/img/icon/report-white.svg">
						</a>
					</li>
					<li $adbook>
						<a href="../addressbook.php">
							<img title="Address Book" src="../resources/img/icon/notebook-white.svg">
						</a>
					</li>
					<li $settings>
						<a href="../settings.php">
							<img title="Settings" src="../resources/img/icon/wrench-white.svg">
						</a>
					</li>
				</ul>
			</div>
		</nav>
NAV;
  $nav .="\n";
  return $nav;
}

function pageIni($title){
	$pageIni = <<<PAGEINI
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="stylesheet" href="resources/css/general.css" type="text/css">
    	<link rel="stylesheet" href="resources/css/adscope.css" type="text/css">
    	<link rel="stylesheet" href="resources/css/fonts.css" type="text/css">
    	<link rel="stylesheet" href="resources/css/datepicker.css" type="text/css">
    	<link href='resources/css/fullcalendar.css' rel='stylesheet' />
    	<link rel="stylesheet" href="resources/css/spectrum.css" type="text/css" />

    	<link rel="stylesheet" href="resources/fonts/font-awesome-4.6.3/css/font-awesome.min.css">
    	<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Kalam:700" rel="stylesheet">
		<!--<link rel="icon" href="resources/img/favicon.ico">-->

		<title>$title</title>
	</head>
PAGEINI;
	$pageIni .= "\n";
	return $pageIni;
}

function pageIni2($title){
	$pageIni = <<<PAGEINI
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="stylesheet" href="../resources/css/general.css" type="text/css">
    	<link rel="stylesheet" href="../resources/css/adscope.css" type="text/css">
    	<link rel="stylesheet" href="../resources/css/fonts.css" type="text/css">
    	<link rel="stylesheet" href="../resources/css/datepicker.css" type="text/css">
    	<link rel="stylesheet" href="../resources/css/colorpicker.css" type="text/css" />

   	    <link rel="stylesheet" href="../resources/fonts/font-awesome-4.6.3/css/font-awesome.min.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Kalam:700" rel="stylesheet">
		<!--<link rel="icon" href="../resources/img/favicon.ico">-->

		<title>$title</title>
	</head>
PAGEINI;
	$pageIni .= "\n";
	return $pageIni;
}

function pageClose(){
	$pageClose = <<<PAGECLOSE
	</body>
</html>
PAGECLOSE;
	return $pageClose;
}

function signIn($errors){

	if(isset($_POST['sign-in'])){
		$signIn = <<<SIGNIN
		<div>
				<div></div>
				<section>
					<div>
						<div>
							<h1>Sign In</h1>
							<h2>"Whenever you find yourself on the side of the majority, it is time to pause and reflect."</h2>
							<h3>- Mark Twain</h3>
							<!-- <h2>The first rule of any technology used in a business is that automation applied to an efficient operation will magnify the efficiency. The second is that automation applied to an inefficient operation will magnify the inefficiency.</h2>
							<h3>- Bill Gates</h3> -->
						</div>
					</div>
					<article>
						<div>
							<div></div>
							<form method="post" action="login.php">
SIGNIN;

						$signIn .= "<div class='error-container'>\n<ul>";
    					for ($a=0; $a < count($errors); $a++) {
    						$signIn .= "<li style='font-size:10px;'>$errors[$a]</li>\n";
					    }
					    $signIn .= "</ul></div>";
					    $signIn .= <<<PART2
								<label for="username">Username</label>
								<input type="text" id="username" name="username">
								<label for="password">Password</label>
								<input type="password" id="password" name="password">
								<input type="submit" value="Sign In" name="sign-in">
							</form>
						</div>
						<a href="forgotpass.php">Forgot Your Password?</a>
					</article>
				</section>
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
			<script type="text/javascript" src="server/js/signin.js"></script>
PART2;
	} else {
		$signIn = <<<SIGNIN
		<div>
				<div></div>
				<section>
					<div>
						<div>
							<h1>Sign In</h1>
							<h2>"Whenever you find yourself on the side of the majority, it is time to pause and reflect."</h2>
							<h3>- Mark Twain</h3>
							<!-- <h2>The first rule of any technology used in a business is that automation applied to an efficient operation will magnify the efficiency. The second is that automation applied to an inefficient operation will magnify the inefficiency.</h2>
							<h3>- Bill Gates</h3> -->
						</div>
					</div>
					<article>
						<div>
							<div></div>
							<form method="post" action="login.php">
								<label for="username">Username</label>
								<input type="text" id="username" name="username">
								<label for="password">Password</label>
								<input type="password" id="password" name="password">
								<input type="submit" value="Sign In" name="sign-in">
							</form>
						</div>
						<a href="forgotpass.php">Forgot Your Password?</a>
					</article>
				</section>
			</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="server/js/signin.js"></script>
SIGNIN;
	}
	$signIn .= "\n";
	return $signIn;
}

function head($page, $username, $uid){

	$head = <<<HEAD
	<header>
		<h1>
			<span>$page</span>
			<span>|</span>
			<ul>
				<li id="header-username" data-id="$uid">$username

				</li>
			</ul>
		</h1>

		<div>
			<span id="time"></span>
			<a href=""><img src="resources/img/icon/bell.svg"><span id="notifyNum"></span>
			<ul>
				
			</ul>
			</a>
		</div>
	
		<a href="logout.php"><img src="resources/img/icon/exit.svg"></a>
	</header>
HEAD;
	$head .= "\n";
	return $head;
}
function head2($page, $username, $uid){
	$head = <<<HEAD
	<header>
		<h1>
			<span>$page</span>
			<span>|</span>
			<ul>
				<li id="header-username" data-id="$uid">$username

				</li>
			</ul>
		</h1>

		<div>
			<span id="time"></span>
			<a href=""><img src="../resources/img/icon/bell.svg"><span id="notifyNum"></span>
			<ul>

			</ul>
			</a>
		</div>
	
		<a href="../logout.php"><img src="../resources/img/icon/exit.svg"></a>
	</header>
HEAD;
	$head .= "\n";
	return $head;
}
?>