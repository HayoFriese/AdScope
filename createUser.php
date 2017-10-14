<?php
	include "server/db.php";

?>

<html>
<head></head>
<body>
	<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$username = isset($_POST['username']) ? $_POST['username'] : null;
			$role = isset($_POST['role']) ? $_POST['role'] : null;
			$salary = isset($_POST['salary']) ? $_POST['salary'] : null;
			$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : null;
			$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : null;
			$email = isset($_POST['email']) ? $_POST['email'] : null;
			$tel = isset($_POST['tel']) ? $_POST['tel'] : null;
			$dob = isset($_POST['dob']) ? $_POST['dob'] : null;

			$password = isset($_POST['password']) ? $_POST['password'] : null;
			$password = trim($password);
	        $password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			$passhash = password_hash($password, PASSWORD_DEFAULT);

			$birthday = date("Y-m-d", strtotime($dob));
			$created = date("Y-m-d H:i:s");

			$status = "Offline";

			$type = "Colleague";
			$adline1 = "Company Road";
			$adline2 = "Building 2";
			$postcode = "NE1 6NE";
			$city = "Newcastle Upon Tyne";
			$country = "United Kingdom";

			$sql = "INSERT INTO user(username, password, salary, role, firstname, lastname, email, tel, created, dateofbirth, status) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt, "sssssssssss", $username, $passhash, $salary, $role, $firstname, $lastname, $email, $tel, $created, $birthday, $status) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt);

    		$id = mysqli_insert_id($conn);

    		$sql4 = "INSERT INTO contact(type, firstname, lastname, position, email, phone, adline1, adline2, postcode, city, country) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt4 = mysqli_prepare($conn, $sql4) or die(mysqli_error($conn));
    		mysqli_stmt_bind_param($stmt4, "sssssssssss", $type, $firstname, $lastname, $role, $email, $tel, $adline1, $adline2, $postcode, $city, $country) or die(mysqli_error($conn));
    		mysqli_stmt_execute($stmt4) or die(mysqli_error($conn));
    		mysqli_stmt_close($stmt4);

    		$id2 = mysqli_insert_id($conn);

    		foreach($_FILES['img']['name'] as $f => $name){
					$target_dir = ("resources/img/profile-pics/".$id."/");
	   		 		$dir = ("resources/img/profile-pics/".$id."/");
					
					$allowedExts = array("gif", "jpeg", "jpg", "png", "PNG", "JPG", "JPEG", "GIF");
					$temp = explode(".", $name);
					$extension = end($temp);
	
					if ((($_FILES['img']['type'][$f] == "image/gif") || ($_FILES['img']['type'][$f] == "image/GIF") 
					|| ($_FILES['img']['type'][$f] == "image/jpeg") || ($_FILES['img']['type'][$f] == "image/JPEG") 
					|| ($_FILES['img']['type'][$f] == "image/jpg") || ($_FILES['img']['type'][$f] == "image/JPG") 
					|| ($_FILES['img']['type'][$f] == "image/png") || ($_FILES['img']['type'][$f] == "image/PNG"))
					&& ($_FILES['img']['size'][$f] < 1073741824)
					&& in_array($extension, $allowedExts)){
						if($_FILES['img']['error'][$f] > 0){
							echo "There was a problem uploading your profile picture. Return Code: ".$_FILES['img']['error'][$f]."<br />";
						} else {
							if(file_exists($target_dir)){
							} else {
								mkdir($target_dir, 0777, true);
							}
							if(file_exists($target_dir.$name)){
								$pathname = $dir.$name;
							} else {
								$names = $_FILES['img']['tmp_name'][$f];
								if(move_uploaded_file($names, "$target_dir/$name")){
									$pathname = ($dir.$name);

									$sqlimg = "UPDATE user SET user.src = ? WHERE iduser = ?";
									$stmt = mysqli_prepare($conn, $sqlimg) or die(mysqli_error($conn));
    								mysqli_stmt_bind_param($stmt, "sd", $pathname, $id) or die(mysqli_error($conn));
    								mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    								mysqli_stmt_close($stmt);

    								$sqlimg2 = "UPDATE contact SET contact.src = ? WHERE idcontact = ?";
									$stmt2 = mysqli_prepare($conn, $sqlimg2) or die(mysqli_error($conn));
    								mysqli_stmt_bind_param($stmt2, "sd", $pathname, $id2) or die(mysqli_error($conn));
    								mysqli_stmt_execute($stmt2) or die(mysqli_error($conn));
    								mysqli_stmt_close($stmt2);
								}
							}
						}
					}
	   		 			
				}
			

    		echo "done";
		} else {
			echo "<form action=\"createUser.php\" method=\"post\" enctype=\"multipart/form-data\">
		<input type=\"text\" name=\"username\" placeholder=\"username\">
		<input type=\"text\" name=\"password\" placeholder=\"password\">
		<input type=\"text\" name=\"salary\" placeholder=\"salary\">
		<input type=\"text\" name=\"role\" placeholder=\"role\">
		<input type=\"text\" name=\"firstname\" placeholder=\"firstname\">
		<input type=\"text\" name=\"lastname\" placeholder=\"lastname\">
		<input type=\"text\" name=\"email\" placeholder=\"email\">
		<input type=\"text\" name=\"tel\" placeholder=\"tel\">
		<input type=\"date\" name=\"dob\" placeholder=\"dob\">
		<input type=\"file\" name=\"img[]\" />
		

		<input type=\"submit\" name=\"submit\">
	</form>";
		}
	?>
	
</body>
</html>