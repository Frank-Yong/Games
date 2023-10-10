
<table class="tftable">
<tr>
	<th colspan="2">
	<h1>Facilities</h1>
	</th>
</tr>
<tr>
	<td>
	<img src="images/parking.jpg" height="210" width="380"> 
	</td>
	<td>
	<!--
	<img src="images/training-ground.jpg" height="210" width="380"> 
	-->
	</td>
</tr>
<?php
	$sql = "SELECT id, data, existent, nou FROM facilitati
			WHERE tip='parcare' AND userid=".$_SESSION['USERID']." ORDER BY id DESC";
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($f_id, $f_data, $f_existent, $f_nou) = mysqli_fetch_row($res);
	
	if(is_null($f_existent)) $loc = 0;
	else $loc = $f_existent;

	if(is_null($f_nou)) $toshow = "";
	else $toshow = "$f_nou spots are in construction. Ready on: $f_data";
	mysqli_free_result($res);
?>
<tr>
	<th align="center">
	<h1>Parking (<?php echo $loc; ?> spots)</h1>
	<form action="" method="post" onSubmit="return validateParking(this);">
	Spots: <input id="locuri" type="text" name="locuri" size="5" class="input-1">
	<input type="Submit" name="BuyParking" value="Buy" class="button-2">
	</form>
	<?php 
	echo "$toshow"; 
	?>
	</th>
	<th align="center">
	<!-- Baza antrenament -->
	</th>

</tr>
<!--
<tr>
	<td>
	<img src="images/clubshop.jpg" height="210" width="380"> 
	</td>
	<td>
	<img src="images/restaurant.jpg" height="210" width="380"> 
	</td>
</tr>
<tr>
	<th align="center">
	Magazin
	</th>
	<th align="center">
	Restaurant
	</th>
</tr>
-->
</table>