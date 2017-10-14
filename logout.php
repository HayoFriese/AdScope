<?php
  //Initiate the session, save path outside directory folder in a safe location that can't be accessed easily.
  // ini_set("session.save_path", "../sessionData");
  include "server/db.php";
  ini_set("session.save_path", "../sessionData");

  //Start session, tracking activity and storing it in the session directory.
  session_start(); 

  //Gathers all existing session data.
  $uname = $_SESSION['uname'];

  $q = "SELECT status FROM user WHERE user.username = '$uname'";
  $r = mysqli_query($conn, $q) or die(mysqli_error($conn));
  $statusnow = mysqli_fetch_assoc($r)['status'];

  if($statusnow != "Out of Office"){
    $sql = "UPDATE user SET user.status = 'Offline' WHERE user.username = ?";
    $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn)); 
    mysqli_stmt_bind_param($stmt, "s", $uname) or die(mysqli_error($conn)); 
    mysqli_stmt_execute($stmt) or die(mysqli_error($conn)); 
  }

  $_SESSION['logged-in'] = false;

  $_SESSION = array();    

  //Destroys session.
  session_destroy(); 

  //returns to previous url that the user was on.
  header("location: signin.php");

?>