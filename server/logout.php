<?php
	/* Display all errors. */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	/* Start session. */
	session_start();

	/* Destroy session. */
	session_destroy();

	header('Location: /client/login.php');
?>
