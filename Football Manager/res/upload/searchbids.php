<?php include('search.php'); ?>
<div>
<?php
$wherePozitie = "";
$whereReflexe = "";
$whereUnulaunu = "";
$whereManevrare = "";
$whereMarcaj = "";
$whereDeposedare = "";
$whereHeading = "";
$whereLong = "";
$wherePozitionare = "";
$whereSut = "";
$whereAtingere = "";
$whereCreativitate = "";
$whereLansari = "";
$wherePase = "";
if(!empty($_REQUEST['SearchPlayers'])) {
	if($_REQUEST['pozitie']>0)
		$wherePozitie = "AND b.Position=".$_REQUEST['pozitie'];
	if($_REQUEST['reflexeLow']<>1 || $_REQUEST['reflexeHigh']<>50)
		$whereReflexe = "AND (b.Reflexes between ".$_REQUEST['reflexeLow']." AND ".$_REQUEST['reflexeHigh'].")";
	if($_REQUEST['unulaunuLow']<>1 || $_REQUEST['unulaunuHigh']<>50)
		$whereUnulaunu = "AND (b.OneOnOne between ".$_REQUEST['unulaunuLow']." AND ".$_REQUEST['unulaunuHigh'].")";
	if($_REQUEST['manevrareLow']<>1 || $_REQUEST['manevrareHigh']<>50)
		$whereManevrare = "AND (b.Handling between ".$_REQUEST['manevrareLow']." AND ".$_REQUEST['manevrareHigh'].")";
	if($_REQUEST['deposedareLow']<>1 || $_REQUEST['deposedareHigh']<>50)
		$whereDeposedare = "AND (b.Tackling between ".$_REQUEST['deposedareLow']." AND ".$_REQUEST['deposedareHigh'].")";
	if($_REQUEST['marcajLow']<>1 || $_REQUEST['marcajHigh']<>50)
		$whereMarcaj = "AND (b.Marking between ".$_REQUEST['marcajLow']." AND ".$_REQUEST['marcajHigh'].")";
	if($_REQUEST['headingLow']<>1 || $_REQUEST['headingHigh']<>50)
		$whereHeading = "AND (b.Heading between ".$_REQUEST['headingLow']." AND ".$_REQUEST['headingHigh'].")";
	if($_REQUEST['longLow']<>1 || $_REQUEST['longHigh']<>50)
		$whereLong = "AND (b.LongShots between ".$_REQUEST['longLow']." AND ".$_REQUEST['longHigh'].")";
	if($_REQUEST['pozitionareLow']<>1 || $_REQUEST['pozitionareHigh']<>50)
		$wherePozitionare = "AND (b.Positioning between ".$_REQUEST['pozitionareLow']." AND ".$_REQUEST['pozitionareHigh'].")";
	if($_REQUEST['sutLow']<>1 || $_REQUEST['sutHigh']<>50)
		$whereSut = "AND (b.Shooting between ".$_REQUEST['sutLow']." AND ".$_REQUEST['sutHigh'].")";
	if($_REQUEST['atingereLow']<>1 || $_REQUEST['atingereHigh']<>50)
		$whereAtigere = "AND (b.FirstTouch between ".$_REQUEST['atingereLow']." AND ".$_REQUEST['atingereHigh'].")";
	if($_REQUEST['creativitateLow']<>1 || $_REQUEST['creativitateHigh']<>50)
		$whereCreativitate = "AND (b.Creativity between ".$_REQUEST['creativitateLow']." AND ".$_REQUEST['creativitateHigh'].")";
	if($_REQUEST['lansariLow']<>1 || $_REQUEST['lansariHigh']<>50)
		$whereLansari = "AND (b.Crossing between ".$_REQUEST['lansariLow']." AND ".$_REQUEST['lansariHigh'].")";
	if($_REQUEST['paseLow']<>1 || $_REQUEST['paseHigh']<>50)
		$wherePase = "AND (b.Passing between ".$_REQUEST['paseLow']." AND ".$_REQUEST['paseHigh'].")";

}
?>
<h2>Bidding started</h2>
<br/>
<table class="tftable" border="1" width="1800">
					<tr>
						<th></th>
						<th>Pos.</th>
						<th><font color="<?php echo $gk; ?>">Player</font></th>
						<th><font color="<?php echo $gk; ?>">y.o.</font></th>
						<th><font color="<?php echo $gk; ?>">Rating</font></th>
						<th><font color="<?php echo $gk; ?>">Owner</font></th>
						<th><font color="<?php echo $gk; ?>">Highest price</font></th>
						<th><font color="<?php echo $gk; ?>">Bid</font></th>
						<th><font color="<?php echo $gk; ?>">Bid expires on</font></th>
						<th><font color="<?php echo $gk; ?>">Value</font></th>
					</tr>

