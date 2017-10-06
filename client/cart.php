<?php
	/* Start session. */
	session_start();

	/* Redirect to shopping cart if already logged in. */
	if(!isset($_SESSION['username'])){
		header('Location: login.php');
		exit();
	}
?>

<!DOCTYPE html>
<html>
	<body>
		<h2>Assignment Login</h2>

		<p><a href="/server/logout.php">Logout</a></p>

		<hr>

		<p>
			Cart.
		</p>

		<script src="vendor/crypto-js-3.1.9-1/crypto-js.js"></script>
		<script src="vendor/rsa.js"></script>
		<script src="js/hash.js"></script>
	</body>
</html>
