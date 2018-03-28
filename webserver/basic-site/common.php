<?php
  if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'w'));

  function isAuthenticated($sessionID){
    fwrite(STDOUT, 'Checking if Authenticated....');
    $curl = curl_init("http://skitter-auth/isAuthenticated");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cookie: sessionID=".$sessionID));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $json = json_decode($result, true);
    return $json['response'] == "true";
  }

  function GetUserData($sessionID){
    fwrite(STDOUT, 'Getting User Data....');
    $curl = curl_init("http://skitter-auth/GetUserData");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cookie: sessionID=".$sessionID));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $json = json_decode($result, true);
    return $json;
  }

  function Logout($sessionID){
    fwrite(STDOUT, 'Logout attempted....');
    $curl = curl_init("http://skitter-auth/Logout");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cookie: sessionID=".$sessionID));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $json = json_decode($result, true);
    return $json['response'] == "true";
  }

  function Register($username, $password, $email, $name){
    fwrite(STDOUT, 'Registration attempted....');
    // do some validation beforte sending to API
    $params = 'username='.$username."&password=".$password."&email=".$email."&displayName=".$name;
    $curl = curl_init("http://skitter-auth/RegisterUser");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $json = json_decode($result, true);
    $json['status_code'] = $httpcode;
    return $json;
  }

  function Update($sessionID, $username, $password, $email, $name, $profileImage){
    fwrite(STDOUT, 'Update attempted....');
    // do some validation beforte sending to API
    $params = 'username='.$username."&password=".$password."&email=".$email."&displayName=".$name."&profileImage=".$profileImage;
    $curl = curl_init("http://skitter-auth/UpdateUser");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cookie: sessionID=".$sessionID));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $json = json_decode($result, true);
    $json['status_code'] = $httpcode;
    return $json;
  }

  function Delete($sessionID){
    fwrite(STDOUT, 'Delete attempted....');
    $curl = curl_init("http://skitter-auth/DeleteUser");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cookie: sessionID=".$sessionID));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $json = json_decode($result, true);
    $json['status_code'] = $httpcode;
    return $json;
  }

  function Login($username, $password){
    fwrite(STDOUT, 'Login attempted....');
    $curl = curl_init("http://skitter-auth/Login");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_USERPWD, $username.":".$password);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    $result = curl_exec($curl);
    $info = curl_getinfo($curl);
    $body = (isset($info["header_size"]))?substr($result,$info["header_size"]):"";
    if ($body !== ""){
      $json_response = json_decode($body, true)['response'];
      if ($json_response){
        preg_match('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        return $matches[1];
      }
    }
    return null;
  }
?>
