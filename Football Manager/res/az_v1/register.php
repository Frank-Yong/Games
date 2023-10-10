<?php
?>
<script type="text/javascript">
<!--
function validateForm() {
    var x = document.forms["myForm"]["email"].value;
    var y = document.forms["myForm"]["userName"].value;
    var z = document.forms["myForm"]["teamName"].value;
    var t = document.forms["myForm"]["stadiumName"].value;
    var u = document.forms["myForm"]["password"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
        alert("Adresa de email nu este valida!");0
        return false;
    }
    if (x == "" || y == "" || z == "" || t == "" || u == "") {
          alert("Trebuie completate toate campurile!");
          return false;
       }
}   
-->
</script>
<?php
			if(!empty($_REQUEST['mes'])) {
?>
			<h1>MyFM.com</h1>
			<div class="clear"></div>
			<div class="container-3d">
				<h3>Mesaj</h3>
				<div class="container-3d-text">
					<?php
					echo "<h2>".$_REQUEST['mes']."</h2>";
					?>
				</div>
			</div>
			<br/>
			<div class="clear"></div>
			<?php 
} 
?>
<form name="myForm" action="index.php" method="POST" onsubmit="return validateForm()">
<table>
<tr>
	<td>Name of the team:</td>
	<td><input type="text" name="teamName" size="20" class="input-win" value="<?php echo $_REQUEST['teamName']; ?>"></td>
</tr>
<tr>
	<td>Name of the stadium:</td>
	<td><input type="text" name="stadiumName" size="20" class="input-win" value="<?php echo $_REQUEST['stadiumName']; ?>"></td>
</tr>
<tr>
	<td>User:</td>
	<td><input type="text" name="userName" size="20" class="input-win" value="<?php echo $_REQUEST['userName']; ?>"></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type="password" name="password" size="20" class="input-win"></td>
</tr>
<tr>
	<td>Email:</td>
	<td><input type="text" name="email" size="20" class="input-win" value="<?php echo $_REQUEST['email']; ?>"> (* - pentru confirmarea contului)</td> 
</tr>
<tr>
	<td colspan="2">
	    <?php
	//$img = new Securimage();
	//$img->text_color = '#ff0000';
	//echo $img::getCaptchaHtml();
	?>
	</td> 
</tr>

<!--
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
	<td><input type="text" name="language" size="20" class="select-1"></td>
</tr>
-->
<tr>
	<td></td>
	<td><input type="Submit" name="Register_step" size="20" class="button-2" value="Register"></td>
</tr>
</table>
</form>