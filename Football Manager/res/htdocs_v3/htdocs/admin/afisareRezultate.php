<?php
include('../app.conf.php');

include('admin.head.php');

?>
<table>

<?php
$sql = "SELECT a.id, b.TeamName, c.TeamName, a.gamedate, a.score, d.name
		FROM gameinvitation a
		LEFT JOIN user b
		ON b.id=a.userId_1
		LEFT JOIN user c
		ON c.id=a.userId_2
		LEFT OUTER JOIN competition d
		ON a.competitionid=d.id
		WHERE a.gamedate='".Date("Y-m-d")."' AND a.accepted=1 ORDER BY a.gamedate DESC";
$res = mysqli_query($GLOBALS['con'],$sql);
$curdate = Date("Y-m-d H:i:s");
echo "$sql<br/>";
while(list($meciID, $e1,$e2, $datameci, $scor, $comp) = mysqli_fetch_row($res)) {
?>
					<tr class="tr-1">
						<td><a href="index.php?option=mecionline&meciID=<?php echo $meciID; ?>" class="link-3"><?php echo "$datameci ($comp)"; ?></a></td>
						<td><?php echo $e1; ?></td>
						<td align="center"><?php 
							//afisare scor doar daca data curenta e mai mare decit data meciului
							echo $scor; 
							?>
						</td>
						<td align="right"><?php echo $e2; ?></td>
						<td>
						<?php
						$sql2 = "SELECT *
								 FROM gametext WHERE gameid=$meciID";
						$res2 = mysqli_query($GLOBALS['con'],$sql2);
						$nrinreg = mysqli_num_rows($res2);
						echo "&nbsp;&nbsp;|&nbsp;&nbsp;$nrinreg comentarii";
						mysqli_free_result($res2);
						?>
						</td>
					</tr>
<?php
}
mysqli_free_result($res);
?>
</table>
<br/><br/>
