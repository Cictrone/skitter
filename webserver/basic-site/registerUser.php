<?php
  include_once("common.php");

  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);
  $email = htmlspecialchars($_POST['email']);
  $name = htmlspecialchars($_POST['name']);

  $registerResponse = Register($username, $password, $email, $name);
  fwrite(STDOUT, implode($registerResponse));
  if (!($registerResponse['response'] == "true")){
    if ($registerResponse['status_code'] == "409"){
      die("False - User Already Exists");
    }
    die("False - Invalid RIT Credentials");
  }else{
    die("True - Registration Successful");
  }
 ?>
