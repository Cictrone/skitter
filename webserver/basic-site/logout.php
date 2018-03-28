<?php
  include_once("common.php");

  if(isset($_COOKIE['sessionID'])){
  	$sessionID = $_COOKIE['sessionID'];
  	if(!isAuthenticated($sessionID)){
      die("True - Not Authenticated");
    }
  }
  $response = Logout($sessionID);
  if ($response) {
    die("True - Logout Successful");
  }
  die("False - Logout Unsuccessful");
?>
