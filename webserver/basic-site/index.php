<!Doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Hello World</title>
	</head>

	<body>
		<h1>Hello World from Docker on EC2!</h1>
		<?php
		$hostname = gethostname();
		echo "<p>Your seeing this page from container: " . $hostname . "</p>"; 
		?>
	</body>
</html>
