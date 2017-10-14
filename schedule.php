<?php
	ini_set("session.save_path", "../sessionData");
	session_start();
	include "functions.php";

	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
	    header("Location: signin.php");
  	} else{
	echo pageIni("Schedule - Adscope");
?>
	<body id="back-end-pages">
<?php
	echo nav("", " id=\"active\"", "", "", "", "", "", "");
	echo head("Agenda", $_SESSION['username'], $_SESSION['iduser']);
?>	
		<div id="back-end-body" class="agenda-body">
			<!-- <section id="date">
				<article>
					<div>
						<a href="#">&lt;</a>
						<p>Today</p>
						<a href="#">&gt;</a>
					</div>
				</article>
				<article>
					<h1>11</h1>
					<h2>Thursday</h2>
				</article>
				<article>
					<ul>
						<li class="active"><a href="#">Month</a></li>
						<li><a href="#">Week</a></li>
					</ul>
				</article>
				<article>
					<h1>Today's Workload <a href="#">+</a></h1>
					<div>
						<ul>
							<li><div style="background-color: red;"></div></li>
							<li>Review Designs for PR Campaign</li>
							<li><img src="resources/img/icon/star.svg"></li>
							<li><img src="resources/img/icon/two-circling-arrows.svg"></li>
							<li><img src="resources/img/icon/calendar.svg"></li>
						</ul>
						<ul>
							<li><div style="background-color: red;"></div></li>
							<li>Make Markers</li>
							<li><img src="resources/img/icon/star.svg"></li>
							<li><img src="resources/img/icon/two-circling-arrows.svg"></li>
							<li><img src="resources/img/icon/calendar.svg"></li>
						</ul>
						<ul>
							<li><div style="background-color: red;"></div></li>
							<li>Approach Client with Concept for a Sales Program</li>
							<li><img src="resources/img/icon/star.svg"></li>
							<li><img src="resources/img/icon/two-circling-arrows.svg"></li>
							<li><img src="resources/img/icon/calendar.svg"></li>
						</ul>
						<ul>
							<li><div style="background-color: red;"></div></li>
							<li>Cut Sandwich Ads</li>
							<li><img src="resources/img/icon/star.svg"></li>
							<li><img src="resources/img/icon/two-circling-arrows.svg"></li>
							<li><img src="resources/img/icon/calendar.svg"></li>
						</ul>
					</div>
				</article>
			</section>
			<section id="day-view">
				<article>

				</article>
			</section>
			<section id="mini-calendar">
				<article>
					<div class="datepicker-here" data-language='en'></div>
				</article>
			</section>
		</div>
 -->
 		<div id="calendar"></div>

    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    	<script src='server/libs/moment.min.js'></script>
		<script src='server/js/fullcalendar.js'></script>
		<script src="server/js/general.js"></script>
		<script src="server/js/schedule.js"></script>
<?php
	echo pageClose();
	}
?>