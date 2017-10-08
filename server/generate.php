<!DOCTYPE php>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Assignment</title>
		<meta http-equiv="Content-Security-Policy" content="default-src 'none'; script-src 'self'; style-src 'self' 'sha256-wvbCDnm6Rf7Vby67RzA88EpChS3mvSEO2JkAOhlRwnw='">
		<meta name="author" content="Aaron Horler">
		<meta name="referrer" content="no-referrer">
		<meta http-equiv="x-dns-prefetch-control" content="off">
		<style>
			.error {
				color: red;
				font-weight: bold;
			}
			.pass {
				color: green;
				font-weight: bold;
			}
		</style>
	</head>
	<body>
		<?php
			/* Display all errors. */
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);

			/* Include dependencies. */
			include('vendor/rsa.php');

			/* Redirect to generate if key was not sent, or is blank. */
			if(!isset($_POST['formKey']) || $_POST['formKey'] == ""){
				echo "<p class=\"error\">Key not received.</p>";
				header("refresh:2;url=/client/generate.php");
				exit();
			}

			/* Start session. */
			session_start();

			/* Define username. */
			$username = $_SESSION['username'];

			/* Redirect to login if not logged in. */
			if(!isset($_SESSION['username'])){
				header('Location: /client/login.php');
				exit();
			}

			/* Define key. */
			$key = $_POST['formKey'];

			/* Decrypt key. */
			$privateKey = get_rsa_privatekey('private.key');
			$decrypted = rsa_decryption($key, $privateKey);

			/* Redirect to generate if RSA decryption failed. */
			if($decrypted == null){
				echo "<p class=\"error\">RSA decryption error.</p>";
				header("refresh:2;url=/client/generate.php");
				exit();
			}

			/* Split string intokey, SHA3 hash, and timestamp. */
			$value = explode("&", $decrypted);

			/* Redirect to generate if key is less than minimum character limit. */
			if(strlen($value[0]) < 6){
				echo "<p class=\"error\">Key is less than 6 characters.</p>";
				header("refresh:2;url=/client/generate.php");
				exit();
			}

			/* Redirect to generate if key exceeds maximum character limit. */
			if(strlen($value[0]) > 255){
				echo "<p class=\"error\">Key exceeds 255 characters.</p>";
				header("refresh:2;url=/client/generate.php");
				exit();
			}

			/* Print current variables. */
			echo "<p><strong>POSTed data (RSA message): </strong>" . $key . "</p>";
			echo "<p><strong>POSTed data (AES key, SHA3 password, and timestamp): </strong>" . $decrypted . "</p>";
			echo "<p><strong>Current timestamp: </strong>" . time() . "</p>";
			echo "<p><strong>POSTed timestamp: </strong>" . $value[2] . "</p>";

			/* Check that difference in timestamp is less than 150. */
			if((time() - $value[2]) < 150){
				echo "<p class=\"pass\">Timestamp difference is less than 150!</p>";

				/* Verify that user exists in database. */
				$exist = 0;
				foreach(file('../database/database.txt') as $line){
					$result = explode(",", $line);

					if(rtrim($result[0]) == $username && rtrim($result[1]) == $value[1]){
						$exist = 1;

						/* Determine line to replace. */
						$toReplace = $line;

						break;
					}
				}

				if($exist == 1){
					/* Generate credential entry. */
					$credential = $username . "," . $value[1] . "," . $value[0] . "\n";

					/* Write entry to database.txt */
					$file = file_get_contents("../database/database.txt");
					$file = str_replace($toReplace, $credential, $file);
					file_put_contents("../database/database.txt", $file);

					echo "<p class=\"pass\">AES key saved!</p>";
					header("refresh:2;url=/client/cart.php");
					exit();
				}
				else{
					echo "<p class=\"error\">Authentication failure.</p>";
					header("refresh:2;url=/client/generate.php");
					exit();
				}
			}
			else{
				echo "<p class=\"error\">Time difference exceeds 150.</p>";
				header("refresh:2;url=/client/generate.php");
				exit();
			}
		?>
	</body>
</html>
