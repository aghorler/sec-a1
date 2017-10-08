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
			include('vendor/GibberishAES.php');
			include('vendor/rsa.php');

			/* Redirect to cart if datae was not sent, or is blank. */
			if(!isset($_POST['formAQuantity']) || !isset($_POST['formBQuantity']) || !isset($_POST['formCQuantity']) || !isset($_POST['formCard']) || $_POST['formAQuantity'] == "" || $_POST['formBQuantity'] == "" || $_POST['formCQuantity'] == "" || $_POST['formCard'] == ""){
				echo "<p class=\"error\">Required data not received.</p>";
				header("refresh:2;url=/client/cart.php");
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

			/* Verify that user exists in database. */
			$exist = false;
			foreach(file('../database/database.txt') as $line){
				$result = explode(",", $line);

				if(rtrim($result[0]) == $username){
					$exist = true;

					/* Retrieve shared AES key. */
					$aesKey = $result[2];

					break;
				}
			}

			if($exist){
				/* Define data. */
				$rsaAQuantity = $_POST['formAQuantity'];
				$rsaBQuantity = $_POST['formBQuantity'];
				$rsaCQuantity = $_POST['formCQuantity'];
				$rsaCard = $_POST['formCard'];

				/* Print RSA encrypted variables. */
				echo "<p><strong>POSTed aQuantity (RSA encrypted): </strong>" . $rsaAQuantity . "</p>";
				echo "<p><strong>POSTed bQuantity (RSA encrypted): </strong>" . $rsaBQuantity . "</p>";
				echo "<p><strong>POSTed cQuantity (RSA encrypted): </strong>" . $rsaCQuantity . "</p>";
				echo "<p><strong>POSTed Card (RSA encrypted): </strong>" . $rsaCard . "</p>";

				/* RSA decrypt data. */
				$privateKey = get_rsa_privatekey('private.key');
				$aesAQuantity = rsa_decryption($rsaAQuantity, $privateKey);
				$privateKey = get_rsa_privatekey('private.key');
				$aesBQuantity = rsa_decryption($rsaBQuantity, $privateKey);
				$privateKey = get_rsa_privatekey('private.key');
				$aesCQuantity = rsa_decryption($rsaCQuantity, $privateKey);
				$privateKey = get_rsa_privatekey('private.key');
				$aesCard = rsa_decryption($rsaCard, $privateKey);

				/* Redirect to cart if RSA decryption failed. */
				if($aesAQuantity == null || $aesBQuantity == null || $aesCQuantity == null || $aesCard == null){
					echo "<p class=\"error\">RSA decryption error.</p>";
					header("refresh:2;url=/client/cart.php");
					exit();
				}

				/* Split data into AES ciphertext and timestamp. */
				$aValue = explode("&", $aesAQuantity);
				$bValue = explode("&", $aesBQuantity);
				$cValue = explode("&", $aesCQuantity);
				$cardValue = explode("&", $aesCard);

				/* Print AES encrypted variables. */
				echo "<p><strong>POSTed aQuantity (AES encrypted): </strong>" . $aesAQuantity . "</p>";
				echo "<p><strong>POSTed bQuantity (AES encrypted): </strong>" . $aesBQuantity . "</p>";
				echo "<p><strong>POSTed cQuantity (AES encrypted): </strong>" . $aesCQuantity . "</p>";
				echo "<p><strong>POSTed Card (AES encrypted): </strong>" . $aesCard . "</p>";
				echo "<p><strong>Current timestamp: </strong>" . time() . "</p>";
				echo "<p><strong>POSTed timestamps: </strong>" . $aValue[1] . ", " . $bValue[1] . ", " . $cValue[1] . ", " . $cardValue[1] . "</p>";

				/* Check that differences in timestamps are less than 150. */
				if((time() - $aValue[1]) < 150 && (time() - $bValue[1]) < 150 && (time() - $cValue[1]) < 150 && (time() - $cardValue[1]) < 150){
					echo "<p class=\"pass\">Timestamp differences are less than 150!</p>";

					/* AES decrypt data. */
					$aQuantity = GibberishAES::dec(rtrim($aValue[0]), rtrim($aesKey));
					$bQuantity = GibberishAES::dec(rtrim($bValue[0]), rtrim($aesKey));
					$cQuantity = GibberishAES::dec(rtrim($cValue[0]), rtrim($aesKey));
					$card = GibberishAES::dec(rtrim($cardValue[0]), rtrim($aesKey));

					/* Redirect to cart if AES decryption failed. */
					if($aQuantity == null || $bQuantity == null || $cQuantity == null || $card == null){
						echo "<p class=\"error\">AES decryption error.</p>";
						header("refresh:2;url=/client/cart.php");
						exit();
					}

					/* Validate contents of cart. */
					if($aQuantity < 0 && $aQuantity > 50 && $bQuantity < 0 && $bQuantity > 50 && $cQuantity < 0 && $cQuantity > 50 && $card[0] < 1000000000000000 && $card > 9999999999999999){
						echo "<p class=\"error\">Cart validation error.</p>";
						header("refresh:2;url=/client/cart.php");
						exit();
					}

					/* Check that at least one product was selected for purchase. */
					if(($aQuantity + $bQuantity + $cQuantity) < 1){
						echo "<p class=\"error\">No products selected.</p>";
						header("refresh:2;url=/client/cart.php");
						exit();
					}

					/* Print plaintext variables. */
					echo "<p><strong>POSTed aQuantity (plaintext): </strong>" . $aQuantity . "</p>";
					echo "<p><strong>POSTed bQuantity (plaintext): </strong>" . $bQuantity . "</p>";
					echo "<p><strong>POSTed cQuantity (plaintext): </strong>" . $cQuantity . "</p>";
					echo "<p><strong>POSTed Card (plaintext): </strong>" . $card . "</p>";

					/* Calculate total order price. */
					$total = ($aQuantity * 10) + ($bQuantity * 15) + ($cQuantity * 20);

					/* Generate database entry. */
					$entry = $username . "," . time() . "," . $aQuantity . "," . $bQuantity . "," . $cQuantity . "," . $card . "," . $total;

					/* Write entry to cart.txt */
					$file = fopen("../database/cart.txt", "a");
					fwrite($file, $entry . "\n");
					fclose($file);

					echo "<p class=\"pass\">Order added to the database!</p>";

					header("refresh:2;url=/client/cart.php");
					exit();
				}
				else{
					echo "<p class=\"error\">Timestamp differences exceed 150.</p>";
					header("refresh:2;url=/client/cart.php");
					exit();
				}
			}
			else{
				echo "<p class=\"error\">Authentication failure.</p>";
				header("refresh:2;url=/server/logout.php");
				exit();
			}
		?>
	</body>
</html>
