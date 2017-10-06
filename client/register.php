<?php
	/* Start session. */
	session_start();

	/* Redirect to shopping cart if logged in. */
	if(isset($_SESSION['username'])){
		header('Location: cart.html');
		exit();
	}
?>

<!DOCTYPE html>
<html>
	<body>
		<h2>Assignment Register</h2>

		<p><a href="/client/login.php">Login</a> | <a href="/client/register.php">Register</a> | <a href="/client/cart.php">Cart</a></p>

		<hr>

		<p>
			<label for="username">Username:</label>
			<input type="text" id="username" min="1" max="255" pattern="[a-zA-Z0-9]+">
		</p>

		<p>
			<label for="password">Password:</label>
			<input type="password" id="password" min="1" max="255">
		</p>

		<p>
			<button type="button" id="login">Register</button>
		</p>

		<form id="formLogin" action="/server/register.php" method="POST">
			<input type="hidden" name="formUsername" id="formUsername" min="1" max="255" pattern="[a-zA-Z0-9]+">
			<input type="hidden" name="formPassword" id="formPassword" min="128" max="128" pattern="[a-zA-Z0-9]+">
		</form>

		<script src="vendor/crypto-js-3.1.9-1/crypto-js.js"></script>
		<script src="vendor/rsa.js"></script>
		<script src="js/hash.js"></script>
	</body>
</html>