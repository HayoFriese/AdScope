<?php
	ini_set("session.save_path", "../sessionData");
	session_start();
	include "server/db.php";
	require_once("functions.php");

	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
	    header("Location: signin.php");
  	} else{	
	echo pageIni("Dashboard - Adscope");
?>

	<body id="back-end-pages">
<?php
	echo nav("", "", "", "", "", "", "", "");
	echo head("Dashboard", $_SESSION['username'], $_SESSION['iduser']);

?>
	

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="server/js/projects.js"></script>
	<script src="server/js/general.js"></script>
<?php
	echo pageClose();
	}
?>