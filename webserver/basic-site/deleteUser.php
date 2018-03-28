<?php
  include_once("common.php");
  if(isset($_COOKIE['sessionID'])){
  	$sessionID = $_COOKIE['sessionID'];
  	if(isAuthenticated($sessionID)){
      $deleteResponse = Delete($sessionID);
      if (!($deleteResponse['response'] == "true")){
        die("False - Invalid User");
      }else{
        die("True - Delete Successful");
      }
  	}
  }
  header("Set-Cookie: sessionID=deleted; expires=Thu, 01 Jan 1970 00:00:00 GMT");
  header("Location: https://localhost/index.php");
  exit();
 ?>
