<?php
error_reporting(63);
include('app.conf');

?>

<form action="home.php" method="POST">
<table>
<tr>
	<td>Manager Name:</td>
	<td><input type="text" name="userName" size="20"></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type="text" name="password" size="20"></td>
</tr>
<tr>
	<td></td>
	<td><input type="Submit" name="Login" size="20"></td>
</tr>
</table>
</form>