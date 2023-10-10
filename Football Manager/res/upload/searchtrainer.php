<?php 
include('search.php'); 

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
	if($_REQUEST['pozitie']>-1)
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
<div>
<h2>Antrenori fara contract</h2>
<br/>
<table class="tftable" border="1">
					<tr>
						<th><font color="<?php echo $gk; ?>">Tara</font></th>
						<th><font color="<?php echo $gk; ?>">Antrenor</font></th>
						<th><font color="<?php echo $gk; ?>">Portar</font></th>
						<th><font color="<?php echo $gk; ?>">Fundas</font></th>
						<th><font color="<?php echo $gk; ?>">Mijlocasi</font></th>
						<th><font color="<?php echo $gk; ?>">Atacanti</font></th>
						<th><font color="<?php echo $gk; ?>">Salariu</font></th>
						<th><font color="<?php echo $gk; ?>">Bonus instalare</font></th>
					</tr>

<?php
//cauta toti antrenorii
$sql = "SELECT b.id
		FROM trainer b
		LEFT OUTER JOIN country c 
		ON c.id=b.country
		WHERE b.Contract=0 $wherePozitie $whereReflexe $whereUnulaunu $whereManevrare $whereMarcaj $whereDeposedare
				$whereHeading $whereLong $wherePozitionare $whereSut $whereAtingere $whereCreativitate $whereLansari $wherePase
		ORDER BY b.id ASC";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$numarinreg = mysqli_num_rows($res);
mysqli_free_result($res);

//cauta toti jucatorii care sunt pe lista de transfer si a caror pariere nu a inceput
$sql = "SELECT b.id, b.fname, b.lname, b.Goalkeeping, b.Defence, b.Midfield, b.Attack, b.Wage, b.Contract, c.name, b.bonus
		FROM trainer b
		LEFT OUTER JOIN country c 
		ON c.id=b.country
		WHERE b.Contract=0 $wherePozitie $whereReflexe $whereUnulaunu $whereManevrare $whereMarcaj $whereDeposedare
				$whereHeading $whereLong $wherePozitionare $whereSut $whereAtingere $whereCreativitate $whereLansari $wherePase
		ORDER BY b.id ASC LIMIT $start, $cantitate";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);

while(list($trainer_id, $fname, $lname, $gk, $df, $mf, $at, $wage, $Contract, $country, $bonus) = mysqli_fetch_row($res)) {

	if($detinator == "") {
		$detinator = "Liber de contract";
	}
	?>
	
					<tr class="tr-1">
						<td align="left">
						<?php echo "<img src=\"steaguri/$country.png\" width=\"20\">"; ?></td>
						<td><a href="index.php?option=viewtrainer&pid=<?php echo $trainer_id; ?>&uid=<?php echo $p_userid; ?>" class="link-5"><?php echo "$fname $lname"; ?></a>
						</td>	
						<td><?php echo $gk; ?></td>
						<td><?php echo $df; ?></td>	
						<td><?php echo $mf; ?></td>
						<td><?php echo $at; ?></td>
						<td><?php echo number_format($wage)." &euro;"; ?></td>
						<td><?php echo number_format($bonus)." &euro;"; ?></td>
						</tr>
	<?php
}
echo "<tr><th colspan=\"8\">";
//partea cu navigarea
$pagini = $numarinreg/$cantitate+1;
//echo "pagini = $numarinreg ::  $pagini";
if($pagini>20) $pagini = 20;
for($i=1;$i<=$pagini;$i++) {
	if(empty($_REQUEST['page'])) $curenta = 1;
	else $curenta = $_REQUEST['page'];
	
	if($i==$curenta) echo "<font color=\"green\">$i&nbsp;</font>";
	else echo "<a href=\"index.php?option=searchtrainer&page=$i&pozitie=".$_REQUEST['pozitie']."\" class=\"div-33\">$i</a>&nbsp;";
}
echo "</th></tr>";

mysqli_free_result($res);
?>
</table>
</div>
<br/><br/>