<?php

//echo "$start: $cantitate";
//vezi citi jucatori sunt in total, pt a vedea cite pagini sunt
$sql = "SELECT b.id
		FROM player b
		LEFT OUTER JOIN userplayer e
		ON e.PlayerID=b.id
		LEFT OUTER JOIN user f
		ON f.id=e.UserID
		LEFT OUTER JOIN country c 
		ON c.id=b.Nationality
		WHERE b.TransferDeadline>'".DATE("Y-m-d H:i:s")."' AND b.Transfer=1 $wherePozitie $whereReflexe $whereUnulaunu $whereManevrare $whereMarcaj $whereDeposedare
				$whereHeading $whereLong $wherePozitionare $whereSut $whereAtingere $whereCreativitate $whereLansari $wherePase
		ORDER BY b.TransferDeadline ASC";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$numarinreg = mysqli_num_rows($res);
mysqli_free_result($res);


//cauta toti jucatorii care sunt pe lista de transfer si a caror pariere a inceput
$sql = "SELECT b.id, b.fname, b.lname, b.TransferDeadline, c.name, b.Rating, b.Age, b.Value, f.TeamName, f.id, MaxBid(b.id), b.Position
		FROM player b
		LEFT OUTER JOIN userplayer e
		ON e.PlayerID=b.id
		LEFT OUTER JOIN user f
		ON f.id=e.UserID
		LEFT OUTER JOIN country c 
		ON c.id=b.Nationality
		WHERE b.TransferDeadline>'".DATE("Y-m-d H:i:s")."' AND b.Transfer=1 $wherePozitie $whereReflexe $whereUnulaunu $whereManevrare $whereMarcaj $whereDeposedare
				$whereHeading $whereLong $wherePozitionare $whereSut $whereAtingere $whereCreativitate $whereLansari $wherePase
		ORDER BY b.TransferDeadline ASC LIMIT $start, $cantitate";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);

while(list($player_id, $fname, $lname, $deadline, $country, $Rating, $p_Age, $p_Value,$detinator, $p_userid, $pariuechipa,$pozitie) = mysqli_fetch_row($res)) {
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

					/*
					$sql2 = "SELECT MaxBid($player_id)";
					echo "$sql2<br/>";
					$res2 = mysqli_query($GLOBALS['con'],$sql2);
					list($pariuechipa) = mysqli_fetch_row($res2);
					list($val,$echipa, $tid) = split(";", $pariuechipa);
					mysqli_free_result($res2);
					*/
					list($val,$echipa, $tid) = split(";", $pariuechipa);

					?>
	
					<tr class="tr-1">
						<td align="left">
						<?php echo "<img src=\"steaguri/$country.png\" width=\"20\">"; ?></td>
						<td><?php echo $pos; ?></td>
						<td><a href="index.php?option=viewplayer&pid=<?php echo $player_id; ?>&uid=<?php echo $p_userid; ?>" class="link-5"><?php echo "$fname $lname"; ?></a>
						</td>	
						<td><?php echo $p_Age; ?></td>
						<td><?php echo $Rating; ?></td>	
						<td><a href="index.php?option=viewclub&club_id=<?php echo $p_userid; ?>" class="link-3"><?php echo $detinator; ?></a></td>
						<td><?php echo "$echipa"; ?></td>
						<td><?php echo number_format($val)." &euro;"; ?></td>
						<td><?php echo "$deadline"; ?></td>
						<td><?php echo number_format($p_Value)." &euro;"; ?></td>
						</tr>
	<?php
}
echo "<tr><th colspan=\"10\">";
//partea cu navigarea
$pagini = $numarinreg/$cantitate+1;
//echo "pagini = $numarinreg ::  $pagini";
for($i=1;$i<=$pagini;$i++) {
	if(empty($_REQUEST['page'])) $curenta = 1;
	else $curenta = $_REQUEST['page'];
	
	if($i==$curenta) echo "<font color=\"green\">$i&nbsp;</font>";
	else echo "<a href=\"index.php?option=searchbids&page=$i\" class=\"div-33\">$i</a>&nbsp;";
}
echo "</th></tr>";
mysqli_free_result($res);
?>
</table>
</div>
<br/><br/>