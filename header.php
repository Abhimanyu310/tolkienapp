<html>
	<head><title>Tolkien DB</title>
		<style>
			.temp {
				width:50%;
				border: 1px solid black;
    				border-collapse: collapse;
				text-align:center;
			}


		</style>


	</head>
	<body>
		<center>
			<a href=index.php>Story List</a>
			<a href=index.php?s=10>Character List</a>
			<a href=add.php?s=11>Add Characters</a>
<?php

if(!isset($_SESSION['authenticated'])){
	echo "<a href=login.php>Login</a>";
	}
?>
			<hr>
		
