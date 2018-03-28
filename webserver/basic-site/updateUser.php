<?php
  include_once("common.php");
  if(isset($_COOKIE['sessionID'])){
  	$sessionID = $_COOKIE['sessionID'];
  	if(isAuthenticated($sessionID)){
      $username = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);
      $email = htmlspecialchars($_POST['email']);
      $name = htmlspecialchars($_POST['name']);
      $profileImage = htmlspecialchars($_POST['profileImage']);


      $updateResponse = Update($sessionID, $username, $password, $email, $name, $profileImage);
      if (!($updateResponse['response'] == "true")){
        die("False - Invalid User");
      }else{
        die("True - Update Successful");
      }
  	}
  }
  header("Set-Cookie: sessionID=deleted; expires=Thu, 01 Jan 1970 00:00:00 GMT");
  header("Location: https://localhost/index.php");
  exit();
 ?>
