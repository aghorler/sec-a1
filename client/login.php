<?php
	/* Start session. */
	session_start();

	/* Redirect to shopping cart if already logged in. */
	if(isset($_SESSION['username'])){
		header('Location: cart.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Assignment</title>
		<meta http-equiv="Content-Security-Policy" content="default-src 'none'; script-src 'self'">
		<meta name="author" content="Aaron Horler">
		<meta name="referrer" content="no-referrer">
		<meta http-equiv="x-dns-prefetch-control" content="off">
	</head>
	<body>
		<h2>Assignment Login</h2>

		<p><a href="/client/login.php">Login</a> | <a href="/client/register.php">Register</a> | <a href="/client/cart.php">Cart</a></p>

		<hr>

		<p>
			<label for="username">Username:</label>
			<input type="text" id="username"  pattern="[a-zA-Z0-9]+">
		</p>

		<p>
			<label for="password">Password:</label>
			<input type="password" id="password">
		</p>

		<p>
			<button type="button" id="login">Login</button>
		</p>

		<form id="formLogin" action="/server/login.php" method="POST">
			<input type="hidden" name="formUsername" id="formUsername" pattern="[a-zA-Z0-9]+">
			<input type="hidden" name="formPassword" id="formPassword" pattern="[a-zA-Z0-9]+">
		</form>

		<script src="vendor/crypto-js-3.1.9-1/crypto-js.js"></script>
		<script src="vendor/jsencrypt/jsencrypt.js"></script>
		<script src="js/client.js"></script>
	</body>
</html>
