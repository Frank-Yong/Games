<?php
$sql = "SELECT p.id, n.nume, p.pos, n.number 
	FROM player_name n, player p 
	WHERE p.nume=n.nume AND n.sezon=$_SEZON AND p.pos=4
	ORDER BY p.pos";

$res = mysql_query($sql);
while(list($idjucator, $nume, $position, $numar) = mysql_fetch_row($res)) {
?>
		<font class="numar-tricou">
			<?php 
			if ($numar<10) echo "&nbsp;0$numar";
			else echo "&nbsp;".$numar; 
			?>
		</font>
		&nbsp;
		<a class="link-5" href="echipa.php?id=<?php echo $idjucator; ?>&pos=<?php echo $position; ?>">
			<?php echo $nume; ?>
		</a>
		<div class="hr-replace-2"></div>
<?php
}
mysql_free_result($res);
?>

