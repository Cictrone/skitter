<?php
  include_once("common.php");

  if(isset($_COOKIE['sessionID'])){
  	$sessionID = $_COOKIE['sessionID'];
  	if(isAuthenticated($sessionID)){
      $response = RemoveSkit($sessionID, htmlspecialchars($_POST['skit_id']));
      if ($response['success']){
        die("True - Skit Removed");
      }else{
        if($response['status_code'] == "400"){
          die("False - Request was Malformed")
        }else{
          die("False - Unable to Remove Skit");
        }
      }
    }
  }
  die("False - Not Authenticated");
?>
