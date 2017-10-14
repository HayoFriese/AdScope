<?php
	ini_set("session.save_path", "../sessionData");
	session_start();
  	include "server/db.php";
	include "functions.php";
	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
	    header("Location: signin.php");
  	} else{	
	echo pageIni("Rooms & Tools - Adscope");
?>
	<body id="back-end-pages">
	<?php
		echo nav("", "", "", " id=\"active\"", "", "", "", "");
		echo head("Rooms & Tools", $_SESSION['username'], $_SESSION['iduser']);
	?>
	<div id="back-end-body">
		<h1 id="comingsoon">Coming Soon</h1>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="server/js/general.js"></script>
<?php
	echo pageClose();
	}
?>