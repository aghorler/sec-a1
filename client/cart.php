<?php
	/* Start session. */
	session_start();

	/* Redirect to shopping cart if already logged in. */
	if(!isset($_SESSION['username'])){
		header('Location: login.php');
		exit();
	}
	else{

		/* Define username. */
		$username = $_SESSION['username'];
	}

	foreach(file('../database/database.txt') as $line){
		$result = explode(",", $line);

		if(rtrim($result[0]) == $username && rtrim($result[2]) == "null"){
			header('Location: generate.php');
			exit();
		}
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
		<h2>Assignment Cart</h2>

		<p><a href="/server/logout.php">Logout</a> | <a href="/client/generate.php">Change AES key</a></p>

		<hr>

		<p>
			<label for="aQuantity">Product A | $10 | </label>
			<input type="number" id="aQuantity" min="0" max="50" value="0"> | <span id="aSubtotal">$0</span>
		</p>

		<p>
			<label for="bQuantity">Product B | $15 | </label>
			<input type="number" id="bQuantity" min="0" max="50" value="0"> | <span id="bSubtotal">$0</span>
		</p>

		<p>
			<label for="cQuantity">Product C | $20 | </label>
			<input type="number" id="cQuantity" min="0" max="50" value="0"> | <span id="cSubtotal">$0</span>
		</p>

		<p>
			<label for="quantity">Total : </label>
			<span id="quantity">$0</span> | <span id="total">0</span>
		</p>

		<p>
			<label for="card">Credit card:</label>
			<input type="number" id="card" min="1000000000000000" max="9999999999999999">
		</p>

		<p>
			<label for="key">AES key:</label>
			<input type="password" id="key">
		</p>

		<p>
			<button type="button" id="process">Process</button>
		</p>

		<form id="formCart" action="/server/cart.php" method="POST">
			<input type="hidden" name="formAQuantity" id="formAQuantity" min="0" max="50">
			<input type="hidden" name="formBQuantity" id="formBQuantity" min="0" max="50">
			<input type="hidden" name="formCQuantity" id="formCQuantity" min="0" max="50">
			<input type="hidden" name="formCard" id="formCard" min="1000000000000000" max="9999999999999999">
		</form>

		<script src="vendor/crypto-js-3.1.9-1/crypto-js.js"></script>
		<script src="vendor/jsencrypt/jsencrypt.js"></script>
		<script src="js/client.js"></script>
	</body>
</html>
