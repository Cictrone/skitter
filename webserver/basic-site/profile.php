<?php
include_once("common.php");

if(isset($_COOKIE['sessionID'])){
	$sessionID = $_COOKIE['sessionID'];
	if(!isAuthenticated($sessionID)){
		header("Set-Cookie: sessionID=deleted; expires=Thu, 01 Jan 1970 00:00:00 GMT");
		header("Location: https://localhost/index.php");
		exit();
	}else{
		$userData = GetUserData($sessionID);
		echo '
			<!Doctype html>
			<html lang="en">
				<head>
					<meta charset="utf-8">
					<title>Skitter</title>
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
              $("#usernameInput")[0].value = user.username;
              $("#emailInput")[0].value = user.email;
              $("#nameInput")[0].value = user.name;
              $("#profileImageInput")[0].value = user.profileImage;


              $("#usernameInput").keypress(function(event){
                if(event.which == 13){ // pressed "enter"
                  $("#updateButton").click();
                }
              });
              $("#passwordInput").keypress(function(event){
                if(event.which == 13){ // pressed "enter"
                  $("#updateButton").click();
                }
              });
              $("#emailInput").keypress(function(event){
                if(event.which == 13){ // pressed "enter"
                  $("#updateButton").click();
                }
              });
              $("#nameInput").keypress(function(event){
                if(event.which == 13){ // pressed "enter"
                  $("#updateButton").click();
                }
              });
              $("#profileImageInput").keypress(function(event){
                if(event.which == 13){ // pressed "enter"
                  $("#updateButton").click();
                }
              });
							$("#backButton").click(function(event){
								event.preventDefault();
								location.assign("/home.php");
							});
							$("#deleteUserButton").click(function(event){
								event.preventDefault();
								$("#usernameInputDiv").attr("class", "ui left icon input loading");
								$("#passwordInputDiv").attr("class", "ui left icon input loading");
								$("#emailInputDiv").attr("class", "ui left icon input loading");
								$("#nameInputDiv").attr("class", "ui left icon input loading");
								$("#profileImageInputDiv").attr("class", "ui left icon input loading");
								$.post("deleteUser.php")
									.done(function(data){
										if(data.search("False - ") != -1){
											var error = data.substring(data.search(" - ")+3,data.length);
											$("#updateForm").attr("class", "ui large form error");
											$("#updateMsg").attr("class", "ui error message");
											$("#updateErrorTitle").html("Delete Failed");
											$("#updateErrorMessage").html(error);
										}else if(data.search("True - ") != -1){
											$("#updateForm").attr("class", "ui large form success");
											$("#updateMsg").attr("class", "ui success message");
											$("#updateErrorTitle").html("Delete Successful");
											$("#updateErrorMessage").html("Your delete was successful!");
											location.assign("/index.php");
										}else{
											$("#updateMsg").css( "color", "red" )
											$("#updateMsg").html("There was an error logging you in, contact the administrator");
										}
									})
									.always(function(data){
										$("#usernameInputDiv").attr("class", "ui left icon input");
										$("#passwordInputDiv").attr("class", "ui left icon input");
										$("#emailInputDiv").attr("class", "ui left icon input");
										$("#nameInputDiv").attr("class", "ui left icon input");
										$("#profileImageInputDiv").attr("class", "ui left icon input");
									});
							});
              $("#updateButton").click(function(event){
                event.preventDefault();
                var username = encodeURIComponent($("#usernameInput")[0].value);
                var password = encodeURIComponent($("#passwordInput")[0].value);
                var email = encodeURIComponent($("#emailInput")[0].value);
								var name = encodeURIComponent($("#nameInput")[0].value);
								var profileImage = encodeURIComponent($("#profileImageInput")[0].value);
                var data = {"username": username, "password": password, "email": email, "name": name, "profileImage": profileImage};
                $("#usernameInputDiv").attr("class", "ui left icon input loading");
                $("#passwordInputDiv").attr("class", "ui left icon input loading");
                $("#emailInputDiv").attr("class", "ui left icon input loading");
                $("#nameInputDiv").attr("class", "ui left icon input loading");
                $("#profileImageInputDiv").attr("class", "ui left icon input loading");
                $.post("updateUser.php", data)
                  .done(function( data ) {
                    if(data.search("False - ") != -1){
                      var error = data.substring(data.search(" - ")+3,data.length);
                      $("#updateForm").attr("class", "ui large form error");
                      $("#updateMsg").attr("class", "ui error message");
                      $("#updateErrorTitle").html("Update Failed");
                      $("#updateErrorMessage").html(error);
                    }else if(data.search("True - ") != -1){
                      $("#updateForm").attr("class", "ui large form success");
                      $("#updateMsg").attr("class", "ui success message");
                      $("#updateErrorTitle").html("Update Successful");
                      $("#updateErrorMessage").html("Your update was successful!");
                    }else{
                      $("#updateMsg").css( "color", "red" )
                      $("#updateMsg").html("There was an error logging you in, contact the administrator");
                    }
                  })
                  .always(function(data){
                    $("#usernameInputDiv").attr("class", "ui left icon input");
                    $("#passwordInputDiv").attr("class", "ui left icon input");
                    $("#emailInputDiv").attr("class", "ui left icon input");
                    $("#nameInputDiv").attr("class", "ui left icon input");
                    $("#profileImageInputDiv").attr("class", "ui left icon input");
                  });
              });
            });
            </script>
              <div class="ui middle aligned center aligned grid">
                <div class="five wide column">
                  <h2 class="ui pink image header">
                    <div class="content">
                      Update Your Skitter Account
                    </div>
                  </h2>
                  <form id="updateForm" class="ui large form">
                    <div id="update" class="ui stacked segment">
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
                      <div class="field">
                        <div id="profileImageInputDiv" class="ui left icon input">
                          <i class="file image icon"></i>
                          <input id="profileImageInput" type="text" placeholder="https://example.com/awesome_photo.png" required>
                        </div>
                      </div>
                      <div id="updateMsg"><div id="updateErrorTitle" class="header"></div><p id="updateErrorMessage"></p></div>
                      <div class="ui fluid buttons">
                        <button id="backButton" class="ui large pink submit button">Back</button>
                        <div class="or"></div>
                        <button id="updateButton" class="ui large pink submit button">Update</button>
                      </div>
											<button id="deleteUserButton" class="ui large red fluid submit button">Delete User</button>
                    </div>
                  </form>
                </div>

              </div>

				</body>
			</html>
		';
	}
}else{
	header("Location: https://localhost/index.php");
	exit();
}

?>
