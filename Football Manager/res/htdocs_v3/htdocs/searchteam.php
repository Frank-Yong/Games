<?php 
//include('search.php'); 

$whereNume = "";
if(!empty($_REQUEST['NumeEchipa'])) {
	if($_REQUEST['NumeEchipa']!="")
		$whereNume = "AND a.TeamName LIKE '%".$_REQUEST['NumeEchipa']."%'";
}
?>
<h2>Echipe</h2>
<br/>
<div>
<table class="tftable"  width="800" cellpadding="1">
					<tr>
						<th></th>
						<th><font color="<?php echo $gk; ?>">Echipa</font></th>
						<th><font color="<?php echo $gk; ?>">Manager</font></th>
						<th><font color="<?php echo $gk; ?>">Rating</font></th>
						<th><font color="<?php echo $gk; ?>">Activ la</font></th>
						<th><font color="<?php echo $gk; ?>">Amical</font></th>
					</tr>

<?php
$sql = "SELECT a.id
		FROM user a
		LEFT JOIN country b
		ON a.CountryID=b.id
		WHERE a.activated=1 $whereNume 
		ORDER BY a.TeamName ASC";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$numarinreg = mysqli_num_rows($res);
mysqli_free_result($res);

$sql = "SELECT a.id, a.TeamName, a.Username, b.name, a.LeagueID, a.Rating, a.LastActive 
		FROM user a
		LEFT JOIN country b
		ON a.CountryID=b.id
		WHERE a.activated=1 $whereNume 
		ORDER BY a.LastActive DESC LIMIT $start, $cantitate";
$res = mysqli_query($GLOBALS['con'],$sql);

while(list($team_id, $teamname, $manager, $country, $LeagueId, $Rating, $Activ) = mysqli_fetch_row($res)) {
	?>
					<tr class="tr-1">
						<td>
						<?php echo "<img src=\"steaguri/$country.png\" width=\"20\">"; ?>
						</td>
						<td align="left"><a href="index.php?option=viewclub&club_id=<?php echo $team_id; ?>" class="link-3"><?php echo $teamname; ?></a>
						</td>	
						<td><?php echo $manager; ?></td>
						<td><?php echo $Rating; ?></td>	
						<td><?php echo $Activ; ?></td>	
						<td><a href="index.php?option=amical&club_id=<?php echo $team_id; ?>" class="link-3"><img src="images/playgame.png" border="0" width="25"></a></td>
					</tr>
	<?php
}
echo "<tr><th colspan=\"6\">";
//partea cu navigarea
$pagini = $numarinreg/$cantitate+1;
//echo "pagini = $numarinreg ::  $pagini";
for($i=1;$i<=$pagini;$i++) {
	if(empty($_REQUEST['page'])) $curenta = 1;
	else $curenta = $_REQUEST['page'];
	
	if($i==$curenta) echo "<font color=\"green\">$i&nbsp;</font>";
	else echo "<a href=\"index.php?option=searchteam&page=$i&NumeEchipa=".$_REQUEST['NumeEchipa']."\" class=\"div-33\">$i</a>&nbsp;";
}
echo "</th></tr>";
mysqli_free_result($res);
?>
</table>
</div>
<br/><br/>