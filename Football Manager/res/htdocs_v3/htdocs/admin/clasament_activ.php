<?php
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');
?>
<!--

Clasamentul este afisat in functie de liga din care face parte si de sezon.
In tabelul competitii se creaza pentru fiecare sezon in parte o competitie , iar id-ul merge mai departe in clasament

Dupa fiecare etapa, la ora 13:50, se ruleaza scriptul care introduce rezulatele in clasament, in sensul ca pracurge toate ligile si in functie de scoruri, insereaza datele necesare in tabela de clasament.
-->
<h1>Ranking</h1>
<table class="tf2">
					<tr>
						<th><font color="<?php echo $gk; ?>"></font></th>
						<th><font color="<?php echo $df; ?>">Team</font></th>	
						<!--
						<th><font color="<?php echo $gk; ?>">M</font></th>
						-->
						<th><font color="<?php echo $gk; ?>">V</font></th>
						<th><font color="<?php echo $gk; ?>">D</font></th>
						<th><font color="<?php echo $gk; ?>">L</font></th>
						<th><font color="<?php echo $gk; ?>">GS-GC</font></th>
						
						<th><font color="<?php echo $gk; ?>">Pct</font></th>
					</tr>


<?php
if(!empty($_REQUEST['AfisareClasament'])) {
	$etapa = $_REQUEST['etapanumar'];
	$getapa = $etapa;
	$competitie = $_REQUEST['competitieid'];
} else {
	$competitie = $user->LeagueID;
	$sql = "SELECT MAX(a.etapa) 
			FROM clasament a
			WHERE a.competitieid=$competitie";
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($etapa) = mysqli_fetch_row($res);
	$getapa = $etapa;
	mysqli_free_result($res);
}
/*
$sql = "SELECT a.etapa, b.TeamName, c.nume, a.victorii, a.egaluri, a.infringeri, a.gm, a.gp, a.puncte
		FROM clasament a
		LEFT JOIN user b
		ON b.id=a.userId
		left join competitie c
		on a.competitieId=c.id
		WHERE a.etapa=$etapa AND a.competitieid=$competitie
		ORDER BY a.puncte DESC, a.gm-a.gp DESC, a.gm DESC";
*/
$sql = "SELECT b.id, b.TeamName, c.name, SUM(a.victorii), SUM(a.egaluri), SUM(a.infringeri), SUM(a.gm), SUM(a.gp), SUM(a.puncte), b.lastactive
		FROM clasament a
		LEFT JOIN user b
		ON b.id=a.userId
		left join competition c
		on a.competitieId=c.id
		WHERE a.etapa<=$etapa AND a.competitieid=$competitie
		GROUP BY b.TeamName, c.name 
		ORDER BY SUM(a.puncte) DESC, SUM(a.gm)-SUM(a.gp) DESC, SUM(a.gm) DESC";
//echo "$sql";
$res = mysqli_query($GLOBALS['con'],$sql);
$index=1;
while(list($echipaid, $echipa, $liga, $v,$e, $inf, $gm, $gp, $puncte, $lastactive) = mysqli_fetch_row($res)) {
?>
					<?php
					if($index<2) {
					?>
					<tr>
						<th><?php echo $index."."; ?></th>	
						<th><font class="clasament"><a href="index.php?option=viewclub&club_id=<?php echo $echipaid; ?>" class="link-33"><div class="div-33"><?php echo $echipa; ?></div></a></font></th>
						<!--
						<th><?php echo $etapa; ?></th>
						-->
						<th><?php echo $v; ?></th>
						<th><?php echo $e; ?></th>
						<th><?php echo $inf; ?></th>
						<th><?php echo "$gm-$gp"; ?></th>
						<th><?php echo $puncte." ($lastactive)";	?></th>
					</tr>
					<?php } else { ?>
					<tr>
						<td><?php echo $index."."; ?></td>	
						<td><font class="clasament"><a href="index.php?option=viewclub&club_id=<?php echo $echipaid; ?>" class="link-33"><div class="div-33"><?php echo $echipa; ?></div></a></font></td>
						<!--
						<td><?php echo $etapa; ?></td>
						-->
						<td><?php echo $v; ?></td>
						<td><?php echo $e; ?></td>
						<td><?php echo $inf; ?></td>
						<td><?php echo "$gm-$gp"; ?></td>
						<td><?php echo $puncte." ($lastactive)";	?></td>
					</tr>
<?php
					}
	$index++;
}
mysqli_free_result($res);
?>
<tr>
	<td colspan="7">
		<form action="" method="POST">
		<table class="tf2" width="100%">
			<tr>
				<th>Competition</th>
				<th>
				<select name="competitieid" class="select-2">
				<?php
				$sql = "SELECT id, name, season FROM competition WHERE season<>0 ORDER BY season DESC";
				$rescom = mysqli_query($GLOBALS['con'],$sql);
				while(list($compid, $compnume, $compsezon) = mysqli_fetch_row($rescom)) {
					$selected = $compid == $competitie ? ' selected':'';
					echo "<option value=\"$compid\" $selected>$compnume (Sez. $compsezon)";
				}
				mysqli_free_result($rescom);
				?>
				</select>
				</th>
			</tr>
			<tr>
				<th>Round</th>
				<th>
				<select name="etapanumar" class="select-2">
				<?php
				for($i=1;$i<23;$i++) {
					$selected = $etapa == $i ? ' selected':'';
					echo "<option value=\"$i\" $selected>$i";
				}
				?>
				</select>
				</th>
			</tr>
			<tr>
				<th colspan="2">
				<input type="Submit" name="AfisareClasament" value="Show Ranking" class="button-2">
				</th>
			</tr>
		</table>
		</form>
	</td>
</tr>
<tr>
	<td colspan="7">
	<table class="tf2">
	<?php
	if($getapa == 0) $getapa = 1;
	$sql = "SELECT a.id, a.userId_1, a.userId_2, a.score, a.gamedate, b.TeamName, c.TeamName
			FROM gameinvitation a
			LEFT JOIN user b
			ON b.id=a.userId_1
			LEFT JOIN user c
			ON c.id=a.userId_2
			WHERE a.rround=$getapa AND a.competitionid=$competitie AND a.accepted=1";
	//echo "$sql";
	$reset = mysqli_query($GLOBALS['con'],$sql);
	$i=0;
	while(list($idmeci, $idu1, $idu2, $sc, $dmeci, $t1, $t2) = mysqli_fetch_row($reset)) {
		if($i==0) {
	?>
	<tr>
		<th colspan="3" align="center"><?php echo "Et. $getapa<br/>".$dmeci.' 12:00'; ?></th>
	</tr>
		<?php } ?>
	<tr>
		<td width="110"><a href="index.php?option=viewclub&club_id=<?php echo $idu1; ?>" class="link-2"><?php echo $t1; ?></a></td>
		<td width="50" align="center"><a href="index.php?option=mecionline&meciID=<?php echo $idmeci; ?>" class="link-33">
		<?php 
		$sc = $dmeci." 13:45">Date("Y-m-d H:i") ? $sc = ":" : $sc=$sc;
		echo $sc; 
		?>
		</a>
		</td>
		<td align="right" width="110"><a href="index.php?option=viewclub&club_id=<?php echo $idu2; ?>" class="link-2"><?php echo $t2; ?></a></td>
	</tr>
	<?php
		$i++;
	}
	mysqli_free_result($reset);
	?>
	</table>
	</td>
</tr>
</table>
<br/><br/>
