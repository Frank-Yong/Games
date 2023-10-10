<?php
include('management.head.php');

$user = new user();
$user->LoginID($_SESSION['USERID']);
$img = $user->Imagine();

?>
<br/>
<h2>My Team</h2>


<form action="index.php" method="POST" enctype="multipart/form-data">
<table class="tftable">
<tr>
	<th colspan="2">
	<?php if($img<>"") echo "<img src=\"$img\" width=\"220\">"; ?>
	</th>
</tr>
<tr>
	<th>Upload logo (under 150kB):</th>
	<th>
	<input type="file" name="file_up" class="input-3">
	<input type="Submit" name="SendSigla" value="Upload" class="button-2">
	</th>
</tr>		

<!--
<tr>
	<th>Numele echipei:</th>
	<td>
	<input type="text" name="TeamName" class="input-3">
	<input type="Submit" name="SchimbaNumele" value="Schimba" class="button-2">
	</td>
</tr>		
<tr>
	<th>Parola noua:</th>
	<td>
	<input type="Password" name="Parola1" class="input-3">
	<br/>
	<input type="Password" name="Parola2" class="input-3">
	<input type="Submit" name="SchimbaParola" value="Schimba" class="button-2">
	</td>
</tr>
-->		
</table>
					
</form>