<?php
  include_once("common.php");

  if(isset($_COOKIE['sessionID'])){
  	$sessionID = $_COOKIE['sessionID'];
  	if(isAuthenticated($sessionID)){
      $response = GetSkits($sessionID);
      if ($response['success']){
        die("True - ".$response['skits']);
      }else{
        die("False - Unable to Retrieve Skits");
      }
    }
  }
  die("False - Not Authenticated");
?>
