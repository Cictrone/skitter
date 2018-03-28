<?php
  include_once("common.php");

  $username = $_POST['username'];
  $password = $_POST['password'];
  $loginResponse = Login($username, $password);
  if (!$loginResponse){
    die("False - Username or password was invalid");
  }
  header("Set-Cookie: ".$loginResponse);
  die("True - login successful");

 ?>
