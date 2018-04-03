<?php
include_once("common.php");

if(isset($_COOKIE['sessionID'])){
	$sessionID = $_COOKIE['sessionID'];
	if(!isAuthenticated($sessionID)){
		header("Set-Cookie: sessionID=deleted; expires=Thu, 01 Jan 1970 00:00:00 GMT");
		header("Location: /index.php");
		exit();
	}else{
		$userData = GetUserData($sessionID);
		echo '
			<!Doctype html>
			<html lang="en">
				<head>
					<meta charset="utf-8">
					<title>Skitter - Home</title>
					<script type="text/javascript" src="js/jquery.min.js"></script>
					<link rel="stylesheet" src="css/semantic.min.css" />
					<link rel=icon href=images/skitter_s.png>
					<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/card.min.css" />
					<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/image.min.css" />
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
						var user = {};
						user.username = "'.$userData['username'].'";
						user.email = "'.$userData['email'].'";
						user.name = "'.$userData['name'].'";
						user.profileImage = "'.$userData['profileImage'].'";
						$(document).ready(function() {
							$("#profileImage").append("<img src=\'"+user.profileImage+"\' class=\'ui circular image\'>");
							$("#profileName").append(user.name);
							$("#profileEmail").append(user.email);
							$("#profileButton").click(function(event) {
								location.assign("/profile.php");
							});
							$("#logoutButton").click(function(event) {
								event.preventDefault();
								$.post("logout.php")
									.done(function( data ){
										if(data.search("False - ") != -1){
										$("#logoutMsg").css( "color", "red" )
										$("#logoutMsg").html(data.substring(data.search(" - ")+3,data.length));
									}else if(data.search("True - ") != -1){
										location.assign("/index.php");
									}else{
										$("#logoutMsg").css( "color", "red" )
										$("#logoutMsg").html("There was an error logging you out, contact the administrator");
									}
								});
							});
						});
					</script>
					<div class="ui grid">
						<div style="background-color: #FF1493;" class="one column row">
							<div class="column">
								<div style="color: #FFFFFF;" class="ui huge header">
									Skitter
								</div>
							</div>
						</div>
						<div class="sixteen column center aligned row">
							<div class="three wide column">
									<div class="ui card">
									  <div id="profileImage" class="image">
									  </div>
									  <div class="content">
									    <a id="profileName" class="header"></a>
									    <div class="meta">
									      <span class="date">Joined in the past</span>
									    </div>
									    <div class="description">
									    </div>
									  </div>
									  <div class="extra content">
									    <a id="profileEmail">
									      <i class="envelope icon"></i>
									    </a>
									  </div>
									</div>
									<div class="ui fluid buttons">
										<button id="profileButton" class="ui pink submit button">Change Profile</button>
										<div class="or"></div>
										<button id="logoutButton" class="ui pink submit button">Logout</button>
									</div>
									<div id="logoutMsg"></div>
							</div>
							<div class="twelve wide column">
								<div class="ui fluid action input">
									<input type="text" placeholder="Enter a Skit...">
									<button class="ui pink right labeled icon button">
								    <i class="edit icon"></i>
								    Skeet
								  </button>
								</div>
							</div>
						</div>
					</div>
				</body>
			</html>
		';
	}
}else{
	header("Location: /index.php");
	exit();
}

?>
