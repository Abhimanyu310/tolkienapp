<?php

if(isset($_SESSION['admin'])){
	if($_SESSION['admin']){
		include_once('/var/www/html/hw9/adminfunc.php');
	}
}
echo "<br>";
if(isset($_SESSION['authenticated'])){
	echo "<a href=logout.php>Logout</a>";
}
?>
</center>
</body>
</html>
