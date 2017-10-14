<?php
	include("db.php");
	require('libs/fpdf/fpdf.php');


	if(isset($_REQUEST['projectid'])){

		$pid = $_REQUEST['projectid'];

		class invoice extends FPDF { 

		    public function __construct ($orientation = 'P', $unit = 'pt', $format = 'Letter', $margin = 40) { 
		        parent::__construct($orientation, $unit, $format, $margin);
		        //$this->FPDF($orientation, $unit, $format); 
		        $this->SetTopMargin($margin); 
		        $this->SetLeftMargin($margin); 
		        $this->SetRightMargin($margin); 
		        $this->SetAutoPageBreak(true, $margin); 
		    } 

		    function HeaderSet($uname, $umail, $utel) { 
			    $this->SetFont('Helvetica', '', 6); 
			    $this->SetTextColor(100); 
			    $this->SetY(10);
			    $this->Cell(70, 30, $uname, 0, 0);
			    $this->Cell(40, 30, "-", 0, 0);
			    $this->Cell(80, 30, $utel, 0, 0);

			    $this->SetFont('Helvetica', 'BI', 6);
			    $this->SetTextColor(0); 
			    $this->Cell(0, 30, $umail, 0, 1, 'R');
			    $this->SetDrawColor(225);
			    $this->Cell(0, 10, "", 'T', 1);
			    //$this->Cell(0, 30, "Nettuts+ Online Store", 0, 1, 'C', true); 
			}
			function To($cname, $r, $g, $b, $cadline1, $cadline2, $cpostcode, $ccity, $ccountry){
				$this->SetY(60);
				$this->SetX(40);
				$this->SetTextColor(210);
				$this->SetFont('Helvetica', '', 9);
				$this->Cell(0, 14, "Billed To", 0, 2, 'L');  
				$this->SetFont('Helvetica', 'BI', 9);
				$this->SetTextColor($r, $g, $b);
				$this->Cell(0, 14, $cname, 0, 2, 'L');
				$this->SetTextColor(0);
				$this->SetFont('Helvetica', 'I', 9);
				$this->Cell(0, 14, $cadline1, 0, 2, 'L');
				if($cadline2 != "" || $cadline2 != null){
					$this->Cell(0, 14, $cadline2, 0, 2, 'L'); 
				} 
				$this->Cell(0, 14, $cpostcode.", ".$ccity, 0, 2, 'L'); 
				$this->Cell(0, 14, $ccountry, 0, 2, 'L'); 
				$this->Ln(100);
			}
	
			function Compiled($projectid, $ptotalcost){
				$this->SetY(73);
				
    			  
				$this->SetFont('Helvetica', '', 9);
				$this->SetTextColor(0, 0, 0);
				$this->SetX(-250);
				$this->Cell(0, 15, 'Invoice Ref:', 0, 0, 'L');
				$this->SetX(-240);
				$this->Cell(200, 15, $projectid, 0, 2, 'R');
	
				$this->SetX(-250);
				$this->Cell(0, 15, 'Invoice Date:', 0, 0, 'L');
				$this->SetX(-240);
				$this->Cell(200, 15, date("M j, Y"), 0, 2, 'R');
	
				$this->SetX(-250);
				$this->SetFillColor(225, 225, 225);
				$this->SetFont('Helvetica', 'B', 9);
				$this->Cell(0, 21, 'Invoice Ref:', 0, 0, 'L', true);
				$this->Cell(0, 21, '$'.$ptotalcost, 0, 0, 'R', true);
				$this->Ln(100);
			}

			function CostTable($r, $g, $b, $products, $prices, $tax){
				$tax = number_format($tax, 2);
				$this->SetFont('Helvetica', 'B', 24);
				$this->SetTextColor($r, $g, $b);
				$this->Cell(0, 50, "Invoice Summary", 0, 2, 'L');

				$this->SetFont('Helvetica', 'BI', 9); 
				$this->SetTextColor(215, 215, 215);  
				$this->SetLineWidth(0.2); 
				$this->Cell(427, 23, "Description", 'B', 0, 'L'); 
				$this->Cell(100, 23, "Price", 'B', 1, 'R'); 
				$this->Ln(2);
 
				$this->SetFont('Helvetica', '', 7); 
				$this->SetFillColor(245, 245, 245); 
				$this->SetLineWidth(0.1); 
				$fill = false; 

				$this->SetTextColor(0);
 
				for($i = 0; $i < count($products); $i++) { 
				    $this->Cell(427, 20, $products[$i], 0, 0, 'L', $fill); 
				    $this->Cell(100, 20, '$' . number_format($prices[$i], 2), 8, 1, 'R', $fill); 
				    $fill = !$fill; 
				}
				$this->Ln(2);
				$this->Cell(0, 0, "", 'B', 1);
				$this->SetFont('Helvetica', '', 9); 
				$this->Cell(427, 16, "Subtotal", 0, 0, 'R'); 
				$this->Cell(100, 16, '$' . number_format(array_sum($prices), 2), 0, 1, 'R'); 

				$this->Cell(427, 16, "Retainer Applied", 0, 0, 'R'); 
				$this->Cell(100, 16, '$'.$tax, 0, 1, 'R'); 

				$final = number_format(($tax+array_sum($prices)), 2);
				$this->SetFillColor(210, 210, 210);
				$this->SetDrawColor(210, 210, 210);
				$this->SetFont('Helvetica', 'B', 10);
				$this->Cell(427, 30, "Subtotal", 1, 0, 'R', true); 
				$this->Cell(100, 30, '$'.$final, 1, 0, 'R', true); 
				$this->Cell(5, 30, "", 1, 1, '', true);
			}

		}
	
		$sqlProject = "SELECT id, title, client, totalcost, user.firstname, user.lastname, user.email, user.tel FROM project
		INNER JOIN user ON project.projectmanager = user.iduser WHERE idproject = '$pid'";
		$rProject = mysqli_query($conn, $sqlProject) or die(mysqli_error($conn));
		while($rowP = mysqli_fetch_assoc($rProject)){
			$projectid = $rowP['id'];
			$projecttitle = $rowP['title'];
			$clientid = $rowP['client'];
			$projectcost = number_format($rowP['totalcost'], 2);
			$projectmanager = $rowP['firstname']." ".$rowP['lastname'];
			$manageremail = $rowP['email'];
			$managertel = $rowP['tel'];
		}

		$sqlAddress = "SELECT adline1, adline2, postcode, city, country, client.name, client.hex FROM contact
		INNER JOIN client ON contact.client = client.idclient 
		WHERE contact.client = '$clientid' LIMIT 1";
		$rAddress = mysqli_query($conn, $sqlAddress) or die(mysqli_error($conn));
		while($rowA = mysqli_fetch_assoc($rAddress)){
			$adline1 = $rowA['adline1'];
			$adline2 = $rowA['adline2'];
			$postcode = $rowA['postcode'];
			$city = $rowA['city'];
			$country = $rowA['country'];
			$clientname = $rowA['name'];
			$clienthex = $rowA['hex'];
			$clienthex = str_replace("#","",$clienthex);
			if(strlen($clienthex) == 3) {
    			$r = hexdec(substr($clienthex,0,1).substr($clienthex,0,1));
    			$g = hexdec(substr($clienthex,1,1).substr($clienthex,1,1));
    			$b = hexdec(substr($clienthex,2,1).substr($clienthex,2,1));
   			} else {
    			$r = hexdec(substr($clienthex,0,2));
    			$g = hexdec(substr($clienthex,2,2));
    			$b = hexdec(substr($clienthex,4,2));
   			}
		}
		$descarr = [];
		$costarr = [];

		$sqlTask = "SELECT task, cost FROM task WHERE project = '$pid'";
		$rTask = mysqli_query($conn, $sqlTask) or die(mysqli_error($conn));
		while($rowT = mysqli_fetch_assoc($rTask)){
			$taskname = "Task: ".$rowT['task'];
			$taskcost = $rowT['cost'];

			array_push($descarr, $taskname);
			array_push($costarr, $taskcost);
		}

		$sqlEvent = "SELECT event, cost FROM event WHERE project = '$pid'";
		$rEvent = mysqli_query($conn, $sqlEvent) or die(mysqli_error($conn));
		while($rowE = mysqli_fetch_assoc($rEvent)){
			$eventname = "Scheduled Event: ".$rowE['event'];
			$eventcost = $rowE['cost'];

			array_push($descarr, $eventname);
			array_push($costarr, $eventcost);
		}
		//Added Cost
		$tax = 250;
		$subtotal = number_format(array_sum($costarr)+$tax, 2);


		//Create a new PDF Page
		$pdf = new invoice(); 
		$pdf->AddPage(); 
		$pdf->headerSet($projectmanager, $manageremail, $managertel);
		$pdf->To($clientname, $r, $g, $b, $adline1, $adline2, $postcode, $city, $country);
		$pdf->Compiled($projectid, $subtotal);
		$pdf->CostTable($r, $g, $b, $descarr, $costarr, $tax);
		$pdf->Output();
	}
?>