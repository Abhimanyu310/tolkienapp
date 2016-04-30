<?php
session_start();

if(isset($_SESSION['authenticated'])){
	session_destroy();
	}

header("Location: /hw9/login.php");


?>
