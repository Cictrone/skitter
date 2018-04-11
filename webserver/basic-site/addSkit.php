<?php
  include_once("common.php");

  if(isset($_COOKIE['sessionID'])){
  	$sessionID = $_COOKIE['sessionID'];
  	if(isAuthenticated($sessionID)){
      $response = AddSkit($sessionID, htmlspecialchars($_POST['skit_message']));
      if ($response['success']){
        die("True - ".$response['_id']);
      }else{
        die("False - Bad Skit Recieved");
      }
    }
  }
  die("False - Not Authenticated");
?>
