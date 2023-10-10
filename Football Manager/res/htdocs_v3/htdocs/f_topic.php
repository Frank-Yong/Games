<!--
Afisare topicuri din categorie
-->
<?php Add(); ?>
<br/>
<table class="tftable">
<?php
$sql = "SELECT a.id, a.nume, a.userid, a.data, a.sticky, a.write, c.TeamName, c.UserName, b.id, b.nume
		FROM f_topic a
		LEFT JOIN f_categorii b
		ON a.categorieid=b.id
		LEFT JOIN user c
		ON a.userid=c.id
		WHERE a.categorieid=".$_REQUEST['catid']. "
		ORDER BY a.data DESC";
//echo "$sql<br/>";
$res = mysql_query($sql);
$i=0;
while(list($tid, $tnume, $tuserid, $tdata, $tsticky, $twrite, $team, $manager, $catid, $categorienume) = mysql_fetch_row($res)) {
	if($i==0) echo "<a href=\"f_index.php\">Forum</a> >> <a href=\"f_index.php?catid=$catid\">$categorienume</a><br/>";
	$i++;
	$s = "SELECT a.id, a.data, b.TeamName, b.UserName
		  FROM f_post a 
		  LEFT JOIN user b
		  ON a.userid=b.id
		  WHERE a.topicid=$tid ORDER BY a.data DESC";
	//echo "$s<br/>";
	$r = mysql_query($s);
	while(list($pid, $pdata, $pteam, $pmanager) = mysql_fetch_row($r)) {
		break;
	}
	$nrpost = mysql_num_rows($r);
	
	//f_post` (`id`, `userid`, `topicid`, `nume`, `comentariu`, `data`
	echo "<tr>";
	echo "<th><a href=\"f_index.php?topid=$tid\" class=\"link-3\"><h4>$tnume</h4></a></th>";
	echo "<th><a href=\"index.php?option=viewclub&club_id=$tuserid\" class=\"link-3\">$manager</a><br/>Data: $tdata</th>";
	echo "<th>Postari:$nrpost</th>";
	echo "<th><a href=\"index.php?option=viewclub&club_id=$pid\" class=\"link-3\">$pmanager</a><br/>Data: $pdata</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"4\">$fdescriere</td>";
	echo "</tr>";
	mysql_free_result($r);
	
}
if($i==0) {
	//nu sunt rezultate
	echo "<a href=\"f_index.php\">Forum</a><br/>";
	echo "<tr><td>Nu sunt postari in aceasta categorie!</td></tr>";
}

mysql_free_result($res);
?>
</table>
<?php Add(); ?>

<?php
function Add() {
?>
<a onclick="showComment();" href="javascript:;" class="button-2">Adauga un topic nou</a>
<div class="1" id="comentariu" style="display:none">
	<form action="" method="post">
	<input type="hidden" name="categorieid" value="<?php echo $_REQUEST['catid']; ?>">
	<input type="hidden" name="userid" value="<?php echo $_SESSION['USERID']; ?>">
	<table class="tftable">
	<tr>
		<th>Titlu</th>
		<td><input type="text" name="nume" size="20"></td>
	</tr>
	<tr>
		<th>Text</th>
		<td>
			<textarea name="descriere" cols="40" rows="5"></textarea>
		</td>
	</tr>
	<tr>
	<td colspan="2" align="right">
	<input type="Submit" name="AdaugaTopic" value="Trimite" class="button-2"/>
	</td>
	</tr>
	</table>
	</form>
</div>
<?php
}
?>