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
	<head>
		<title>Assignment</title>
	</head>
	<body>
		<h2>Assignment AES Key Generate</h2>

		<p><a href="/server/logout.php">Logout</a></p>

		<hr>

		<p>
			<label for="key">AES key:</label>
			<input type="password" id="key" min="6" max="255">
		</p>

		<p>
			<label for="keyRepeat">AES key (repeat):</label>
			<input type="password" id="keyRepeat" min="6" max="255">
		</p>

		<p>
			<label for="password">User password:</label>
			<input type="password" id="password" min="6" max="255">
		</p>

		<p>
			<button type="button" id="generate">Save</button>
		</p>

		<form id="formGenerate" action="/server/generate.php" method="POST">
			<input type="hidden" name="formKey" id="formKey">
		</form>

		<script src="vendor/crypto-js-3.1.9-1/crypto-js.js"></script>
		<script src="vendor/jsencrypt/jsencrypt.js"></script>
		<script src="js/client.js"></script>
	</body>
</html>
