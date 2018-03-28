<?php
include_once("common.php");

if(isset($_COOKIE['sessionID'])){
	$sessionID = $_COOKIE['sessionID'];
	if(isAuthenticated($sessionID)){
		header("Location: /home.php");
		exit();
	}else{
		header("Set-Cookie: sessionID=deleted; expires=Thu, 01 Jan 1970 00:00:00 GMT");
		header("Location: /index.php");
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
					$("#registerButton").click();
				}
			});
			$("#passwordInput").keypress(function(event){
				if(event.which == 13){ // pressed "enter"
					$("#registerButton").click();
				}
			});
      $("#emailInput").keypress(function(event){
				if(event.which == 13){ // pressed "enter"
					$("#registerButton").click();
				}
			});
      $("#nameInput").keypress(function(event){
				if(event.which == 13){ // pressed "enter"
					$("#registerButton").click();
				}
			});
			$("#registerButton").click(function(event){
				event.preventDefault();
				var username = encodeURIComponent($("#usernameInput")[0].value);
				var password = encodeURIComponent($("#passwordInput")[0].value);
				var email = encodeURIComponent($("#emailInput")[0].value);
				var name = encodeURIComponent($("#nameInput")[0].value);
				var data = {"username": username, "password": password, "email": email, "name": name};
				$("#usernameInputDiv").attr("class", "ui left icon input loading");
				$("#passwordInputDiv").attr("class", "ui left icon input loading");
				$("#emailInputDiv").attr("class", "ui left icon input loading");
				$("#nameInputDiv").attr("class", "ui left icon input loading");
				$.post( "registerUser.php", data)
					.done(function( data ) {
						if(data.search("False - ") != -1){
							var error = data.substring(data.search(" - ")+3,data.length);
							$("#registerForm").attr("class", "ui large form error");
							$("#registerMsg").attr("class", "ui error message");
							$("#registerErrorTitle").html("Registration Failed");
							$("#registerErrorMessage").html(error);
						}else if(data.search("True - ") != -1){
							$("#registerForm").attr("class", "ui large form success");
							$("#registerMsg").attr("class", "ui success message");
							$("#registerErrorTitle").html("Registration Successful");
							$("#registerErrorMessage").html("Your registration was successful!");
						}else{
							$("#registerMsg").css( "color", "red" )
							$("#registerMsg").html("There was an error logging you in, contact the administrator");
						}
					})
					.always(function(data){
						$("#usernameInputDiv").attr("class", "ui left icon input");
						$("#passwordInputDiv").attr("class", "ui left icon input");
						$("#emailInputDiv").attr("class", "ui left icon input");
						$("#nameInputDiv").attr("class", "ui left icon input");
					});
			});
		});
		</script>
			<div class="ui middle aligned center aligned grid">
				<div class="five wide column">
					<h2 class="ui pink image header">
						<div class="content">
							Register Your Skitter Account
						</div>
					</h2>
					<form id="registerForm" class="ui large form">
						<div id="register" class="ui stacked segment">
							<div class="field">
								<div id="usernameInputDiv" class="ui left icon input">
									<i class="user icon"></i>
									<input id="usernameInput" type="text" name="username" placeholder="Username">
								</div>
							</div>
							<div class="field">
			          <div id="passwordInputDiv" class="ui left icon input">
			            <i class="lock icon"></i>
			            <input id="passwordInput" type="password" name="password" placeholder="Password">
			          </div>
		  				</div>
              <div class="field">
								<div id="emailInputDiv" class="ui left icon input">
                  <i class="envelope icon"></i>
									<input id="emailInput" type="email" placeholder="user@email.com" required>
								</div>
							</div>
              <div class="field">
								<div id="nameInputDiv" class="ui left icon input">
                  <i class="address book icon"></i>
									<input id="nameInput" type="text" placeholder="John Doe" required>
								</div>
							</div>
							<div id="registerMsg"><div id="registerErrorTitle" class="header"></div><p id="registerErrorMessage"></p></div>
							<button id="registerButton" class="ui fluid large pink submit button">Register</button>
						</div>
					</form>
          <div class="ui message">
            Have an Account? <a href="/index.php">Login</a>
          </div>
				</div>
			</div>
		</body>
	</html>
	';
}

 ?>
