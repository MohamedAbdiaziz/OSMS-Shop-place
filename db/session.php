<?php
	session_save_path('../db');
  ini_set('session.gc_probability', 1);
  error_reporting(E_ALL);
ini_set('display_errors', 1);
  session_start();
  header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

?>
