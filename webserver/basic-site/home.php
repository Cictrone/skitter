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
							$.post("getSkits.php")
								.done(function(data){
									if(data.search("False - ") != -1){
										$("#SkitsMsg").css( "color", "red" )
										$("#SkitsMsg").html(data.substring(data.search(" - ")+3,data.length));
									} else if(data.search("True - ") != -1){
										var skitData = JSON.parse(data.substring(data.search(" - ")+3,data.length));
										for (var i = 0; i < skitData.length; i++) {
									    var skit = skitData[i];
											var skitMessage = skit[\'_source\'][\'message\']
											var skitID = skit[\'_id\']
											var skitUser = skit[\'_source\'][\'user\']
											$("#ListOfSkits").append("<div id=\'"+skitID+"\'class=\'ui message\'><i class=\'close icon\'></i><div class=\'header\'>"+skitUser+"</div><p>"+skitMessage+"</p></div>");
										}
									} else{
										$("#SkitsMsg").css( "color", "red" )
										$("#SkitsMsg").html("There was an error getting Skits to you, contact the administrator");									}
								});
							$(".message .close")
							  .on("click", function() {
							    $(this)
							      .closest(".message")
							      .transition("fade");
							  });
							$("#SkeetButton").click(function(event){
								event.preventDefault();
								var skit_message = encodeURIComponent($("#SkitInput")[0].value);
								var data = {"skit_message": skit_message}
								$.post("addSkit.php", data)
									.done(function(data){
										if(data.search("False - ") != -1){
											var error = data.substring(data.search(" - ")+3,data.length);
											$("#skitForm").attr("class", "ui large form error");
											$("#skitAddMsg").attr("class", "ui error message");
											$("#skitErrorTitle").html("Skeet Failed");
											$("#skitErrorMessage").html(error);
										}else if(data.search("True - ") != -1){
											$("#skitForm").attr("class", "ui large form success");
											$("#skitAddMsg").attr("class", "ui success message");
											$("#skitErrorTitle").html("Registration Successful");
											$("#skitErrorMessage").html("Your skeet was successful!");
											location.reload();
										}else{
											$("#skitForm").attr("class", "ui large form error");
											$("#skitAddMsg").attr("class", "ui error message");
											$("#skitErrorTitle").html("Skeet Failed");
											$("#skitErrorMessage").html("There was an error with skeeting, contact the administrator");
										}
									});
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
								<form id="skitForm" class="ui large form">
									<div class="ui fluid action input">
										<input id="SkitInput" type="text" placeholder="Enter a Skit...">
										<button id="SkeetButton" class="ui pink right labeled icon button">
									    <i class="edit icon"></i>
									    Skeet
									  </button>
									</div>
									<div id="skitAddMsg"><div id="skitErrorTitle" class="header"></div><p id="skitErrorMessage"></p></div>
									<br />
									<div id="ListOfSkits"></div>
									<div id="SkitsMsg"></div>
								</form>
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
