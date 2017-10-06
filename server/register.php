<!DOCTYPE php>
<php>
	<head>
		<title>Assignment</title>
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
			include('vendor/rsa.php');

			/* Redirect to register if username and/or password was not sent, or is/are blank. */
			if(!isset($_POST['formUsername']) || !isset($_POST['formPassword']) || $_POST['formUsername'] == "" || $_POST['formPassword'] == ""){
				echo "<p class=\"error\">Username and/or password not received.</p>";
				header("refresh:2;url=/client/register.php");
				exit();
			}

			/* Start session. */
			session_start();

			/* Redirect to shopping cart if logged in. */
			if(isset($_SESSION['username'])){
				header('Location: cart.php');
				exit();
			}

			/* Define username and password. */
			$username = $_POST['formUsername'];
			$password = $_POST['formPassword'];

			/* Redirect to register if username exceeds maximum limit. */
			if(strlen($username) > 249){
				echo "<p class=\"error\">Username exceeds limit of 250 characters.</p>";
				header("refresh:2;url=/client/register.php");
				exit();
			}

			/* Decrypt password. */
			$privateKey = get_rsa_privatekey('private.key');
			$decrypted = rsa_decryption($password, $privateKey);

			/* Redirect to register if RSA decryption failed. */
			if($decrypted == null){
				echo "<p class=\"error\">RSA decryption error.</p>";
				header("refresh:2;url=/client/register.php");
				exit();
			}

			/* Split password into SHA3 hash and timestamp. */
			$value = explode("&", $decrypted);

			/* Redirect to register if password is not a SHA3 hash. */
			if(ctype_alnum($value[0]) && strlen($value[0]) !== 128){
				echo "<p class=\"error\">Invalid SHA3 hash.</p>";
				header("refresh:2;url=/client/register.php");
				exit();
			}

			/* Redirect to register if password is an empty string. */
			if($value[0] == "0eab42de4c3ceb9235fc91acffe746b29c29a8c366b7c60e4e67c466f36a4304c00fa9caf9d87976ba469bcbe06713b435f091ef2769fb160cdab33d3670680e"){
				echo "<p class=\"error\">Password cannot be blank.</p>";
				header("refresh:2;url=/client/register.php");
				exit();
			}

			/* Print current variables. */
			echo "<p><strong>POSTed password (RSA message): </strong>" . $password . "</p>";
			echo "<p><strong>POSTed password (decrypted SHA3 hash and timestamp): </strong>" . $decrypted . "</p>";
			echo "<p><strong>Current timestamp: </strong>" . time() . "</p>";
			echo "<p><strong>POSTed timestamp: </strong>" . $value[1] . "</p>";
			
			if((time() - $value[1]) < 150){
				echo "<p class=\"pass\">Timestamp difference is less than 150!</p>";

				$exist = 0;

				foreach(file('/database/database.txt') as $line){
					$result = explode(",", $line);

					if($result[0] == $username && rtrim($result[1]) == $value[0]){
						$exist = 1;
						break;
					}
				}

				if($exist == 0){
					$credential = $username . "," . $value[0];

					echo $credential;

					$file = fopen("../database/database.txt", "a");
				
					fwrite($file, $credential . "\n");
				
					fclose($file);

					echo "<p class=\"pass\">Account added to the database!</p>";

					header("refresh:2;url=/client/login.php");
				}
				else{
					echo "<p class=\"error\">Account already exists.</p>";
					header("refresh:2;url=/client/register.php");
					exit();
				}
			}
			else{
				echo "<p class=\"error\">Time difference exceeds 150.</p>";
				header("refresh:2;url=/client/register.php");
				exit();
			}
		?>
	</body>
</php>
