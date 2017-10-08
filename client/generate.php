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
		<h2>Assignment AES Key Generate</h2>

		<p><a href="/server/logout.php">Logout</a></p>

		<hr>

		<p>
			<label for="key">AES key:</label>
			<input type="password" id="key">
		</p>

		<p>
			<label for="keyRepeat">AES key (repeat):</label>
			<input type="password" id="keyRepeat">
		</p>

		<p>
			<label for="password">User password:</label>
			<input type="password" id="password">
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
