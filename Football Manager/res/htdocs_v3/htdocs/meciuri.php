<h1>Meciuri</h1>
<table class="tftable" width="100%" cellpadding="1">
					<tr>
						<th><font color="<?php echo $gk; ?>">Data (click pentru detalii)</font></th>
						<th><font color="<?php echo $df; ?>"></font></th>	
						<th><font color="<?php echo $gk; ?>">Meci</font></th>
						<th><font color="<?php echo $gk; ?>"></font></th>
					</tr>

<?php
$echipaid = !empty($_REQUEST['teamid']) ? $_REQUEST['teamid']:$_SESSION['USERID']; 

$sql = "SELECT a.id, b.TeamName, c.TeamName, a.gamedate, a.score, d.name
		FROM gameinvitation a
		LEFT JOIN user b
		ON b.id=a.userId_1
		LEFT JOIN user c
		ON c.id=a.userId_2
		LEFT OUTER JOIN competition d
		ON a.competitionid=d.id
		WHERE (b.id=$echipaid OR c.id=$echipaid) AND a.accepted=1 ORDER BY a.gamedate DESC";
$res = mysqli_query($GLOBALS['con'],$sql);
$curdate = Date("Y-m-d H:i:s");
//echo "$sql<br/>";
while(list($meciID, $e1,$e2, $datameci, $scor, $numecomp) = mysqli_fetch_row($res)) {
?>
					<tr class="tr-1">
						<td><a href="index.php?option=mecionline&meciID=<?php echo $meciID; ?>" class="link-3">
						<?php 
						if($numecomp == "") $numecomp = "Amical";
						echo "($numecomp)$datameci"; 
						?></a></td>
						<td><?php 
						echo $e1; 
						?></td>
						<td align="center"><?php 
							//afisare scor doar daca data curenta e mai mare decit data meciului
							$sc = $datameci." 13:45">Date("Y-m-d H:i") ? $sc = ":" : $sc=$scor; 
							echo $sc; 
							?>
						</td>
						<td align="right"><?php echo $e2; ?></td>	
					</tr>
					<?php
					//if($datameci." 00:00:00">Date("Y-m-d H:i:s")) break;
}
mysqli_free_result($res);
?>
</table>
<br/><br/>
