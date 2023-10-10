<div class="clear"></div>
<form action="index.php" method="POST">
<table class="tftable">
<?php
if(!empty($_REQUEST['key'])) {
	//echo "A venit cod de resetare din mail!!!!".$_REQUEST['key'];
?>
<input type="hidden" name="cod" value="<?php echo $_REQUEST['key']; ?>">
<tr>
	<th colspan="2"><h1>Parola pierduta</h1></th>
</tr>
<tr>
	<th>Parola noua</th>
	<td>
	<input type="password" name="password" size="20" class="input-2">
	</td>
</tr>
<tr>
	<th>Repeta parola</th>
	<td>
	<input type="password" name="repassword" size="20" class="input-2">
	</td>
</tr>
<tr>
	<td colspan="2">
		<input type="Submit" name="SetParola" value="Stabileste parola" class="button-2">
	</td>
</tr>
<?php } else { ?>
<tr>
	<th>Email</th>
	<td>
	<input type="text" name="email" size="20" class="input-2">
	<input type="Submit" name="ResetParola" value="Resetare parola" class="button-2">
	</td>
</tr>
<?php } ?>
</table>
</form>
<br/>