<?php 
//error_reporting(63);
include('search2.php'); 
?>
<div>
<h2>Free players</h2>
<br/>
<table class="tftable" border="1">
<tr>
	<th></th>
	<th><font color="<?php echo $gk; ?>">Pos.</font></th>
	<th><font color="<?php echo $gk; ?>">Jucator</font></th>
	<th><font color="<?php echo $gk; ?>">Ani</font></th>
	<th><font color="<?php echo $gk; ?>">Rating</font></th>
	<th><font color="<?php echo $gk; ?>">Detinator</font></th>
	<th><font color="<?php echo $gk; ?>">Pret incepere</font></th>
	<th><font color="<?php echo $gk; ?>">Valoare</font></th>
</tr>
<?php

$result = $_SESSION['qplayers'];
//var_dump($result);
$jjj=0;
foreach($result as $key=>$value)  {
		if($jjj>20) break;
		$jjj++;
		switch ($value[11]) {
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
	if($value[8] == "") {
		$detinator = "No contract";
	} else {
		$detinator = "<a href=\"index.php?option=amical&club_id=".$value[10]."\" class=\"link-3\">".$value[8]."</a>";
	}
			//b.id, b.fname, b.lname, b.TransferDeadline, c.name, b.Rating, b.Age, b.Value, f.TeamName, b.TransferSuma, f.id, b.Position
?>
	
					<tr class="tr-1">
						<td align="left">
						<?php echo "<img src=\"steaguri/".$value[4].".png\" width=\"20\">"; ?></td>
						<td><?php echo $pos; ?></td>
						<td><a href="index.php?option=viewplayer&pid=<?php echo $value[0]; ?>&uid=<?php echo $value[10]; ?>" class="link-5"><?php echo $value[1].' '.$value[2]; ?></a>
						</td>	
						<td><?php echo $value[6]; ?></td>
						<td><?php echo $value[5]; ?></td>	
						<td><?php echo $detinator; ?></td>
						<td><?php echo number_format($value[9])." &euro;"; ?></td>
						<td><?php echo number_format($value[7])." &euro;"; ?></td>
						</tr>
	<?php
}
?>
</table>
</div>
<br/><br/>