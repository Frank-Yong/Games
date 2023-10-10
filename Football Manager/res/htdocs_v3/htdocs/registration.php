<?php
error_reporting(0);
include('app.conf.php');
include('UserStadium.php');
include('player.php');
include('trainer.php');

?>

<form action="registration_step1.php" method="POST">
<table>
<tr>
	<td>Team Name:</td>
	<td><input type="text" name="teamName" size="20"></td>
</tr>
<tr>
	<td>Stadium Name:</td>
	<td><input type="text" name="stadiumName" size="20"></td>
</tr>
<tr>
	<td>Manager Name:</td>
	<td><input type="text" name="managerName" size="20"></td>
</tr>
<tr>
	<td>User Name:</td>
	<td><input type="text" name="userName" size="20"></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type="password" name="password" size="20"></td>
</tr>
<tr>
	<td>First Name:</td>
	<td><input type="text" name="firstName" size="20"></td>
</tr>
<tr>
	<td>Last Name:</td>
	<td><input type="text" name="lastName" size="20"></td>
</tr>
<tr>
	<td>Address:</td>
	<td><input type="text" name="address" size="20"></td>
</tr>
<tr>
	<td>City:</td>
	<td><input type="text" name="city" size="20"></td>
</tr>
<tr>
	<td>Country:</td>
	<td>
	<select name="country">
	<?php
	$sql = "SELECT id, name FROM country";
	$res = mysql_query($sql);
	while(list($country_id, $country_name) = mysql_fetch_row($res)) {
		echo "<option value=\"$country_id\">$country_name";
	}
	mysql_free_result($res);
	?>
	</select>
	</td>
</tr>
<tr>
	<td>Language:</td>
	<td><input type="text" name="language" size="20"></td>
</tr>
<tr>
	<td></td>
	<td><input type="Submit" name="Inscriere" size="20"></td>
</tr>
</table>
</form>