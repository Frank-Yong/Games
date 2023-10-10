<?php 
//include('search.php'); 

?>
<div>
<h2>Players on transfer list</h2>
<br/>
<table class="tftable" border="1">
					<tr>
						<th></th>
						<th><font color="<?php echo $gk; ?>">Pos.</font></th>
						<th><font color="<?php echo $gk; ?>">Player</font></th>
						<th><font color="<?php echo $gk; ?>">Y.o.</font></th>
						<th><font color="<?php echo $gk; ?>">Rating</font></th>
						<th><font color="<?php echo $gk; ?>">Owner</font></th>
						<th><font color="<?php echo $gk; ?>">Starting bid</font></th>
						<th><font color="<?php echo $gk; ?>">Value</font></th>
					</tr>

<?php
//echo "$start: $cantitate";
//vezi citi jucatori sunt in total, pt a vedea cite pagini sunt
/*
$sql = "SELECT b.id
		FROM player b
		LEFT OUTER JOIN userplayer e
		ON e.PlayerID=b.id
		LEFT OUTER JOIN user f
		ON f.id=e.UserID
		LEFT OUTER JOIN country c 
		ON c.id=b.Nationality
		WHERE b.TransferDeadline='0000-00-00 00:00:00' AND b.Transfer=1 $wherePozitie $whereReflexe $whereUnulaunu $whereManevrare $whereMarcaj $whereDeposedare
				$whereHeading $whereLong $wherePozitionare $whereSut $whereAtingere $whereCreativitate $whereLansari $wherePase
		ORDER BY b.TransferDeadline ASC";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$numarinreg = mysqli_num_rows($res);
mysqli_free_result($res);
*/
//cauta toti jucatorii care sunt pe lista de transfer si a caror pariere nu a inceput
$sql = "SELECT b.id, b.fname, b.lname, b.TransferDeadline, c.name, b.Rating, b.Age, b.Value, f.TeamName, b.TransferSuma, f.id, b.Position
		FROM player b
		LEFT OUTER JOIN userplayer e
		ON e.PlayerID=b.id
		LEFT OUTER JOIN user f
		ON f.id=e.UserID
		LEFT OUTER JOIN country c 
		ON c.id=b.Nationality
		WHERE b.TransferDeadline='0000-00-00 00:00:00' AND b.Transfer=1 $wherePozitie $whereReflexe $whereUnulaunu $whereManevrare $whereMarcaj $whereDeposedare
				$whereHeading $whereLong $wherePozitionare $whereSut $whereAtingere $whereCreativitate $whereLansari $wherePase
		ORDER BY b.TransferDeadline ASC LIMIT $start, $cantitate";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$iii=0;
while(list($player_id, $fname, $lname, $deadline, $country, $Rating, $p_Age, $p_Value, $detinator, $sumatransfer, $p_userid, $pozitie) = mysqli_fetch_row($res)) {
		if($iii>100) break;
		switch ($pozitie) {
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
	$iii++;
	if($detinator == "") {
		$detinator = "No contract";
	} else {
		$detinator = "<a href=\"index.php?option=amical&club_id=$p_userid\" class=\"link-3\">$detinator</a>";
	}
	?>
	
					<tr class="tr-1">
						<td align="left">
						<?php echo "<img src=\"steaguri/$country.png\" width=\"20\">"; ?></td>
						<td><?php echo $pos; ?></td>
						<td><a href="index.php?option=viewplayer&pid=<?php echo $player_id; ?>&uid=<?php echo $p_userid; ?>" class="link-5"><?php echo "$fname $lname"; ?></a>
						</td>	
						<td><?php echo $p_Age; ?></td>
						<td><?php echo $Rating; ?></td>	
						<td><?php echo $detinator; ?></td>
						<td><?php echo number_format($sumatransfer)." &euro;"; ?></td>
						<td><?php echo number_format($p_Value)." &euro;"; ?></td>
						</tr>
	<?php
}
echo "<tr><th colspan=\"8\">";
//partea cu navigarea
$pagini = $numarinreg/$cantitate+1;
$pagini = $iii/$cantitate+1;
//echo "pagini = $pagini";
for($i=1;$i<=$pagini;$i++) {
	if(empty($_REQUEST['page'])) $curenta = 1;
	else $curenta = $_REQUEST['page'];
	
	if($i==$curenta) echo "<font color=\"green\">$i&nbsp;</font>";
	else echo "<a href=\"index.php?option=searchplayers&page=$i\" class=\"div-33\">$i</a>&nbsp;";
}
echo "</th></tr>";
mysqli_free_result($res);
?>
</table>
</div>
<br/><br/>