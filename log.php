<?php
	ini_set("session.save_path", "../sessionData");
	session_start();
  	include "server/db.php";
	include "functions.php";
	if((!isset($_SESSION['logged-in']) && $_SESSION['logged-in'] != true)){
	    header("Location: signin.php");
  	} else{	
	echo pageIni("Financial Logs - Adscope");
?>
	<body id="back-end-pages">
	<?php
		echo nav("", "", "", "", "", " id=\"active\"", "", "");
		echo head("Financial Logs", $_SESSION['username'], $_SESSION['iduser']);
	?>
		<div id="back-end-body" class="log">
			<section id="finance-body">
				<?php
					$sqlP = "SELECT idproject, title, client, client.name, client.hex FROM project
					INNER JOIN client ON project.client = client.idclient
					ORDER BY project.started DESC";
					$rP = mysqli_query($conn, $sqlP) or die(mysqli_error($conn));
					if(mysqli_num_rows($rP) > 0){
						while($rowP = mysqli_fetch_assoc($rP)){
							$idproject = $rowP['idproject'];
							$projecttitle = $rowP['title'];
							$clientname = $rowP['name'];
							$clientid = $rowP['client'];
							$clientcolor = $rowP['hex'];

							echo "
							<article class=\"f-project\">
								<div>
									<div>
										<h2>$projecttitle</h2>
										<div></div>
										<h2 style=\"color:$clientcolor\">$clientname</h2>
									</div>
									<a href=\"#\" data-id=\"$idproject\"><img src=\"resources/img/icon/download.svg\"></a>
								</div>
								<div class=\"fin-data\">
									<ul id=\"divisor\" class=\"project-divisor\">
										<li id=\"active\"><a href=\"#\">Loggers</a></li>
										<li><a href=\"#\">Tasks</a></li>
										<li><a href=\"#\">Events</a></li>
										<li><a href=\"#\">Added Costs</a></li>
									</ul>
									<div id=\"fin-logger\">
										<ul id=\"table-head\">
											<li>Log Name</li>
											<li>Task</li>
											<li>Done By</li>
											<li>Time Length Logged</li>
											<li>Cost</li>
										</ul>";
											$ltotalcost = 0;
											$ttt = 0;
			
											$sqlFinL = "SELECT logger, user.firstname, user.lastname, task.task, timerstart, timerend, pauselength, logger.cost FROM logger 
												INNER JOIN task ON logger.task = task.idtask
											INNER JOIN user ON logger.user = user.iduser
											WHERE logger.project = '$idproject'";
			
											$rFinL = mysqli_query($conn, $sqlFinL) or die(mysqli_error($conn));
											if(mysqli_num_rows($rFinL) > 0){
												echo "<div id=\"table-body\">";

												while($rowFL = mysqli_fetch_assoc($rFinL)){
													$llogname = $rowFL['logger'];
													$ltaskname = $rowFL['task'];
			
													$start = date("H:i:s", strtotime($rowFL['timerstart']));
														$startE = explode(":", $start);
														$end = date("H:i:s", strtotime($rowFL['timerend']));
														$endE = explode(":", $end);
														$pausel = date("H:i:s", strtotime($rowFL['pauselength']));
														$pauselE = explode(":", $pausel);
					
														$s = ($startE[0]*60*60)+($startE[1]*60)+($startE[2]);
														$e = ($endE[0]*60*60)+($endE[1]*60)+($endE[2]);
														$math = $e-$s-$pausel;
														$ttt += $math;
			
														$sc = $math % 60;
														if($sc < 10){
															$sc = "0".$sc;
														}
			
														$mn = (($math - $sc) / 60) % 60;
														if($mn < 10){
															$mn = "0".$mn;
														}
			
														$hr = (($math - $sc - $mn*60) / 60) / 60 % 60;
														if($hr < 10){
															$hr = "0".$hr;
														}
			
														$ltime = $hr.":".$mn.":".$sc;
				
													$lby = $rowFL['firstname']." ".$rowFL['lastname'];
				
													$lcost = $rowFL['cost'];
													if($lcost === 0 || $lcost === null){
														$lcost = "0.00";
													}
													$ltotalcost += $lcost;
													$ltotalcost = round($ltotalcost, 2);
													echo "<ul>
														<li>$llogname</li>
														<li>$ltaskname</li>
														<li>$lby</li>
														<li>$ltime</li>
														<li>&pound;$lcost</li>
													</ul>";
												}
												$tts = ($ttt % 60);
												if($tts < 10){
													$tts = "0".$tts;
												}
												$ttm = (($ttt - $tts) / 60) % 60;
												if($ttm < 10){
													$ttm = "0".$ttm;
												}
												$tth = (($ttt - $tts - $mn*60) / 60) / 60 % 60;
												if($tth < 10){
													$tth = "0".$tth;
												}
												$ttime = $tth.":".$ttm.":".$tts;

												if($ltotalcost === 0){
													$ltotalcost = "0.00";
												} else{
													$ltotalcost = number_format($ltotalcost, 2);
												}
												echo "</div>
												<div id=\"table-footer\">
													<ul>
														<li><br /></li>
														<li><br /></li>
														<li>Total:</li>
														<li>$ttime</li>
														<li>&pound;$ltotalcost</li>
													</ul>
												</div>";
											}
									echo "</div>

									<div id=\"fin-task\">
										<ul id=\"table-head\">
											<li>Task Name</li>
											<li>Added By</li>
											<li>Status</li>
											<li>Completed On</li>
											<li>Cost</li>
										</ul>";
											$ttotalcost = 0;
			
											$sqlFinT = "SELECT task, user.firstname, user.lastname, done, completedon, cost FROM task 
											INNER JOIN user ON task.postedby = user.iduser 
											WHERE task.project = '$idproject'";
											$rFinT = mysqli_query($conn, $sqlFinT) or die(mysqli_error($conn));
											if(mysqli_num_rows($rFinT) > 0){
												echo "<div id=\"table-body\">";
												while($rowFT = mysqli_fetch_assoc($rFinT)){
														$ftaskname = $rowFT['task'];
														$ftname = $rowFT['firstname']." ".$rowFT['lastname'];
														$ftdone = $rowFT['done'];
														if($ftdone === "1"){
															$ftstatus = "Completed";
														} else {
															$ftstatus = "Active";
														}
														
														$ftcompletedon = $rowFT['completedon'];
														$ftcost = $rowFT['cost'];
														$ttotalcost += $ftcost;
														$ttotalcost = round($ttotalcost, 2);
														if($ftcost === 0 || $ftcost === null){
															$ftcost = "0.00";
														}
														echo "<ul>
															<li>$ftaskname</li>
															<li>$ftname</li>
															<li>$ftstatus</li>
															<li>$ftcompletedon</li>
															<li>&pound;$ftcost</li>
														</ul>";
													}
													if($ttotalcost === 0){
														$ttotalcost = "0.00";
													}else{
														$ttotalcost = number_format($ttotalcost, 2);
													}
												echo "</div>
													<div id=\"table-footer\">
														<ul>
															<li><br /></li>
															<li><br /></li>
															<li><br /></li>
															<li>Total Cost:</li>
															<li>&pound;$ttotalcost</li>
														</ul>
													</div>";
											}		
									echo "</div>

									<div id=\"fin-events\">
										<ul id=\"table-head\">
											<li>Event Title</li>
											<li>Added By</li>
											<li>Started</li>
											<li>Ended</li>
											<li>Cost</li>
										</ul>";
											$etotalcost = 0;
			
											$sqlFinE = "SELECT event, startdate, starttime, enddate, endtime, user.firstname, user.lastname, cost FROM event 
											INNER JOIN user ON event.eventby = user.iduser 
											WHERE event.project = '$idproject'";
											$rFinE = mysqli_query($conn, $sqlFinE) or die(mysqli_error($conn));
											if(mysqli_num_rows($rFinE) > 0){
												echo "<div id=\"table-body\">";
												while($rowFE = mysqli_fetch_assoc($rFinE)){
												$feventname = $rowFE['event'];
												$fename = $rowFE['firstname']." ".$rowFE['lastname'];
												$festart = $rowFE['startdate']." ".$rowFE['starttime'];
												$feend = $rowFE['enddate']." ".$rowFE['endtime'];
			
												$fecost = $rowFE['cost'];
												$etotalcost += $fecost;
												$etotalcost = round($etotalcost, 2);
												
												if($fecost === "0" || $fecost === null){
													$fecost = "0.00";
												}

												echo "<ul>
													<li>$feventname</li>
													<li>$fename</li>
													<li>$festart</li>
													<li>$feend</li>
													<li>&pound;$fecost</li>
												</ul>";
											}
											if($etotalcost === 0){
												$etotalcost = "0.00";
											}else{
												$etotalcost = number_format($etotalcost, 2);
											}
											echo "</div>
												<div id=\"table-footer\">
													<ul>
														<li><br /></li>
														<li><br /></li>
														<li><br /></li>
														<li>Total Cost:</li>
														<li>&pound;$etotalcost</li>
													</ul>
												</div>";
											}
								echo "</div>
							</article>";
						}
					} else {
						echo "<h1 id=\"comingsoon\">No available projects, hence no invoices!</h1>";
					}
				?>
				
			</section>
		</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="server/js/general.js"></script>
	<script src="server/js/log.js"></script>
<?php
	echo pageClose();
	}
?>