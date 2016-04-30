<?php
include_once('/var/www/html/hw9/header.php');
?>
<center>
<form method=post action=add.php>
	<table>
		<tr>
			<td><label>Username</label></td>
			<td><input type=text name=post_user></td>
		</tr>
		<tr>
			<td><label>Password</label></td>
			<td><input type=password name=post_pwd></td>
		</tr>
		<tr colspan=2>
			<td><input type=submit name=submit value=Login></td>
		</tr>
	</table>
</form>
</center>
<?php
include_once('/var/www/html/hw9/footer.php');
?>



