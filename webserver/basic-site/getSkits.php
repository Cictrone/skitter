<?php
  include_once("common.php");

  if(isset($_COOKIE['sessionID'])){
  	$sessionID = $_COOKIE['sessionID'];
  	if(isAuthenticated($sessionID)){
      $response = GetSkits($sessionID);
      if ($response['success']){
        if (empty($response['skits'])){
          die("True - []")
        }else{
          die("True - ".implode($response['skits']));          
        }
      }else{
        die("False - Unable to Retrieve Skits");
      }
    }
  }
  die("False - Not Authenticated");
?>
