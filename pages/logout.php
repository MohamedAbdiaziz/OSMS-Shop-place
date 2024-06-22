<?php
	include_once('../db/session.php');
	session_destroy();
	unset($_SESSION['customer']);
	header("Location: login.php");

?>