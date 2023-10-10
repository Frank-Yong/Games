<?php
$cantitate = 10;
if(empty($_REQUEST['page'])) $start = 0;
else $start = ($_REQUEST['page']-1)*10;
?>
<h2>Cautare</h2>
<br/>
<table>
<tr>
<TD>
<a href= "index.php?option=searchteam"><img src="images/echipe.png" width="65"></a>
</TD>
<td>
<a href= "index.php?option=searchtrainer"><img src="images/trainer2.png" width="65"></a> 
</td>
<td>
<a href= "index.php?option=searchplayers"><img src="images/jucatori.png" width="90"></a>
</td>
<td>
<a href= "index.php?option=searchbids"><img src="images/licitatii.png" width="80"></a> 
</td>
</tr>
<tr>
<td align="center">Teams</td>
<td align="center">Trainers</td>
<td align="center">Players</td>
<td align="center">Bids</td>
</tr>
</table>

<form action="" method="POST">

<table class="tftable" width="100%" cellpadding="1">

<tr>
	<th colspan="7">Search for a player</th>
</tr>		
<tr>
	<td colspan="1">
	Position
	<select name="pozitie">
	<?php
	$selected = "";
	for($i=0;$i<11;$i++) {
				if($i == $_REQUEST['pozitie']) $selected = "selected";
				else $selected = "";
				switch($i) {
					case 0: $pos = "-"; break;
					case 1: $pos = "GK"; break;
					case 2: $pos = "DR"; break;
					case 3: $pos = "DC"; break;
					case 4: $pos = "DL"; break;
					case 5: $pos = "MR"; break;
					case 6: $pos = "MC"; break;
					case 7: $pos = "ML"; break;
					case 8: $pos = "FR"; break;
					case 9: $pos = "FC"; break;
					case 10: $pos = "FL"; break;
				}
				echo "<option value=\"$i\" $selected>$pos</option>";	
	}
	?>
	</select>
	</td>
	<td colspan="6">
	Age
	<select name="grupa">
	<?php
	$selected = "";
	for($i=0;$i<2;$i++) {
				if($i == $_REQUEST['grupa']) $selected = "selected";
				else $selected = "";
				switch($i) {
					case 0: $posgr = "Toti"; break;
					case 1: $posgr = "Juniori"; break;
				}
				echo "<option value=\"$i\" $selected>$posgr</option>";	
	}
	?>
	</select>
	</td>
	</tr>
	
	<tr>
		<th><font color="<?php echo $gk; ?>">Reflexe</font></th>
		<th><font color="<?php echo $gk; ?>">Unu la unu</font></th>
		<th><font color="<?php echo $gk; ?>">Manevrare</font></th>
		<th><font color="<?php echo $df; ?>">Deposedare</font></th>	
		<th><font color="<?php echo $df; ?>">Marcaj</font></th>	
		<th><font color="<?php echo $dffw; ?>">Jocul cu capul</font></th>
		<th><font color="<?php echo $md; ?>">Suturi de la distanta</font></th>
	</tr>

	<tr class="tr-1">			
		<td>
		<?php 
		$rlow = 1;
		$rhigh = 50;
		if($_REQUEST['reflexeLow']<>"" || !empty($_REQUEST['reflexeLow'])) $rlow = $_REQUEST['reflexeLow'];
		if($_REQUEST['reflexeHigh']<>"" || !empty($_REQUEST['reflexeHigh'])) $rhigh = $_REQUEST['reflexeHigh'];
		?>
		<input type="text" name="reflexeLow" value="<?php echo $rlow; ?>" class="input-search"> - 
		<input type="text" name="reflexeHigh" value="<?php echo $rhigh; ?>" class="input-search">
		</td>
		<td>
		<?php 
		$ulow = 1;
		$uhigh = 50;
		if($_REQUEST['unulaunuLow']<>"" || !empty($_REQUEST['unulaunuLow'])) $ulow = $_REQUEST['unulaunuLow'];
		if($_REQUEST['unulaunuHigh']<>"" || !empty($_REQUEST['unulaunuHigh'])) $uhigh = $_REQUEST['unulaunuHigh'];
		?>
		<input type="text" name="unulaunuLow" value="<?php echo $ulow; ?>" class="input-search"> - 
		<input type="text" name="unulaunuHigh" value="<?php echo $uhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['manevrareLow']<>"" || !empty($_REQUEST['manevrareLow'])) $mlow = $_REQUEST['manevrareLow'];
		if($_REQUEST['manevrareHigh']<>"" || !empty($_REQUEST['manevrareHigh'])) $mhigh = $_REQUEST['manevrareHigh'];
		?>
		<input type="text" name="manevrareLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="manevrareHigh" value="<?php echo $mhigh; ?>" class="input-search"> 		
		</td>	
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['deposedareLow']<>"" || !empty($_REQUEST['deposedareLow'])) $mlow = $_REQUEST['deposedareLow'];
		if($_REQUEST['deposedareHigh']<>"" || !empty($_REQUEST['deposedareHigh'])) $mhigh = $_REQUEST['deposedareHigh'];
		?>
		<input type="text" name="deposedareLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="deposedareHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>	
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['marcajLow']<>"" || !empty($_REQUEST['marcajLow'])) $mlow = $_REQUEST['marcajLow'];
		if($_REQUEST['marcajHigh']<>"" || !empty($_REQUEST['marcajHigh'])) $mhigh = $_REQUEST['marcajHigh'];
		?>
		<input type="text" name="marcajLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="marcajHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['headingLow']<>"" || !empty($_REQUEST['headingLow'])) $mlow = $_REQUEST['headingLow'];
		if($_REQUEST['headingHigh']<>"" || !empty($_REQUEST['headingHigh'])) $mhigh = $_REQUEST['headingHigh'];
		?>
		<input type="text" name="headingLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="headingHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['longLow']<>"" || !empty($_REQUEST['longLow'])) $mlow = $_REQUEST['longLow'];
		if($_REQUEST['longHigh']<>"" || !empty($_REQUEST['longHigh'])) $mhigh = $_REQUEST['longHigh'];
		?>
		<input type="text" name="longLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="longHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		
	</tr>
	<tr>
		<th><font color="<?php echo $md; ?>">Pozitionare</font></th>
		<th><font color="<?php echo $fw; ?>">Sut</font></th>
		<th><font color="<?php echo $md; ?>">Atingere</font></th>
		<th><font color="<?php echo $md; ?>">Creativitate</font></th>	
		<th><font color="<?php echo $md; ?>">Lansari</font></th>	
		<th><font color="<?php echo $md; ?>">Pase</font></th>
		<th><font color="<?php echo $gk; ?>">Comunicatie</font></th>
	</tr>

	<tr class="tr-1">			
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['pozitionareLow']<>"" || !empty($_REQUEST['pozitionareLow'])) $mlow = $_REQUEST['pozitionareLow'];
		if($_REQUEST['pozitionareHigh']<>"" || !empty($_REQUEST['pozitionareHigh'])) $mhigh = $_REQUEST['pozitionareHigh'];
		?>
		<input type="text" name="pozitionareLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="pozitionareHigh" value="<?php echo $mhigh; ?>" class="input-search">
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['sutLow']<>"" || !empty($_REQUEST['sutLow'])) $mlow = $_REQUEST['sutLow'];
		if($_REQUEST['sutHigh']<>"" || !empty($_REQUEST['sutHigh'])) $mhigh = $_REQUEST['sutHigh'];
		?>
		<input type="text" name="sutLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="sutHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['atingereLow']<>"" || !empty($_REQUEST['atingereLow'])) $mlow = $_REQUEST['atingereLow'];
		if($_REQUEST['atingereHigh']<>"" || !empty($_REQUEST['atingereHigh'])) $mhigh = $_REQUEST['atingereHigh'];
		?>

		<input type="text" name="atingereLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="atingereHigh" value="<?php echo $mhigh; ?>" class="input-search"> 		
		</td>	
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['creativitateLow']<>"" || !empty($_REQUEST['creativitateLow'])) $mlow = $_REQUEST['creativitateLow'];
		if($_REQUEST['creativitateHigh']<>"" || !empty($_REQUEST['creativitateHigh'])) $mhigh = $_REQUEST['creativitateHigh'];
		?>
		<input type="text" name="creativitateLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="creativitateHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>	
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['lansariLow']<>"" || !empty($_REQUEST['lansariLow'])) $mlow = $_REQUEST['lansariLow'];
		if($_REQUEST['lansariHigh']<>"" || !empty($_REQUEST['lansariHigh'])) $mhigh = $_REQUEST['lansariHigh'];
		?>
		<input type="text" name="lansariLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="lansariHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['paseLow']<>"" || !empty($_REQUEST['paseLow'])) $mlow = $_REQUEST['paseLow'];
		if($_REQUEST['paseHigh']<>"" || !empty($_REQUEST['paseHigh'])) $mhigh = $_REQUEST['paseHigh'];
		?>
		
		<input type="text" name="paseLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="paseHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['comunicatieLow']<>"" || !empty($_REQUEST['comunicatieLow'])) $mlow = $_REQUEST['comunicatieLow'];
		if($_REQUEST['comunicatieHigh']<>"" || !empty($_REQUEST['comunicatieHigh'])) $mhigh = $_REQUEST['comunicatieHigh'];
		?>
		<input type="text" name="comunicatieLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="comunicatieHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		
	</tr>
		
	<tr>
		<th>Joc de echipa</th>
		<th>Rezistenta</th>
		<th>Viteza</th>
		<th>Experienta</th>	
		<th>Conditie fizica</th>	
		<th>Dribling</th>
		<th>Agresivitate</th>
	</tr>
	<tr class="tr-1">			
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['twLow']<>"" || !empty($_REQUEST['twLow'])) $mlow = $_REQUEST['twLow'];
		if($_REQUEST['twHigh']<>"" || !empty($_REQUEST['twHigh'])) $mhigh = $_REQUEST['twHigh'];
		?>
		<input type="text" name="twLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="twHigh" value="<?php echo $mhigh; ?>" class="input-search">
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['rezistentaLow']<>"" || !empty($_REQUEST['rezistentaLow'])) $mlow = $_REQUEST['rezistentaLow'];
		if($_REQUEST['rezistentaHigh']<>"" || !empty($_REQUEST['rezistentaHigh'])) $mhigh = $_REQUEST['rezistentaHigh'];
		?>
		<input type="text" name="rezistentaLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="rezistentaHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['vitezaLow']<>"" || !empty($_REQUEST['vitezaLow'])) $mlow = $_REQUEST['vitezaLow'];
		if($_REQUEST['vitezaHigh']<>"" || !empty($_REQUEST['vitezaHigh'])) $mhigh = $_REQUEST['vitezaHigh'];
		?>
		<input type="text" name="vitezaLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="vitezaHigh" value="<?php echo $mhigh; ?>" class="input-search"> 		
		</td>	
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['experientaLow']<>"" || !empty($_REQUEST['experientaLow'])) $mlow = $_REQUEST['experientaLow'];
		if($_REQUEST['experientaHigh']<>"" || !empty($_REQUEST['experientaHigh'])) $mhigh = $_REQUEST['experientaHigh'];
		?>
		<input type="text" name="experientaLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="experientaHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>	
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['strengthLow']<>"" || !empty($_REQUEST['strengthLow'])) $mlow = $_REQUEST['strengthLow'];
		if($_REQUEST['strengthHigh']<>"" || !empty($_REQUEST['strengthHigh'])) $mhigh = $_REQUEST['strengthHigh'];
		?>
		<input type="text" name="strengthLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="strengthHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['driblingLow']<>"" || !empty($_REQUEST['driblingLow'])) $mlow = $_REQUEST['driblingLow'];
		if($_REQUEST['driblingHigh']<>"" || !empty($_REQUEST['driblingHigh'])) $mhigh = $_REQUEST['driblingHigh'];
		?>
		<input type="text" name="driblingLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="driblingHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		<td>
		<?php 
		$mlow = 1;
		$mhigh = 50;
		if($_REQUEST['agresivitateLow']<>"" || !empty($_REQUEST['agresivitateLow'])) $mlow = $_REQUEST['agresivitateLow'];
		if($_REQUEST['agresivitateHigh']<>"" || !empty($_REQUEST['agresivitateHigh'])) $mhigh = $_REQUEST['agresivitateHigh'];
		?>
		<input type="text" name="agresivitateLow" value="<?php echo $mlow; ?>" class="input-search"> - 
		<input type="text" name="agresivitateHigh" value="<?php echo $mhigh; ?>" class="input-search"> 
		</td>
		
	</tr>
<tr>
	<td colspan="7">
	<input type="Submit" name="SearchPl" value="Afiseaza" class="button-2">
	</td>
</tr>

</table>
</form>

<?php
//echo var_dump($_SESSION['qplayers']);
if($_SESSION['qplayers'] == NULL) {
	//echo "fac ceva";
	include('searchplayers.php');
}
?>