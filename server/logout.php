<?php
	/* Start session. */
	session_start();

	/* Destroy session. */
	session_destroy();

	header('Location: /client/login.php');
?>
