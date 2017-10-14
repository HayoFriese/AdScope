<?php
    ini_set("session.save_path", "../sessionData");
    session_start();
    include "server/db.php";
	include "functions.php";

	if(isset($_POST['sign-in'])){
		$username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;
        $password = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;

        $username = trim($username);
        $password = trim($password);
        $password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $errors = [];

        if(empty($username)) {
            $errors[]="You have not entered a username";
        } else {
            $sqlusername = "SELECT * FROM user WHERE user.username = '$username'";
            $rusername = mysqli_query($conn, $sqlusername) or die(mysqli_error($conn));
            if(mysqli_num_rows($rusername) === 0){
                $errors[]="Username Doesn't Exist";
            }
        }

        if(empty($password)) {
            $errors[]="You have not entered a password";
        }

         if(!empty($errors)){
            echo pageIni("Sign In - Adscope");
            echo "<body id=\"sign-in-cont\">";
            echo signIn($errors);
            echo pageClose();
        } else {
       	    $sql = "SELECT iduser, password, salary, firstname, lastname, status FROM user WHERE username = '$username'";

       	    $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn)); 
            mysqli_stmt_execute($stmt) or die(mysqli_error($conn)); 
            mysqli_stmt_bind_result($stmt, $uid, $passHash, $salary, $firstname, $lastname, $stat) or die(mysqli_error($conn));

            if (mysqli_stmt_fetch($stmt)){
                mysqli_stmt_close($stmt);
    
                if(password_verify($password, $passHash)){
                    $status = "Online";
    
                    $_SESSION['logged-in'] = true;
                    $_SESSION['username'] = $firstname . " " . $lastname;
                    $_SESSION['iduser'] = $uid;
                    $_SESSION['salary'] = $salary;
                    $_SESSION['uname'] = $username;
    
                    if($stat != "Out of Office"){
                        $sqlS = "UPDATE user SET user.status = ? WHERE user.username = ?";
                        $stmt2 = mysqli_prepare($conn, $sqlS) or die(mysqli_error($conn)); 
                        mysqli_stmt_bind_param($stmt2, "ss", $status, $username) or die(mysqli_error($conn)); 
                        mysqli_stmt_execute($stmt2) or die(mysqli_error($conn)); 
                        mysqli_stmt_close($stmt2);
                    }
    
                    if($_SESSION['logged-in'] = true){
                        header("location: dashboard.php");
                    }

                } else{
                    $errors[]="Password Incorrect";
                    echo pageIni("Sign In - Adscope");
                    echo "<body id=\"sign-in-cont\">";
                    echo signIn($errors);
                    echo pageClose();      
                }   
            } else{
                mysqli_stmt_close($stmt);
                echo "Oops! Something Went Wrong!";
            }
        }
    } else {
        header("location: signin.php");
    }
?>