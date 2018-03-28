<?php
include_once("common.php");

if(isset($_COOKIE['sessionID'])){
	$sessionID = $_COOKIE['sessionID'];
	if(isAuthenticated($sessionID)){
		header("Location: https://localhost/home.php");
		exit();
	}else{
		header("Set-Cookie: sessionID=deleted; expires=Thu, 01 Jan 1970 00:00:00 GMT");
		header("Location: https://localhost/index.php");
		exit();
	}
}else{
	echo '
	<!Doctype html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<title>Skitter</title>
			<script type="text/javascript" src="js/jquery.min.js"></script>
			<script type="text/javascript" src="js/semantic.min.js"></script>
			<link rel="stylesheet" src="css/semantic.min.css" />
			<link rel=icon href=images/skitter_s.png>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/message.min.css" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/input.min.css" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/header.min.css" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/icon.min.css" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/grid.min.css" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/segment.min.css" />
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/form.min.css" />
			<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/form.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/button.min.css" />
		</head>
		<body>
		<script type="text/javascript">
		$(document).ready(function() {
			$("#usernameInput").keypress(function(event){
				if(event.which == 13){ // pressed "enter"
					$("#loginButton").click();
				}
			});
			$("#passwordInput").keypress(function(event){
				if(event.which == 13){ // pressed "enter"
					$("#loginButton").click();
				}
			});
			$("#loginButton").click(function(event){
				event.preventDefault();
				var data = {"username": $("#usernameInput")[0].value, "password": $("#passwordInput")[0].value};
				$.post( "login.php", data)
					.done(function( data ) {
						if(data.search("False - ") != -1){
						var error = data.substring(data.search(" - ")+3,data.length);
						$("#loginForm").attr("class", "ui large form error");
						$("#loginMsg").attr("class", "ui error message");
						$("#loginErrorTitle").html("Login Failed");
						$("#loginErrorMessage").html(error);
					}else if(data.search("True - ") != -1){
						location.assign("/home.php");
					}else{
						$("#loginMsg").css( "color", "red" )
						$("#loginMsg").html("There was an error logging you in, contact the administrator");
					}
					});
			});
		});
		</script>
			<div class="ui middle aligned center aligned grid">
				<div class="five wide column">
					<h2 class="ui pink image header">
						<div class="content">
							Login to Skitter
						</div>
					</h2>
					<form id="loginForm" class="ui large form">
						<div id="login" class="ui stacked segment">
							<div class="field">
								<div class="ui left icon input">
									<i class="user icon"></i>
									<input id="usernameInput" name="username" type="text" placeholder="Username">
								</div>
							</div>
							<div class="field">
			          <div class="ui left icon input">
			            <i class="lock icon"></i>
			            <input id="passwordInput" type="password" name="password" placeholder="Password">
			          </div>
		  				</div>
							<div id="loginMsg"><div id="loginErrorTitle" class="header"></div><p id="loginErrorMessage"></p></div>
							<button id="loginButton" class="ui fluid large pink submit button">Login</button>
						</div>
					</form>
					<div class="ui message">
						New to Skitter? <a href="/register.php">Sign Up</a>
					</div>
				</div>
			</div>
		</body>
	</html>
	';
}

 ?>
